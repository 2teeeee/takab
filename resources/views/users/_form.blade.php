@csrf
<div class="mb-3">
    <label class="form-label">نام</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">موبایل</label>
    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">رمز عبور</label>
    <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
    @if(isset($user))
        <small class="text-muted">فقط در صورت تغییر رمز عبور، مقدار وارد کنید.</small>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">تکرار رمز عبور</label>
    <input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div>

<div class="mb-3">
    <label class="form-label">نقش‌ها</label>
    <select name="roles[]" class="form-select" multiple>
        @foreach($roles as $role)
            <option value="{{ $role->id }}"
                    {{ isset($user) && $user->roles->contains($role->id) ? 'selected' : '' }}>
                {{ $role->label ?? $role->name }}
            </option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-success">ذخیره</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">بازگشت</a>
