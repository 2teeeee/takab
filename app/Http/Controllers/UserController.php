<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ویرایش شد.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'کاربر حذف شد.');
    }
}
