<?php

namespace App\Http\Controllers;

use App\Models\LetterReference;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use App\Services\Sms\NikSmsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $authUser = Auth::user();

        $usersQuery = User::query();

        $isSearch = $request->filled('search');

        // محدودیت دسترسی
        if (
            ! $authUser->hasRole(['admin', 'manager', 'personel']) &&
            ! $isSearch
        ) {
            if($authUser->hasRole('wholesaler')) {
                $usersQuery->where('wholesaler_id', $authUser->id);
            }
            else{
                $usersQuery->where('registered_by', $authUser->id);
            }
        }

        // جستجو
        if ($isSearch) {

            $search = trim($request->search);

            $usersQuery
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('national_code', 'like', "%{$search}%");
                });
        }

        $users = $usersQuery
            ->with('roles')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = self::getRoles();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request, NikSmsService $sms): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'mobile' => [
                'required',
                'string',
                'max:11',
                'unique:users,mobile',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
            ],

            'national_code' => [
                'required',
                'string',
                'digits:10',
                'unique:users,national_code',
            ],

            'moaref_id' => [
                'nullable',
                'exists:users,id',
            ],

            'roles' => [
                'required',
                'array',
                'min:1',
            ],

            'roles.*' => [
                'exists:roles,id',
            ],
        ]);

        try {

            $user = DB::transaction(function () use ($validated) {

                $authUser = Auth::user();

                $user = User::create([
                    'name' => $validated['name'],
                    'mobile' => $validated['mobile'],
                    'password' => Hash::make($validated['password']),
                    'national_code' => $validated['national_code'],

                    'registered_by' => $authUser->id,

                    'wholesaler_id' => $authUser->hasRole('wholesaler')
                        ? $authUser->id
                        : $authUser->wholesaler_id,

                    'moaref_id' => $validated['moaref_id'] ?? null,
                ]);

                $user->roles()->sync($validated['roles']);

                $user->update([
                    'moaref_code' => $user->generateMoarefCode(),
                ]);

                return $user;
            });

        } catch (QueryException $e) {

            /*
             * Unique constraint violation
             *
             * این قسمت مهم است:
             * حتی اگر دو درخواست همزمان ارسال شوند،
             * دیتابیس اجازه ساخت رکورد تکراری نمی‌دهد.
             */
            if ($e->getCode() === '23000') {

                return back()
                    ->withErrors([
                        'mobile' => 'شماره موبایل یا کد ملی قبلاً در سیستم ثبت شده است.',
                    ])
                    ->withInput();
            }

            throw $e;
        }

        /*
         * SMS را بعد از موفقیت کامل Transaction ارسال می‌کنیم.
         */
        $sms->sendSingle(
            $user->mobile,
            "به جمع تک آبی ها خوش آمدید."
            . "\n"
            . "کد معرف شما: "
            . $user->moaref_code
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'کاربر با موفقیت ایجاد شد.');
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
        // فقط admin اجازه حذف دارد
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'شما اجازه حذف کاربر را ندارید.');
        }

        // جلوگیری از حذف خود کاربر
        if ($user->id === Auth::id()) {
            return back()->with('error', 'شما نمی‌توانید حساب کاربری خودتان را حذف کنید.');
        }

        /*
        |--------------------------------------------------------------------------
        | بررسی وابستگی‌های کاربر
        |--------------------------------------------------------------------------
        */

        $dependencies = [];

        // سفارش‌هایی که کاربر به عنوان مشتری دارد
        if ($user->orders()->exists()) {
            $dependencies[] = 'سفارش‌های ثبت شده';
        }

        // محصولاتی که در موجودی کاربر هستند
        if ($user->inventories()->exists()) {
            $dependencies[] = 'موجودی محصولات';
        }

        // نامه‌هایی که کاربر ارسال کرده
        if ($user->sentLetters()->exists()) {
            $dependencies[] = 'نامه‌های ارسال شده';
        }

        // نامه‌هایی که کاربر دریافت کرده
        if ($user->letterReceivers()->exists()) {
            $dependencies[] = 'نامه‌های دریافتی';
        }

        // ارجاع‌هایی که از طرف این کاربر ثبت شده
        if (LetterReference::where('from_user_id', $user->id)->exists()) {
            $dependencies[] = 'ارجاع‌های ثبت شده';
        }

        // ارجاع‌هایی که به این کاربر شده
        if (LetterReference::where('to_user_id', $user->id)->exists()) {
            $dependencies[] = 'ارجاع‌های دریافتی';
        }

        // درخواست‌های نصب
        if ($user->installRequests()->exists()) {
            $dependencies[] = 'درخواست‌های نصب';
        }

        // برنامه‌های نصب
        if ($user->schedules()->exists()) {
            $dependencies[] = 'برنامه‌های نصب';
        }

        /*
        |--------------------------------------------------------------------------
        | registered_by
        |--------------------------------------------------------------------------
        */

        if (User::where('registered_by', $user->id)->exists()) {
            $dependencies[] = 'کاربرانی توسط این شخص ثبت شده‌اند';
        }

        /*
        |--------------------------------------------------------------------------
        | moaref_id
        |--------------------------------------------------------------------------
        */

        if (User::where('moaref_id', $user->id)->exists()) {
            $dependencies[] = 'کاربرانی توسط این شخص معرفی شده‌اند';
        }

        /*
        |--------------------------------------------------------------------------
        | wholesaler_id
        |--------------------------------------------------------------------------
        */

        if (User::where('wholesaler_id', $user->id)->exists()) {
            $dependencies[] = 'کاربران یا فروشگاه‌های وابسته به این عمده‌فروش وجود دارند';
        }

        /*
        |--------------------------------------------------------------------------
        | جلوگیری از حذف
        |--------------------------------------------------------------------------
        */

        if (!empty($dependencies)) {

            $message = 'این کاربر به دلیل داشتن اطلاعات وابسته قابل حذف نیست: '
                . implode('، ', $dependencies);

            return back()->with('error', $message);
        }

        /*
        |--------------------------------------------------------------------------
        | حذف امن
        |--------------------------------------------------------------------------
        */

        try {

            // چون کاربر هیچ وابستگی ندارد،
            // ابتدا رابطه نقش‌ها را پاک می‌کنیم.
            $user->roles()->detach();

            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'کاربر با موفقیت حذف شد.');

        } catch (Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'حذف کاربر انجام نشد. اطلاعات کاربر وابستگی دارد یا خطایی در سیستم رخ داده است.'
            );
        }
    }

    static function getRoles(): Collection
    {
        $user = Auth::user();

        $rolesQuery = Role::query();
        if ($user->hasRole('admin'))
            $rolesQuery->whereIn('name', ['admin', 'manager', 'personel', 'wholesaler', 'marketer', 'seller', 'nasab', 'user']);
        elseif ($user->hasRole('manager'))
            $rolesQuery->whereIn('name', ['personel', 'wholesaler', 'marketer', 'seller', 'nasab', 'user']);
        elseif ($user->hasRole('personel'))
            $rolesQuery->whereIn('name', ['wholesaler', 'marketer', 'seller', 'nasab', 'user']);
        elseif ($user->hasRole('wholesaler'))
            $rolesQuery->whereIn('name', ['marketer', 'seller']);
        elseif ($user->hasRole('marketer'))
            $rolesQuery->whereIn('name', ['seller']);
        elseif ($user->hasRole('seller'))
            $rolesQuery->whereIn('name', ['user']);

        return $rolesQuery->get();
    }
}
