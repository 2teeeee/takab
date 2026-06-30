<x-admin-layout title="ایجاد نامه جدید" header="ایجاد نامه جدید">
    <div class="container py-4">
        <form action="{{ route('admin.letters.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
            @csrf

            <div class="mb-3">
                <label for="receiver_id" class="form-label">گیرنده</label>
                <select name="receiver_id" id="receiver_id" class="form-select" required>
                    <option value="">انتخاب کنید...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('receiver_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">موضوع</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="form-control" required>
                @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="body" class="form-label">متن نامه</label>
                <textarea name="body" id="body" rows="6" class="form-control" required>{{ old('body') }}</textarea>
                @error('body') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">اولویت</label>
                <select name="priority" class="form-select">
                    <option value="low">کم</option>
                    <option value="medium" selected>متوسط</option>
                    <option value="high">زیاد</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">فایل‌های ضمیمه (اختیاری)</label>
                <input type="file" name="attachments[]" class="form-control" multiple>
                @error('attachments.*') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.letters.index') }}" class="btn btn-secondary">بازگشت</a>
                <button type="submit" class="btn btn-success">ارسال نامه</button>
            </div>
        </form>
    </div>
</x-admin-layout>
