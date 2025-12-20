<x-main-layout>
    <div class="row justify-content-center mx-0 my-5 py-5">
        <div class="col-md-4 border rounded-1 px-0">
            <h5 class="bg-main-light text-center p-2 rounded-top-1">{{ __('app.signup_in_takab') }}</h5>
            <form method="POST" action="{{ route('register') }}" class="p-2">
                @csrf

                <div class="input-group mb-3">
                    <span class="input-group-text" id="name-l">{{ __('app.name') }}</span>
                    <input type="text" class="form-control" aria-label="name-l" aria-describedby="{{ __('app.name') }}" name="name">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="mobile-l">{{ __('app.mobile') }}</span>
                    <input type="text" class="form-control" aria-label="mobile-l" aria-describedby="{{ __('app.mobile') }}" name="mobile">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="password-l">{{ __('app.password') }}</span>
                    <input type="password" class="form-control" aria-label="password-l" aria-describedby="{{ __('app.password') }}" name="password">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="password-confirmation-l">{{ __('app.confirmPassword') }}</span>
                    <input type="password" class="form-control" aria-label="password-confirmation-l" aria-describedby="{{ __('app.confirmPassword') }}" name="password_confirmation">
                </div>
                <div class="d-flex">
                    <a href="{{route('login')}}" class="btn btn-link me-auto text-decoration-none text-primary text-small">{{ __('app.before_registered') }}...</a>
                    <button type="submit" class="btn btn-success ms-auto">
                        {{ __('app.signup') }}</button>
                </div>
            </form>
        </div>

    </div>

</x-main-layout>
