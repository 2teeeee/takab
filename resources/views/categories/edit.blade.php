<x-main-layout>
    <div class="container py-4">
        <h4>ویرایش دسته</h4>

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @method('PUT')
            @include('categories._form')
        </form>
    </div>
</x-main-layout>
