<x-admin-layout title="مدیریت اسلایدرها" header="مدیریت اسلایدرها">
    <div class="container py-4">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary mb-3">➕ افزودن اسلاید جدید</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>تصویر</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($sliders as $slider)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $slider->title ?? '-' }}</td>
                    <td><img src="{{ asset('storage/' . $slider->image_path) }}" alt="" width="100"></td>
                    <td>
                        @if($slider->is_active)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-secondary">غیرفعال</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('آیا از حذف اطمینان دارید؟')" class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">هیچ اسلایدی وجود ندارد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $sliders->links() }}
    </div>
</x-admin-layout>
