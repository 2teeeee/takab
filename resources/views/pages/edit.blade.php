<x-admin-layout title="ویرایش صفحه" header="ویرایش صفحه">
    <div class="container py-4">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST">
            @csrf
            @method('PUT')

            @php
                $languages = [
                    'fa' => '🇮🇷 فارسی',
                    'en' => '🇺🇸 English',
                    'ar' => '🇸🇦 العربية',
                ];
            @endphp

            <ul class="nav nav-tabs mb-3">
                @foreach($languages as $locale => $label)
                    <li class="nav-item">
                        <a href="#{{ $locale }}" class="nav-link {{ $loop->first ? 'active' : '' }}"
                           data-bs-toggle="tab"
                           data-bs-target="#lang-{{ $locale }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content border rounded-2 p-2">
                @foreach($languages as $locale => $label)
                    @php
                        $tr = $page->translations->firstWhere('locale', $locale);
                    @endphp

                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="lang-{{ $locale }}">

                        <div class="mb-3">
                            <label class="form-label">عنوان صفحه ({{ $label }})</label>
                            <input type="text" name="title[{{ $locale }}]"
                                   class="form-control"
                                   value="{{ old("title.$locale", $tr->title ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">محتوا</label>
                            <textarea name="content[{{ $locale }}]"
                                      class="form-control editor"
                                      rows="10">{{ old("content.$locale", $tr->content ?? '') }}</textarea>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mb-3">
                <label class="form-label">آدرس (slug)</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
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
