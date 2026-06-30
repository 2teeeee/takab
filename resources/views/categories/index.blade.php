<x-admin-layout title="لیست دسته ها" header="لیست دسته‌ها">
    <div class="container py-4">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">افزودن دسته جدید</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>عنوان</th>
                <th>کلمات کلیدی</th>
                <th>توضیحات</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->translation->title }}</td>
                    <td>{{ $category->translation->keywords }}</td>
                    <td>{{ Str::limit($category->translation?->description, 80) }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('آیا از حذف اطمینان دارید؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>
</x-admin-layout>
