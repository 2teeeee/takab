<x-admin-layout title="برنامه نصب جدید" header="ثبت برنامه نصب جدید">

    <div class="container py-4">
        <form method="POST" action="{{ route('admin.install_schedules.store') }}" class="card p-4 shadow-sm">
            @csrf

            <div class="mb-3">
                <label class="form-label">نصاب</label>
                <select name="installer_id" class="form-select" required>
                    <option value="">انتخاب نصاب...</option>
                    @foreach($installers as $installer)
                        <option value="{{ $installer->id }}">{{ $installer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">درخواست نصب</label>
                <select name="install_request_id" class="form-select" required>
                    <option value="">انتخاب درخواست...</option>
                    @foreach($requests as $req)
                        <option value="{{ $req->id }}">
                            {{ $req->user->name }} - {{ $req->device_model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">تاریخ نصب</label>
                <input type="date" name="scheduled_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">ثبت برنامه نصب</button>
        </form>
    </div>

</x-admin-layout>
