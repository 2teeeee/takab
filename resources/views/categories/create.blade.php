<x-main-layout>
    <div class="container py-4">
        <h4>افزودن دسته جدید</h4>

        <form action="{{ route('categories.store') }}" method="POST">
            @include('categories._form')
        </form>
    </div>
</x-main-layout>
