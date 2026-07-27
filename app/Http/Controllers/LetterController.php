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
use Symfony\Component\HttpFoundation\StreamedResponse;

class LetterController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $letters = Letter::query()
            ->with(['sender', 'receiver']);

        // دسترسی
        if (!$user->hasRole('admin')) {
            $letters->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Tabs
        |--------------------------------------------------------------------------
        */

        switch ($request->get('tab')) {

            case 'received':
                $letters->where('receiver_id', $user->id);
                break;

            case 'sent':
                $letters->where('sender_id', $user->id);
                break;

            case 'unread':
                $letters->where('receiver_id', $user->id)
                    ->where('status', 'new');
                break;

            case 'read':
                $letters->where('receiver_id', $user->id)
                    ->where('status', 'read');
                break;

            case 'referred':
                $letters->where('status', 'referred');
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
                    ->orWhereHas('receiver', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });

            });
        }

        $letters = $letters
            ->latest()
            ->paginate(15)
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
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'attachments.*' => 'nullable|file|max:2048',
        ]);

        $validated['sender_id'] = Auth::id();
        $letter = Letter::create($validated);

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

        $message = <<<TEXT
        یک نامه جدید برای شما ثبت شده است.
        
        موضوع:
        {$letter->subject}
        
        $letter->url
        TEXT;

        $sms->sendSingle(
            $letter->receiver->mobile,
            $message
        );

        return redirect()->route('admin.letters.show', $letter->id)
            ->with('success', 'نامه با موفقیت ارسال شد.');
    }

    public function show(Letter $letter): View
    {
        $user = auth()->user();

        $isAdmin = $user->roles()->where('name', 'admin')->exists();

        if (! $isAdmin && $letter->sender_id !== $user->id && $letter->receiver_id !== $user->id) {
            abort(403, 'شما به این نامه دسترسی ندارید.');
        }

        if ($letter->receiver_id === $user->id && $letter->status === 'new') {
            $letter->update(['status' => 'read']);
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

        LetterReference::create([
            'letter_id' => $letter->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $validated['to_user_id'],
            'note' => $validated['note'] ?? null,
        ]);

        // به‌روزرسانی وضعیت نامه
        $letter->update(['status' => 'referred']);

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

        if (
            $letter->sender_id !== Auth::id() &&
            $letter->receiver_id !== Auth::id() &&
            ! Auth::user()->hasRole('admin')
        ) {
            abort(403, 'شما اجازه حذف این ضمیمه را ندارید.');
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
        if ($letter->sender_id !== Auth::id() && $letter->receiver_id !== Auth::id()) {
            abort(403, 'شما اجازه مشاهده این نامه را ندارید.');
        }
    }
}
