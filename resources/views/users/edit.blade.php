<x-main-layout>
    <div class="container py-4">
        <h4>ویرایش کاربر</h4>
        <form action="{{ route('users.update', $user) }}" method="POST">
            @method('PUT')
            @include('users._form')
        </form>
    </div>
</x-main-layout>
