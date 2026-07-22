<x-admin-layout title="افزودن کاربر جدید" header="افزودن کاربر جدید">
    <div class="container py-4">

        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary mb-3">بازگشت به لیست کاربران</a>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @include('users._form')
        </form>
    </div>
</x-admin-layout>
