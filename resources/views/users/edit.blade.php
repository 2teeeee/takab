<x-admin-layout title="ویرایش کاربر" header="ویرایش کاربر">
    <div class="container py-4">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @method('PUT')
            @include('users._form')
        </form>
    </div>
</x-admin-layout>
