@csrf

<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">

        <!-- عنوان فرم -->
        <h5 class="mb-4 fw-bold text-primary border-bottom pb-2">فرم کاربر</h5>

        <!-- نام -->
        <div class="mb-3">
            <label class="form-label fw-semibold">نام <span class="text-danger">*</span></label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="مثلاً علی رضایی"
                   value="{{ old('name', $user->name ?? '') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- موبایل -->
        <div class="mb-3">
            <label class="form-label fw-semibold">شماره موبایل <span class="text-danger">*</span></label>
            <input type="text" name="mobile"
                   class="form-control @error('mobile') is-invalid @enderror"
                   placeholder="0912xxxxxxx"
                   value="{{ old('mobile', $user->mobile ?? '') }}" required>
            @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- رمز عبور -->
        <div class="mb-3">
            <label class="form-label fw-semibold">رمز عبور {{ isset($user) ? '(اختیاری)' : '' }}</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="رمز عبور خود را وارد کنید"
                {{ isset($user) ? '' : 'required' }}>
            @if(isset($user))
                <small class="text-muted">در صورت عدم تغییر رمز عبور، این فیلد را خالی بگذارید.</small>
            @endif
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- تکرار رمز عبور -->
        <div class="mb-3">
            <label class="form-label fw-semibold">تکرار رمز عبور {{ isset($user) ? '(اختیاری)' : '' }}</label>
            <input type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="تکرار رمز عبور"
                {{ isset($user) ? '' : 'required' }}>
            @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- نقش‌ها -->
        <div class="mb-4">
            <label class="form-label fw-semibold d-block mb-2">نقش‌ها</label>
            <div class="row g-2">
                @foreach($roles as $role)
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check border rounded-3 p-2 ps-4 bg-light hover-shadow-sm">
                            <input class="form-check-input me-2"
                                   type="checkbox"
                                   name="roles[]"
                                   id="role_{{ $role->id }}"
                                   value="{{ $role->id }}"
                                {{ (isset($user) && $user->roles->contains($role->id)) || in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="role_{{ $role->id }}">
                                {{ $role->label ?? $role->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('roles')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- دکمه‌ها -->
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-right"></i> بازگشت
            </a>
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-check2-circle"></i> ذخیره
            </button>
        </div>

    </div>
</div>
