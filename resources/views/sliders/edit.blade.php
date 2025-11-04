<x-admin-layout title="ویرایش اسلاید" header="ویرایش اسلاید">
    <div class="container py-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- عنوان --}}
            <div class="mb-3">
                <label class="form-label">عنوان</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}">
            </div>

            {{-- زیرعنوان --}}
            <div class="mb-3">
                <label class="form-label">زیرعنوان</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $slider->subtitle) }}">
            </div>

            {{-- تصویر فعلی --}}
            <div class="mb-3">
                <label class="form-label d-block">تصویر فعلی</label>
                <img src="{{ asset('storage/' . $slider->image_path) }}" alt="تصویر اسلاید" class="rounded shadow-sm" width="200">
            </div>

            {{-- آپلود تصویر جدید --}}
            <div class="mb-3">
                <label class="form-label">تغییر تصویر (اختیاری)</label>
                <input type="file" name="image_path" class="form-control">
                <small class="text-muted">در صورت عدم انتخاب، تصویر فعلی باقی می‌ماند.</small>
            </div>

            {{-- متن دکمه --}}
            <div class="mb-3">
                <label class="form-label">متن دکمه</label>
                <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $slider->button_text) }}">
            </div>

            {{-- لینک دکمه --}}
            <div class="mb-3">
                <label class="form-label">لینک دکمه</label>
                <input type="url" name="button_link" class="form-control" value="{{ old('button_link', $slider->button_link) }}">
            </div>

            {{-- وضعیت فعال بودن --}}
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                    {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">فعال باشد</label>
            </div>

            <div class="d-flex justify-content-between">
                <button class="btn btn-success">💾 ذخیره تغییرات</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">↩ بازگشت</a>
            </div>
        </form>
    </div>
</x-admin-layout>
