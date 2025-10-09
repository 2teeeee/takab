<x-admin-layout title="افزودن کاربر جدید" header="افزودن کاربر جدید">
    <div class="container py-4">
        <form action="{{ route('users.store') }}" method="POST">
            @include('users._form')
        </form>
    </div>
</x-admin-layout>
