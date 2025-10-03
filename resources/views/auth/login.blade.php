<x-main-layout>
    <div class="row justify-content-center mx-0 my-5 py-5">
        <div class="col-md-4 border rounded-1 px-0">
            <h5 class="bg-main-light text-center p-2 rounded-top-1">ورود به تک آب صنعت ارم</h5>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}" class="p-2">
                @csrf
                <div class="input-group mb-3">
                    <span class="input-group-text" id="mobile-l">{{ __('mobile') }}</span>
                    <input type="text" class="form-control" aria-label="mobile-l" aria-describedby="{{ __('mobile') }}" name="mobile">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="password-l">{{ __('password') }}</span>
                    <input type="password" class="form-control" aria-label="password-l" aria-describedby="{{ __('password') }}" name="password">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-text border rounded me-1">
                        <input class="form-check-input mt-0" type="checkbox" id="remember_me" name="remember" value="1" aria-label="Checkbox for following text input">
                    </div>
                    <label class="form-check-label form-control border-0" for="remember">{{ __('Remember me') }}</label>
                </div>
                <div class="d-flex">
                    <a href="{{route('register')}}" class="btn btn-link me-auto text-decoration-none text-primary text-small">قبلا ثبت نام نکرده ام!</a>
                    <button type="submit" class="btn btn-success ms-auto">
                        {{ __('login') }}</button>
                </div>

            </form>
        </div>

    </div>

</x-main-layout>
