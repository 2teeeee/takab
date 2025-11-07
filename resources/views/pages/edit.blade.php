<x-admin-layout title="ویرایش صفحه" header="ویرایش صفحه">
    <div class="container py-4">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">عنوان صفحه</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">آدرس (slug)</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">محتوا</label>
                <textarea id="editor" name="content" class="form-control" rows="8">{{ old('content', $page->content) }}</textarea>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" {{ $page->is_active ? 'checked' : '' }}>
                <label class="form-check-label">فعال باشد</label>
            </div>

            <button class="btn btn-success">💾 ذخیره تغییرات</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">بازگشت</a>
        </form>
    </div>


    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#editor'), {
            language: 'fa',
            ckfinder: {
                uploadUrl: "{{ route('admin.products.uploadImage') }}?&_token={{ csrf_token() }}"
            }
        }).catch(error => {
            console.error(error);
        });
    </script>
</x-admin-layout>
