<x-profile-layout title="تغییر رمز عبور">
    <form action="{{ route('profile.password.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>رمز فعلی</label>
            <input type="password" name="current_password" class="form-control">
        </div>
        <div class="mb-3">
            <label>رمز جدید</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>تکرار رمز جدید</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button class="btn btn-primary">تغییر رمز</button>
    </form>
</x-profile-layout>
