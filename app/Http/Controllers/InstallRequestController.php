<?php

namespace App\Http\Controllers;

use App\Models\InstallRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InstallRequestController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $requests = InstallRequest::with('user')->latest()->paginate(10);
        } else {
            $requests = InstallRequest::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('install_requests.index', compact('requests'));
    }

    public function create(): View
    {
        $users = User::all();
        return view('install_requests.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'address' => 'required|string',
        ]);

        InstallRequest::create($data);

        return redirect()->route('admin.install_requests.index')->with('success', 'درخواست نصب با موفقیت ثبت شد.');
    }

    public function edit(InstallRequest $installRequest): View
    {
        $users = User::all();
        return view('install_requests.edit', compact('installRequest', 'users'));
    }

    public function update(Request $request, InstallRequest $installRequest): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:pending,scheduled,installed,serviced,cancelled',
        ]);

        $installRequest->update($data);

        return redirect()->route('admin.install_requests.index')->with('success', 'درخواست نصب ویرایش شد.');
    }

    public function destroy(InstallRequest $installRequest): RedirectResponse
    {
        $installRequest->delete();
        return back()->with('success', 'درخواست حذف شد.');
    }
}
