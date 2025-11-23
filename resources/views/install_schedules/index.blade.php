<x-admin-layout title="برنامه‌های نصب" header="لیست برنامه‌های نصب">

    <div class="container py-4">
        <a href="{{ route('admin.install_schedules.create') }}" class="btn btn-primary mb-3">➕ افزودن برنامه جدید</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>نصاب</th>
                <th>کاربر</th>
                <th>مدل دستگاه</th>
                <th>تاریخ نصب</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $schedule->installer->name ?? '-' }}</td>
                    <td>{{ $schedule->installRequest->user->name ?? '-' }}</td>
                    <td>{{ $schedule->installRequest->device_model }}</td>
                    <td>{{ jdate($schedule->scheduled_date)->format('Y/m/d') }}</td>
                    <td>
                        <form action="{{ route('admin.install_schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('آیا از حذف اطمینان دارید؟')" class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">برنامه‌ای وجود ندارد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $schedules->links() }}
    </div>

</x-admin-layout>
