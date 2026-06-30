<x-admin-layout title="ویرایش دسته" header="ویرایش دسته">
    <div class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @method('PUT')
            @include('categories._form')
        </form>
    </div>
</x-admin-layout>
