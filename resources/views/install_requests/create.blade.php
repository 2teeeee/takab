<x-admin-layout title="ثبت درخواست نصب" header="ثبت درخواست نصب جدید">

    <div class="container py-4">
        <form method="POST" action="{{ route('admin.install_requests.store') }}" class="card p-4 shadow-sm">
            @csrf

            <div class="mb-3">
                <label class="form-label">کاربر</label>
                <select name="user_id" class="form-select" required>
                    <option value="">انتخاب کاربر...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">مدل دستگاه</label>
                <input type="text" name="device_model" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">شماره سریال</label>
                <input type="text" name="serial_number" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">آدرس نصب</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">ثبت درخواست</button>
        </form>
    </div>

</x-admin-layout>
