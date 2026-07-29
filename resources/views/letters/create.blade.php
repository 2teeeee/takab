<x-admin-layout title="ایجاد نامه جدید" header="ایجاد نامه جدید">
    <div class="container py-4">

        <a href="{{ route('admin.letters.index') }}" class="btn btn-sm btn-secondary mb-3">
            <i class="bi bi-chevron-double-right"></i>
            بازگشت به لیست نامه ها
        </a>

        <form action="{{ route('admin.letters.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="card shadow-sm p-4">

            @csrf

            {{-- گیرندگان --}}
            <div class="mb-3">
                <label class="form-label">گیرندگان</label>

                <select
                        id="receiver_ids"
                        name="receiver_ids[]"
                        class="form-select"
                        multiple
                        required>

                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                                @selected(in_array($user->id, old('receiver_ids', [])))>
                            {{ $user->name }}
                        </option>
                    @endforeach

                </select>

                @error('receiver_ids')
                <small class="text-danger">{{ $message }}</small>
                @enderror

                @error('receiver_ids.*')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- موضوع --}}
            <div class="mb-3">
                <label for="subject" class="form-label">
                    موضوع
                </label>

                <input
                        type="text"
                        name="subject"
                        id="subject"
                        value="{{ old('subject') }}"
                        class="form-control"
                        required>

                @error('subject')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- متن --}}
            <div class="mb-3">
                <label for="body" class="form-label">
                    متن نامه
                </label>

                <textarea
                        name="body"
                        id="body"
                        rows="8"
                        class="form-control"
                        required>{{ old('body') }}</textarea>

                @error('body')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- اولویت --}}
            <div class="mb-3">
                <label class="form-label">
                    اولویت
                </label>

                <select name="priority" class="form-select">

                    <option value="low"
                            @selected(old('priority')=='low')>
                        کم
                    </option>

                    <option value="medium"
                            @selected(old('priority','medium')=='medium')>
                        متوسط
                    </option>

                    <option value="high"
                            @selected(old('priority')=='high')>
                        زیاد
                    </option>

                </select>
            </div>

            {{-- ضمیمه --}}
            <div class="mb-3">
                <label class="form-label">
                    فایل‌های ضمیمه
                </label>

                <input
                        type="file"
                        name="attachments[]"
                        class="form-control"
                        multiple>

                @error('attachments.*')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-between">

                <a href="{{ route('admin.letters.index') }}"
                   class="btn btn-secondary">
                    بازگشت
                </a>

                <button type="submit"
                        class="btn btn-success">
                    <i class="bi bi-send"></i>
                    ارسال نامه
                </button>

            </div>

        </form>

    </div>

    @push('scripts')
        <script>
            $(function () {
                $('#receiver_ids').select2({
                    theme: 'bootstrap-5',
                    dir: 'rtl',
                    width: '100%',
                    placeholder: 'گیرندگان را انتخاب کنید',
                    allowClear: true,
                    language: {
                        noResults: function () {
                            return "موردی یافت نشد";
                        }
                    }
                });
            });
        </script>
    @endpush

</x-admin-layout>