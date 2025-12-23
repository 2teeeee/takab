<x-admin-layout title="مدیریت صفحات" header="مدیریت صفحات">
    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>آدرس (slug)</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($pages as $page)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $page->translation?->title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>
                        @if($page->is_active)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-secondary">غیرفعال</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-warning">ویرایش</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">صفحه‌ای وجود ندارد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $pages->links() }}
    </div>
</x-admin-layout>
