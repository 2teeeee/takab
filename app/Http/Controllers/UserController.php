<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\Sms\NikSmsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $usersQuery = User::query();
        if(!$user->hasRole(['admin', 'manager','personel']))
            $usersQuery->where('registered_by', $user->id);
        $users = $usersQuery->with('roles')->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = self::getRoles();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request, NikSmsService $sms): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|string|min:6',
            'national_code' => 'required|string|min:10|max:10',
            'moaref_id' => 'nullable',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['moaref_code'] = rand(111111,999999);
        $data['registered_by'] = Auth::id();

        $user = User::create($data);
        $user->moaref_code = $user->id.rand(111111,999999);

        $user->save();

        $sms->sendSingle($request->mobile, "به جمع تک آبی ها خوش آمدید."."\n"."کد معرف شما:".$user->moaref_code);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function edit(User $user): View
    {
        $roles = self::getRoles();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'password' => 'nullable|string|min:6',
            'national_code' => 'required|string|min:10|max:10',
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

    static function getRoles(): Collection
    {
        $user = Auth::user();

        $rolesQuery = Role::query();
        if ($user->hasRole('admin'))
            $rolesQuery->whereIn('name', ['admin','manager', 'personel', 'wholesaler', 'marketer', 'seller', 'nasab', 'user']);
        elseif ($user->hasRole('manager'))
            $rolesQuery->whereIn('name', ['personel', 'wholesaler', 'marketer', 'seller', 'nasab', 'user']);
        elseif ($user->hasRole('personel'))
            $rolesQuery->whereIn('name', ['wholesaler', 'marketer', 'seller', 'nasab', 'user']);
        elseif ($user->hasRole('wholesaler'))
            $rolesQuery->whereIn('name', ['marketer', 'seller', 'user']);
        elseif ($user->hasRole('marketer'))
            $rolesQuery->whereIn('name', ['seller', 'user']);
        elseif ($user->hasRole('seller'))
            $rolesQuery->whereIn('name', ['nasab', 'user']);

        return $rolesQuery->get();
    }
}
