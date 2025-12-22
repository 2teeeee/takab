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

            @php
                $languages = [
                    'fa' => '🇮🇷 فارسی',
                    'en' => '🇺🇸 English',
                    'ar' => '🇸🇦 العربية',
                ];
            @endphp

            <ul class="nav nav-tabs mb-3">
                @foreach($languages as $key => $label)
                    <li class="nav-item">
                        <a href="#{{ $locale }}" class="nav-link {{ $loop->first ? 'active' : '' }}"
                                data-bs-toggle="tab"
                                data-bs-target="#lang-{{ $key }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content border rounded-2 p-2">
                @foreach($languages as $locale => $label)
                    @php
                        $tr = $product->translations->firstWhere('locale', $locale);
                    @endphp

                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="lang-{{ $locale }}">

                        <div class="mb-3">
                            <label class="form-label">نام محصول ({{ $label }})</label>
                            <input type="text" name="title[{{ $locale }}]"
                                   class="form-control"
                                   value="{{ old("title.$locale", $tr->title ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">توضیح کوتاه</label>
                            <textarea name="small_text[{{ $locale }}]"
                                      class="form-control"
                                      rows="2">{{ old("small_text.$locale", $tr->small_text ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">توضیحات کامل</label>
                            <textarea name="large_text[{{ $locale }}]"
                                      class="form-control editor"
                                      rows="10">{{ old("large_text.$locale", $tr->large_text ?? '') }}</textarea>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">کلمات کلیدی</label>
                            <textarea name="keywords[{{ $locale }}]"
                                      class="form-control"
                                      rows="2">{{ old("keywords.$locale", $tr->keywords ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">توضیح موتور جستجو</label>
                            <textarea name="description[{{ $locale }}]"
                                      class="form-control"
                                      rows="2">{{ old("description.$locale", $tr->description ?? '') }}</textarea>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسلاگ (اختیاری)</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">دسته‌بندی محصول</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">قیمت اصلی</label>
                    <input type="number" step="0.01" name="main_price" class="form-control" value="{{ old('main_price', $product->main_price) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">قیمت فروش</label>
                    <input type="number" step="0.01" name="sell_price" class="form-control" value="{{ old('sell_price', $product->sell_price) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">قیمت فروشنده</label>
                    <input type="number" step="0.01" name="seller_price" class="form-control" value="{{ old('seller_price', $product->seller_price) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_assembly_enabled" id="is_assembly_enabled"
                               value="1" {{ old('is_assembly_enabled', $product->is_assembly_enabled) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_assembly_enabled">این محصول قابلیت اسمبل دارد</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_main_sale" id="is_main_sale" checked
                               value="1" {{ old('is_main_sale', $product->is_main_sale) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_main_sale">نمایش در فروش اصلی</label>
                    </div>
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
        document.querySelectorAll('.editor').forEach(el => {
            ClassicEditor.create(el, {
                language: 'fa',
                ckfinder: {
                    uploadUrl: "{{ route('admin.products.uploadImage') }}?_token={{ csrf_token() }}"
                }
            });
        });
    </script>

</x-admin-layout>
