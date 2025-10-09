<x-admin-layout title="ویرایش دسته" header="ویرایش دسته">
    <div class="container py-4">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @method('PUT')
            @include('categories._form')
        </form>
    </div>
</x-admin-layout>
