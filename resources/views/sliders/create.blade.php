<x-admin-layout title="افزودن اسلاید جدید" header="افزودن اسلاید جدید">
    <div class="container py-4">
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">عنوان</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">زیرعنوان</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">تصویر</label>
                <input type="file" name="image_path" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">متن دکمه</label>
                <input type="text" name="button_text" class="form-control" value="{{ old('button_text') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">لینک دکمه</label>
                <input type="url" name="button_link" class="form-control" value="{{ old('button_link') }}">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" checked>
                <label class="form-check-label">فعال باشد</label>
            </div>

            <button class="btn btn-success">ذخیره</button>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">بازگشت</a>
        </form>
    </div>
</x-admin-layout>
