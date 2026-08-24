<?php

namespace App\Http\Controllers;

use App\Models\Installer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InstallerController extends Controller
{
    /**
     * Display installers list.
     */
    public function index(Request $request): View
    {
        $usersQuery = User::query()
            ->role('installer')
            ->with('installer');

        // Search installers
        if ($request->filled('search')) {

            $search = trim($request->search);

            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('national_code', 'like', "%{$search}%");
            });
        }

        // Filter by installer status
        if ($request->filled('status')) {

            $usersQuery->whereHas('installer', function ($query) use ($request) {
                $query->where('status', $request->status);
            });
        }

        $users = $usersQuery
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('installers.index', compact('users'));
    }


    /**
     * Show installer creation form.
     */
    public function create(): View
    {
        return view('installers.create');
    }


    /**
     * Store a new installer.
     */
    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([

            // User information
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'mobile' => [
                'required',
                'string',
                'max:11',
                'unique:users,mobile',
            ],

            'national_code' => [
                'required',
                'string',
                'size:10',
                'unique:users,national_code',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
            ],

            // Installer information
            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'experience' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['name'],
                'mobile' => $validated['mobile'],
                'national_code' => $validated['national_code'],
                'password' => Hash::make($validated['password']),
                'registered_by' => Auth::id(),
            ]);

            $user->assignRole('installer');

            Installer::create([
                'user_id' => $user->id,
                'address' => $validated['address'] ?? null,
                'experience' => $validated['experience'] ?? null,
                'description' => $validated['description'] ?? null,

                // New installers must be reviewed by management
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('admin.installers.index')
            ->with(
                'success',
                'اطلاعات نصاب با موفقیت به سیستم اضافه شد و منتظر تایید مدیریت می باشد.'
            );
    }


    /**
     * Display installer details.
     */
    public function show(User $user): View
    {
        $user->load([
            'installer',
            'installer.wholesalers',
            'roles',
        ]);

        return view(
            'installers.show',
            compact('user')
        );
    }


    /**
     * Show installer edit form.
     */
    public function edit(User $user): View
    {
        $user->load('installer');

        return view(
            'installers.edit',
            compact('user')
        );
    }


    /**
     * Update installer information.
     */
    public function update(
        Request $request,
        User $user
    ): RedirectResponse {

        abort_unless(
            $user->hasRole('installer'),
            404
        );

        $installer = $user->installer;

        $validated = $request->validate([

            // User information
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'mobile' => [
                'required',
                'string',
                'max:11',
                'unique:users,mobile,' . $user->id,
            ],

            'national_code' => [
                'required',
                'string',
                'size:10',
                'unique:users,national_code,' . $user->id,
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

            // Installer information
            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'experience' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $user,
            $installer
        ) {

            $userData = [
                'name' => $validated['name'],
                'mobile' => $validated['mobile'],
                'national_code' => $validated['national_code'],
            ];

            // Update password only if a new password was provided
            if (!empty($validated['password'])) {
                $userData['password'] =
                    Hash::make($validated['password']);
            }

            $user->update($userData);

            // Update installer-specific information
            if ($installer) {

                $installer->update([
                    'address' =>
                        $validated['address'] ?? null,

                    'experience' =>
                        $validated['experience'] ?? null,

                    'description' =>
                        $validated['description'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.installers.index')
            ->with(
                'success',
                'اطلاعات نصااب با موفقیت ویرایش شد.'
            );
    }

    /**
     * Approve installer by management.
     */
    public function approve(User $user): RedirectResponse
    {
        abort_unless(
            $user->hasRole('installer'),
            404
        );

        $installer = $user->installer;

        abort_unless($installer, 404);

        $installer->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'نصاب با موفقیت تایید شد.'
        );
    }


    /**
     * Reject installer.
     */
    public function reject(
        Request $request,
        User $user
    ): RedirectResponse {

        abort_unless(
            $user->hasRole('installer'),
            404
        );

        $request->validate([
            'status_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $installer = $user->installer;

        abort_unless($installer, 404);

        $installer->update([
            'status' => 'rejected',
            'status_note' => $request->status_note,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'نصاب مورد تایید قرار نگرفت.'
        );
    }


    /**
     * Delete installer.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_unless(
            $user->hasRole('installer'),
            404
        );

        DB::transaction(function () use ($user) {

            // Delete installer-specific information
            $user->installer?->delete();

            // Remove installer role
            $user->removeRole('installer');

            // Delete user account
            $user->delete();
        });

        return redirect()
            ->route('admin.installers.index')
            ->with(
                'success',
                'نصاب با موفقیت حذف شد.'
            );
    }
}