<x-admin-layout title="افزودن کاربر جدید" header="افزودن کاربر جدید">
    <div class="container py-4">

        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary mb-3">بازگشت به لیست کاربران</a>

        <form action="{{ route('admin.users.store') }}" method="POST" id="create-user-form">
            @include('users._form')
        </form>
    </div>

    <script>
        document.getElementById('create-user-form').addEventListener('submit', function () {

            const button = document.getElementById('submit-user');

            button.disabled = true;

            button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-1"></span>
        در حال ثبت...
    `;
        });
    </script>
</x-admin-layout>
