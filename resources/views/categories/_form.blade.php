@csrf
<div class="mb-3">
    <label for="title" class="form-label">عنوان</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $category->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="keywords" class="form-label">کلمات کلیدی</label>
    <textarea name="keywords" class="form-control" rows="2">{{ old('keywords', $category->keywords ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="description" class="form-label">توضیحات</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div class="form-check form-switch mb-3">
    <input type="checkbox" name="is_assembly_enabled" class="form-check-input" id="is_assembly_enabled"
           value="1" {{ old('is_assembly_enabled', $category->is_assembly_enabled ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_assembly_enabled">
        فعال‌سازی اسمبل برای این دسته
    </label>
</div>

<button type="submit" class="btn btn-success">ذخیره</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">بازگشت</a>
