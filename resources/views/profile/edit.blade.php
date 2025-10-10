<x-profile-layout title="ویرایش اطلاعات کاربر">
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>نام</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>ایمیل</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>موبایل</label>
            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="form-control">
        </div>
        <button class="btn btn-success">ذخیره تغییرات</button>
    </form>
</x-profile-layout>
