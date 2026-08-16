<x-main-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="text-center px-4 py-4">

                        <div class="mb-3">

                            <img
                                    src="{{ asset('img/hydrojoy-logo.png') }}"
                                    alt="Hydrojoy"
                                    width="200"
                                    class="img-fluid"
                            >

                        </div>

                        <h5 class="fw-bold mb-1">
                            ورود به حساب کاربری
                        </h5>

                        <p class="text-muted small mb-0">
                            برای ورود، کد ملی و رمز عبور خود را وارد کنید.
                        </p>

                    </div>


                    {{-- Form --}}
                    <div class="card-body p-4">

                        <x-auth-session-status
                                class="mb-3"
                                :status="session('status')"
                        />


                        {{-- General errors --}}
                        @if ($errors->any())

                            <div class="alert alert-danger rounded-3 small mb-4">

                                <div class="d-flex align-items-start">

                                    <i class="bi bi-exclamation-circle-fill me-2 mt-1"></i>

                                    <div>

                                        <strong>
                                            {{ __('auth.login_error_title') }}
                                        </strong>

                                        <ul class="mb-0 mt-2 ps-3">

                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        @endif


                        <form
                                method="POST"
                                action="{{ route('hydrojoy.login') }}"
                                novalidate
                        >

                            @csrf


                            {{-- National Code --}}
                            <div class="mb-3">

                                <label
                                        for="national_code"
                                        class="form-label fw-semibold"
                                >
                                    کد ملی
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-person-vcard"></i>
                                    </span>

                                    <input
                                            type="text"
                                            id="national_code"
                                            name="national_code"
                                            value="{{ old('national_code') }}"
                                            inputmode="numeric"
                                            autocomplete="username"
                                            autofocus
                                            class="form-control @error('national_code') is-invalid @enderror"
                                            placeholder="کد ملی خود را وارد کنید"
                                    >

                                </div>

                                @error('national_code')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>


                            {{-- Password --}}
                            <div class="mb-3">

                                <label
                                        for="password"
                                        class="form-label fw-semibold"
                                >
                                    {{ __('app.password') }}
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-lock"></i>
                                    </span>

                                    <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            autocomplete="current-password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="رمز عبور خود را وارد کنید"
                                    >

                                    <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            id="toggle-password"
                                            aria-label="نمایش رمز عبور"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                                @error('password')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>


                            {{-- Remember Me --}}
                            <div class="form-check mb-4">

                                <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="remember_me"
                                        name="remember"
                                        value="1"
                                        @checked(old('remember'))
                                >

                                <label
                                        class="form-check-label small"
                                        for="remember_me"
                                >
                                    {{ __('app.Remember_me') }}
                                </label>

                            </div>


                            {{-- Login --}}
                            <button
                                    type="submit"
                                    class="btn btn-success w-100 py-2 fw-semibold rounded-3"
                            >

                                <i class="bi bi-box-arrow-in-right me-1"></i>

                                {{ __('app.login') }}

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Password Toggle --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');

            if (!passwordInput || !toggleButton) {
                return;
            }

            toggleButton.addEventListener('click', function () {

                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {

                    passwordInput.type = 'text';

                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');

                } else {

                    passwordInput.type = 'password';

                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');

                }

            });

        });

    </script>

</x-main-layout>