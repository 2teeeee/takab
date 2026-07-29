<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\LetterReference;
use App\Models\Attachment;
use App\Models\User;
use App\Services\Sms\NikSmsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LetterController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $letters = Letter::query()->with([
            'sender',
            'receiverItems.user',
            'references' => function ($q) {
                $q->latest();
            },
        ]);

        if(!$user->hasRole('admin')){
            $letters->where(function ($q) use ($user){
                $q->where('sender_id',$user->id)
                    ->orWhereHas('receiverItems',function($q) use ($user){
                        $q->where('user_id',$user->id);
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Tabs
        |--------------------------------------------------------------------------
        */

        switch ($request->get('tab')) {

            case 'received':
                $letters->whereHas('receiverItems',function($q) use ($user){
                    $q->where('user_id',$user->id);
                });
                break;

            case 'sent':
                $letters->where('sender_id', $user->id);
                break;

            case 'unread':
                $letters->whereHas('receiverItems',function($q) use ($user){
                    $q->where('user_id',$user->id)
                        ->where('status','new');
                });
                break;

            case 'read':
                $letters->whereHas('receiverItems',function($q) use ($user){
                    $q->where('user_id',$user->id)
                        ->where('status','read');
                });
                break;

            case 'referred':
                $letters->whereHas('references');
                break;

            default:
                // همه نامه ها
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $letters->where(function ($q) use ($search) {

                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhereHas('sender', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('receiverItems.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });

            });
        }


        $letters->leftJoin('letter_receivers as lr', function ($join) use ($user) {
            $join->on('letters.id', '=', 'lr.letter_id')
                ->where('lr.user_id', $user->id);
        });

        $letters->select('letters.*')
            ->orderByRaw("
                CASE
                    WHEN lr.created_at IS NULL THEN 1
                    ELSE 0
                END
            ")
            ->orderByDesc('lr.last_received_at')
            ->orderByDesc('letters.created_at');


        $letters = $letters
            ->paginate(25)
            ->withQueryString();

        return view('letters.index', compact('letters'));
    }

    public function create(): View
    {
        $user = auth()->user();

        $usersQuery = User::query()
            ->where('id', '!=', $user->id)
            ->whereHas('roles', function ($q) {
                $q->where('name', '!=', 'user');
            });

        $users = $usersQuery->get();

        return view('letters.create', compact('users'));
    }

    public function store(Request $request, NikSmsService $sms): RedirectResponse
    {
        $validated = $request->validate([
            'receiver_ids' => 'required|array|min:1',
            'receiver_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'attachments.*' => 'nullable|file|max:2048',
        ]);

        try {

            $letter = Letter::create([
                'sender_id' => Auth::id(),
                'subject' => $validated['subject'],
                'body' => $validated['body'],
                'priority' => $validated['priority'],
            ]);

            foreach ($validated['receiver_ids'] as $receiverId) {
                $letter->receiverItems()->create([
                    'user_id' => $receiverId,
                    'status' => 'new',
                    'last_received_at'=>now(),
                ]);
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    );
                    $extension = $file->getClientOriginalExtension();
                    $random = Str::random(8);
                    $fileName = $originalName . '_' . $random . '.' . $extension;
                    $path = $file->storeAs(
                        'attachments',
                        $fileName,
                        'public'
                    );
                    Attachment::create([
                        'letter_id' => $letter->id,
                        'file_path' => $path,
                        'file_name' => $fileName,
                    ]);
                }
            }

            foreach ($letter->receiverItems as $receiver){

                $message = <<<TEXT
یک نامه جدید برای شما ثبت شده است.

موضوع:
{$letter->subject}

{$letter->url}
TEXT;

                $sms->sendSingle(
                    $receiver->user->mobile,
                    $message
                );
            }

            return redirect()->route('admin.letters.show', $letter->id)
                ->with('success', 'نامه با موفقیت ارسال شد.');

        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with('error', 'خطایی در ثبت نامه رخ داد.');
        }
    }

    public function show(Letter $letter): View
    {
        $user = auth()->user();

        $letter->load([
            'sender',
            'receiverItems.user',
            'attachments',
        ]);

        $isAdmin = $user->roles()->where('name', 'admin')->exists();


        $receiver = $letter->receiverItems()
            ->where('user_id',$user->id)
            ->first();

        $isReceiver = $receiver !== null;

        if (
            ! $isAdmin &&
            $letter->sender_id !== $user->id &&
            ! $isReceiver
        ) {
            abort(403, 'شما به این نامه دسترسی ندارید.');
        }

        if($receiver && $receiver->status=='new'){
            $receiver->update([
                'status'=>'read',
                'read_at'=>now(),
            ]);
        }

        $references = $letter->references()->with(['from', 'to'])->latest()->get();

        $usersQuery = User::query()
            ->where('id', '!=', $user->id)
            ->whereHas('roles', function ($q) {
                $q->where('name', '!=', 'user');
            });

        $referableUsers = $usersQuery->get();

        return view('letters.show', compact('letter', 'references', 'referableUsers'));
    }

    public function refer(Request $request, Letter $letter, NikSmsService $sms): RedirectResponse
    {
        $this->authorizeView($letter);

        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'note' => 'nullable|string|max:1000',
        ]);

        try {
            LetterReference::create([
                'letter_id' => $letter->id,
                'from_user_id' => Auth::id(),
                'to_user_id' => $validated['to_user_id'],
                'note' => $validated['note'] ?? null,
            ]);

            $letter->receiverItems()->updateOrCreate(
                [
                    'user_id' => $validated['to_user_id'],
                ],
                [
                    'status' => 'new',
                    'read_at' => null,
                    'last_received_at' => now(),
                ]
            );

            $userRef = User::find($validated['to_user_id']);

            $message = <<<TEXT
        یک نامه جدید برای شما ثبت شده است.
        
        موضوع:
        {$letter->subject}
        
        $letter->url
        TEXT;

            $sms->sendSingle(
                $userRef->mobile,
                $message
            );

            return back()->with('success', 'نامه با موفقیت ارجاع داده شد.');

        } catch (Throwable $e) {

            report($e);

            return back()
            ->withInput()
            ->with('error', 'خطایی در ثبت نامه رخ داد.');
        }
    }

    public function storeAttachment(Request $request, Letter $letter): RedirectResponse
    {
        $this->authorizeView($letter);

        $request->validate([
            'attachments.*' => 'required|file|max:2048',
        ]);

        foreach ($request->file('attachments') as $file) {

            $originalName = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $extension = $file->getClientOriginalExtension();
            $random = Str::random(8);

            $fileName = $originalName . '_' . $random . '.' . $extension;

            $path = $file->storeAs(
                'attachments',
                $fileName,
                'public'
            );

            Attachment::create([
                'letter_id' => $letter->id,
                'file_path' => $path,
                'file_name' => $fileName,
            ]);
        }

        return back()->with('success', 'ضمیمه‌ها با موفقیت اضافه شدند.');
    }

    public function destroyAttachment(Attachment $attachment): RedirectResponse
    {
        $letter = $attachment->letter;

        $isReceiver = $letter->receiverItems()
            ->where('user_id', Auth::id())
            ->exists();

        if (
            $letter->sender_id !== Auth::id() &&
            ! $isReceiver &&
            ! Auth::user()->hasRole('admin')
        ) {
            abort(403);
        }

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return back()->with('success', 'ضمیمه با موفقیت حذف شد.');
    }

    public function downloadAttachment(Attachment $attachment): StreamedResponse
    {
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    protected function authorizeView(Letter $letter): void
    {
        if (Auth::user()->hasRole('admin')) {
            return;
        }

        $isReceiver = $letter->receiverItems()
            ->where('user_id', Auth::id())
            ->exists();

        if (
            $letter->sender_id !== Auth::id() &&
            ! $isReceiver
        ) {
            abort(403, 'شما اجازه مشاهده این نامه را ندارید.');
        }
    }
}
