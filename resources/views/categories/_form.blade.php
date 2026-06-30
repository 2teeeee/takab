@csrf
@php
    $languages = [
        'fa' => 'فارسی',
        'en' => 'انگلیسی',
        'ar' => 'عربی',
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

<div class="tab-content border rounded-3 p-2">
    @foreach($languages as $locale => $label)
        @php
            $tr = $category->translations->firstWhere('locale', $locale);
        @endphp

        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="lang-{{ $locale }}">

            <div class="mb-3">
                <label class="form-label">عنوان ({{ $label }})</label>
                <input type="text"
                       name="title[{{ $locale }}]"
                       class="form-control"
                       value="{{ old("title.$locale", $tr->title ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">کلمات کلیدی</label>
                <textarea name="keywords[{{ $locale }}]"
                          class="form-control"
                          rows="2">{{ old("keywords.$locale", $tr->keywords ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">توضیحات</label>
                <textarea name="description[{{ $locale }}]"
                          class="form-control"
                          rows="4">{{ old("description.$locale", $tr->description ?? '') }}</textarea>
            </div>

        </div>
    @endforeach
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
