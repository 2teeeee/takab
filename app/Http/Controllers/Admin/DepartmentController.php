<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::query()
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view(
            'admin.departments.index',
            compact('departments')
        );
    }

    public function users(Department $department): View
    {
        $department->load('users');

        $selectedUsers = $department->users;

        return view(
            'admin.departments.users',
            compact(
                'department',
                'selectedUsers'
            )
        );
    }

    public function updateUsers(
        Request $request,
        Department $department
    ) {
        $validated = $request->validate([
            'users' => ['nullable', 'array'],
            'users.*' => ['integer', 'exists:users,id'],
        ]);

        $department->users()->sync(
            $validated['users'] ?? []
        );

        return redirect()
            ->route(
                'admin.departments.users',
                $department
            )
            ->with(
                'success',
                'کاربران دپارتمان با موفقیت بروزرسانی شدند.'
            );
    }
}