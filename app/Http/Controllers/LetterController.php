<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\LetterReference;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LetterController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->roles()->where('name', 'admin')->exists()) {
            // اگر ادمین است: همه نامه‌ها را ببیند
            $letters = Letter::with(['sender', 'receiver'])->latest()->paginate(15);
        } else {
            // اگر کاربر عادی است: فقط نامه‌های خودش را ببیند
            $letters = Letter::with(['sender', 'receiver'])
                ->where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                })
                ->latest()
                ->paginate(15);
        }

        return view('letters.index', compact('letters'));
    }

    public function create(): View
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', '!=', 'user');
        })->where('id', '!=', auth()->id())->get();

        return view('letters.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
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

        // ذخیره فایل‌های ضمیمه
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                Attachment::create([
                    'letter_id' => $letter->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

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

        // کاربران قابل ارجاع (غیر از user)
        $referableUsers = User::whereHas('roles', fn($q) => $q->where('name', '!=', 'user'))
            ->where('id', '!=', $user->id)
            ->get();

        return view('letters.show', compact('letter', 'references', 'referableUsers'));
    }

    public function refer(Request $request, Letter $letter): RedirectResponse
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

        return back()->with('success', 'نامه با موفقیت ارجاع داده شد.');
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
