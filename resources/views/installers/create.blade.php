<x-admin-layout title="ایجاد نصاب جدید" header="ایجاد نصاب جدید">

    <div class="container py-4">

        {{-- بازگشت به لیست --}}
        <a href="{{ route('admin.installers.index') }}"
           class="btn btn-sm btn-secondary mb-3">
            <i class="bi bi-chevron-double-right"></i>
            بازگشت به لیست نصاب‌ها
        </a>

        <form action="{{ route('admin.installers.store') }}"
              method="POST"
              class="card shadow-sm p-4">

            @csrf

            {{-- اطلاعات کاربر --}}
            <div class="mb-4">

                <h5 class="mb-3">
                    <i class="bi bi-person"></i>
                    اطلاعات نصاب
                </h5>

                <div class="row">

                    {{-- نام --}}
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            نام و نام خانوادگی
                            <span class="text-danger">*</span>
                        </label>

                        <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required>

                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- موبایل --}}
                    <div class="col-md-6 mb-3">
                        <label for="mobile" class="form-label">
                            شماره موبایل
                            <span class="text-danger">*</span>
                        </label>

                        <input
                                type="text"
                                name="mobile"
                                id="mobile"
                                value="{{ old('mobile') }}"
                                class="form-control @error('mobile') is-invalid @enderror"
                                maxlength="11"
                                inputmode="numeric"
                                placeholder="09123456789"
                                required>

                        @error('mobile')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- کد ملی --}}
                    <div class="col-md-6 mb-3">
                        <label for="national_code" class="form-label">
                            کد ملی
                            <span class="text-danger">*</span>
                        </label>

                        <input
                                type="text"
                                name="national_code"
                                id="national_code"
                                value="{{ old('national_code') }}"
                                class="form-control @error('national_code') is-invalid @enderror"
                                maxlength="10"
                                inputmode="numeric"
                                placeholder="0012345678"
                                required>

                        @error('national_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- اطلاعات ورود --}}
            <div class="mb-4">

                <h5 class="mb-3">
                    <i class="bi bi-shield-lock"></i>
                    اطلاعات ورود
                </h5>

                <div class="row">

                    {{-- رمز عبور --}}
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            رمز عبور
                            <span class="text-danger">*</span>
                        </label>

                        <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                minlength="6"
                                required>

                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        <small class="text-muted">
                            حداقل ۶ کاراکتر
                        </small>
                    </div>

                </div>
            </div>


            {{-- اطلاعات تخصصی نصاب --}}
            <div class="mb-4">

                <h5 class="mb-3">
                    <i class="bi bi-tools"></i>
                    اطلاعات تخصصی نصاب
                </h5>

                <div class="row">

                    {{-- سابقه کاری --}}
                    <div class="col-md-6 mb-3">
                        <label for="experience" class="form-label">
                            سابقه کار
                        </label>

                        <div class="input-group">

                            <input
                                    type="number"
                                    name="experience"
                                    id="experience"
                                    value="{{ old('experience') }}"
                                    class="form-control @error('experience') is-invalid @enderror"
                                    min="0"
                                    placeholder="مثلاً 5">

                            <span class="input-group-text">
                                سال
                            </span>

                        </div>

                        @error('experience')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- آدرس --}}
                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">
                            آدرس
                        </label>

                        <textarea
                                name="address"
                                id="address"
                                rows="3"
                                maxlength="1000"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="آدرس محل سکونت یا محل فعالیت نصاب">{{ old('address') }}</textarea>

                        @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- توضیحات --}}
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">
                            توضیحات
                        </label>

                        <textarea
                                name="description"
                                id="description"
                                rows="5"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="توضیحات تکمیلی درباره نصاب...">{{ old('description') }}</textarea>

                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- وضعیت --}}
            <div class="alert alert-warning">

                <i class="bi bi-info-circle"></i>

                پس از ثبت، این نصاب با وضعیت
                <strong>در انتظار تأیید</strong>
                ثبت می‌شود و باید توسط مدیریت بررسی و تأیید شود.

            </div>


            {{-- دکمه‌ها --}}
            <div class="d-flex justify-content-between mt-3">

                <a href="{{ route('admin.installers.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-right"></i>
                    بازگشت

                </a>

                <button type="submit"
                        class="btn btn-success">

                    <i class="bi bi-person-plus"></i>
                    ثبت نصاب

                </button>

            </div>

        </form>

    </div>

</x-admin-layout>