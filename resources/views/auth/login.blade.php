<x-main-layout>
    <div class="row justify-content-center my-5 py-5">
        <div class="col-md-4 border rounded-1 p-2">
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="mobile" class="form-label">{{ __('mobile') }}</label>
                    <input type="text" class="form-control" id="mobile" name="mobile">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('password') }}</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('login') }}</button>
            </form>
        </div>

    </div>

</x-main-layout>
