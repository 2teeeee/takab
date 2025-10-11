<x-admin-layout title="{{ $product->exists ? 'ویرایش محصول' : 'افزودن محصول جدید' }}"
                header="{{ $product->exists ? 'ویرایش محصول' : 'افزودن محصول جدید' }}">
    <div class="container py-4">
        <h4>{{ $product->exists ? 'ویرایش محصول' : 'افزودن محصول جدید' }}</h4>

        <form method="POST"
              action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if($product->exists)
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">نام محصول</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $product->title) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسلاگ (اختیاری)</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">توضیح کوتاه</label>
                <textarea name="small_text" class="form-control" rows="2">{{ old('small_text', $product->small_text) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">توضیحات کامل</label>
                <textarea id="editor" name="large_text" class="form-control" rows="10">{{ old('large_text', $product->large_text) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">قیمت اصلی</label>
                    <input type="number" step="0.01" name="main_price" class="form-control" value="{{ old('main_price', $product->main_price) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">قیمت فروش</label>
                    <input type="number" step="0.01" name="sell_price" class="form-control" value="{{ old('sell_price', $product->sell_price) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">تصاویر محصول</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*" onchange="previewImages(event)">
            </div>

            <div id="preview" class="d-flex flex-wrap gap-2 mb-3"></div>

            {{-- تصاویر فعلی --}}
            @if($product->exists && $product->images->count())
                <div class="mb-3">
                    <label class="form-label">تصاویر فعلی:</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->images as $img)
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $img->small_image_name) }}" class="img-thumbnail" width="120">
                                <label class="position-absolute top-0 start-0 bg-white px-1">
                                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"> حذف
                                </label>
                                @if($img->is_main)
                                    <span class="badge bg-success position-absolute bottom-0 start-0">اصلی</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <button class="btn btn-primary">{{ $product->exists ? 'ویرایش محصول' : 'افزودن محصول' }}</button>
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
