<x-admin-layout title="ویرایش درخواست نصب" header="ویرایش درخواست نصب">

    <div class="container py-4">
        <form method="POST" action="{{ route('admin.install_requests.update', $installRequest) }}" class="card p-4 shadow-sm">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">کاربر</label>
                <select name="user_id" class="form-select" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected($user->id == $installRequest->user_id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">مدل دستگاه</label>
                <input type="text" name="device_model" class="form-control" value="{{ $installRequest->device_model }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">شماره سریال</label>
                <input type="text" name="serial_number" class="form-control" value="{{ $installRequest->serial_number }}">
            </div>

            <div class="mb-3">
                <label class="form-label">آدرس</label>
                <textarea name="address" class="form-control" rows="3">{{ $installRequest->address }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">وضعیت</label>
                <select name="status" class="form-select">
                    <option value="pending" @selected($installRequest->status=='pending')>در انتظار</option>
                    <option value="scheduled" @selected($installRequest->status=='scheduled')>برنامه‌ریزی شده</option>
                    <option value="installed" @selected($installRequest->status=='installed')>نصب شده</option>
                    <option value="serviced" @selected($installRequest->status=='serviced')>سرویس شده</option>
                    <option value="cancelled" @selected($installRequest->status=='cancelled')>لغو شده</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">ویرایش</button>
        </form>
    </div>

</x-admin-layout>
