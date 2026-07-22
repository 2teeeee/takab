<x-admin-layout title="ویرایش کاربر" header="ویرایش کاربر">
    <div class="container py-4">

        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary mb-3">بازگشت به لیست کاربران</a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary mb-3">افزودن کاربر جدید</a>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @method('PUT')
            @include('users._form')
        </form>
    </div>
</x-admin-layout>
