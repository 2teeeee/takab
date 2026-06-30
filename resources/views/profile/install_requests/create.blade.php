<x-profile-layout title="ثبت درخواست نصب / سرویس">

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('profile.install_requests.index') }}" class="btn btn-outline-secondary btn-sm">بازگشت</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.install_requests.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">نوع عملیات</label>
                {{-- اگر مایل باشی می‌تونی این فیلد رو ذخیره کنی؛ در اسکیمای تو فقط نصب/service از status استفاده شده --}}
                <select name="type" class="form-select d-none">
                    <option value="install" selected>install</option>
                    <option value="service">service</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">مدل دستگاه <span class="text-danger">*</span></label>
                <input type="text" name="device_model" class="form-control" value="{{ old('device_model') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">شماره سریال</label>
                <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">آدرس دقیق <span class="text-danger">*</span></label>
                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">تاریخ/زمان پیشنهادی (اختیاری)</label>
                <input type="datetime-local" name="preferred_date" class="form-control" value="{{ old('preferred_date') }}">
            </div>

            <button class="btn btn-primary">ثبت درخواست</button>
        </form>
    </div>
</x-profile-layout>
