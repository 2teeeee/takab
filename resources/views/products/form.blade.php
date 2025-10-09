<x-main-layout>
    <div class="container py-4">
        <h4>{{ $product->exists ? 'ویرایش محصول' : 'افزودن محصول جدید' }}</h4>

        <form method="POST"
              action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}"
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
                <textarea id="editor" name="large_text" class="form-control" rows="6">{{ old('large_text', $product->large_text) }}</textarea>
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

    {{-- ادیتور متن --}}
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            directionality: 'rtl',
            language: 'fa',
            height: 300,
            plugins: 'link image lists code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code'
        });

        function previewImages(event) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            Array.from(event.target.files).forEach(file => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.classList.add('img-thumbnail');
                img.style.width = '120px';
                preview.appendChild(img);
            });
        }
    </script>
</x-main-layout>
