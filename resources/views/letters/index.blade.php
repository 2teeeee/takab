<x-admin-layout title="لیست نامه‌ها" header="لیست نامه‌ها">
    <div class="container py-4">

        <a href="{{ route('admin.letters.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> ایجاد نامه جدید
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>موضوع</th>
                <th>فرستنده</th>
                <th>گیرنده</th>
                <th>اولویت</th>
                <th>وضعیت</th>
                <th>تاریخ</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($letters as $letter)
                <tr>
                    <td>{{ $loop->iteration + ($letters->currentPage() - 1) * $letters->perPage() }}</td>
                    <td>{{ $letter->subject }}</td>
                    <td>{{ $letter->sender->name }}</td>
                    <td>{{ $letter->receiver->name }}</td>
                    <td>
                        <span class="badge
                            @if($letter->priority == 'high') bg-danger
                            @elseif($letter->priority == 'medium') bg-warning
                            @else bg-success @endif">
                            {{ ucfirst($letter->priority) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $letter->status }}</span>
                    </td>
                    <td>{{ jdate($letter->created_at)->format('Y/m/d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.letters.show', $letter) }}" class="btn btn-sm btn-info text-white">
                            مشاهده
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">هیچ نامه‌ای یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $letters->links() }}
    </div>
</x-admin-layout>
