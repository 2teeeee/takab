<x-admin-layout title="افزودن دسته" header="افزودن دسته جدید">
    <div class="container py-4">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @include('categories._form')
        </form>
    </div>
</x-admin-layout>
