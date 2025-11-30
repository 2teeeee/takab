<x-admin-layout title="درخواست‌های نصب" header="لیست درخواست‌های نصب">

    <div class="container py-4">
        <a href="{{ route('admin.install_requests.create') }}" class="btn btn-primary mb-3">➕ ثبت درخواست جدید</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>کاربر</th>
                <th>مدل دستگاه</th>
                <th>وضعیت</th>
                <th>تاریخ نصب</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $request->user->name ?? '-' }}</td>
                    <td>{{ $request->device_model }}</td>
                    <td>
                        <x-status_badge status="{{ $request->status }}" />
                    </td>
                    <td>{{ $request->installation_date ? jdate($request->installation_date)->format('Y/m/d') : '-' }}</td>
                    <td>
                        <a href="{{ route('admin.install_requests.edit', $request) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        <form action="{{ route('admin.install_requests.destroy', $request) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('آیا از حذف اطمینان دارید؟')" class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">درخواستی ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $requests->links() }}
    </div>
</x-admin-layout>
