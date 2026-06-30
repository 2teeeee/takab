<x-admin-layout title="سرویس‌های دوره‌ای" header="لیست سرویس‌های دوره‌ای">

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>کاربر</th>
                <th>مدل دستگاه</th>
                <th>آخرین سرویس</th>
                <th>سرویس بعدی</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($services as $service)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $service->installRequest->user->name }}</td>
                    <td>{{ $service->installRequest->device_model }}</td>
                    <td>{{ $service->last_service_date ? jdate($service->last_service_date)->format('Y/m/d') : '-' }}</td>
                    <td>{{ $service->next_service_date ? jdate($service->next_service_date)->format('Y/m/d') : '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.periodic_services.update', $service) }}" class="d-inline">
                            @csrf @method('PUT')
                            <input type="date" name="last_service_date" required class="form-control form-control-sm d-inline-block w-auto">
                            <button class="btn btn-sm btn-primary">ثبت سرویس</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">سرویسی یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $services->links() }}
    </div>

</x-admin-layout>
