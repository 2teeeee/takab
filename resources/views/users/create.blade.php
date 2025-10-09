<x-main-layout>
    <div class="container py-4">
        <h4>افزودن کاربر جدید</h4>
        <form action="{{ route('users.store') }}" method="POST">
            @include('users._form')
        </form>
    </div>
</x-main-layout>
