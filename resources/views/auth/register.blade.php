<x-main-layout>
    <div class="row justify-content-center my-5 py-5">
        <div class="col-md-4 border rounded-1 p-2">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('nsme') }}</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">{{ __('mobile') }}</label>
                    <input type="text" class="form-control" id="mobile" name="mobile">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('password') }}</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('confirmPassword') }}</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('register') }}</button>
            </form>
        </div>

    </div>

</x-main-layout>
