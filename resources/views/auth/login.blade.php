<x-main-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

                {{-- Login Card --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="bg-main-light text-center px-4 py-4">

                        <div class="mb-3">
                            <div
                                    class="d-inline-flex align-items-center justify-content-center
                                       bg-white rounded-circle shadow-sm"
                                    style="width: 64px; height: 64px;"
                            >
                                <i class="bi bi-person-lock fs-3 text-success"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-1">
                            {{ __('app.signin_tp_takab') }}
                        </h4>

                        <p class="text-muted small mb-0">
                            {{ __('app.login_subtitle') }}
                        </p>

                    </div>


                    {{-- Form --}}
                    <div class="card-body p-4 p-md-5">

                        <x-auth-session-status
                                class="mb-3"
                                :status="session('status')"
                        />

                        {{-- General Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3 small mb-4">

                                <div class="d-flex align-items-start">

                                    <i class="bi bi-exclamation-circle-fill me-2 mt-1"></i>

                                    <div>
                                        <strong>
                                            {{ __('app.login_error_title') }}
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
                                action="{{ route('authenticate') }}"
                                novalidate
                        >

                            @csrf


                            {{-- Mobile --}}
                            <div class="mb-3">

                                <label
                                        for="mobile"
                                        class="form-label fw-semibold"
                                >
                                    {{ __('app.mobile') }}
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-phone"></i>
                                    </span>

                                    <input
                                            type="text"
                                            id="mobile"
                                            name="mobile"
                                            value="{{ old('mobile') }}"
                                            autocomplete="tel"
                                            inputmode="tel"
                                            autofocus
                                            class="form-control
                                            @error('mobile') is-invalid @enderror"
                                            placeholder="{{ __('app.mobile_placeholder') }}"
                                    >

                                    @error('mobile')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>

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
                                            class="form-control
                                            @error('password') is-invalid @enderror"
                                            placeholder="{{ __('app.password_placeholder') }}"
                                    >

                                    <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            id="toggle-password"
                                            aria-label="{{ __('app.show_password') }}"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>

                            </div>


                            {{-- Remember --}}
                            <div class="d-flex align-items-center mb-4">

                                <div class="form-check">

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

                            </div>


                            {{-- Login Button --}}
                            <button
                                    type="submit"
                                    class="btn btn-success w-100 py-2 fw-semibold rounded-3"
                            >
                                <i class="bi bi-box-arrow-in-right me-1"></i>

                                {{ __('app.login') }}

                            </button>

                        </form>


                        {{-- Register --}}
                        <div class="text-center mt-4 pt-3 border-top">

                            <span class="text-muted small">
                                {{ __('app.before_not_registered') }}
                            </span>

                            <a
                                    href="{{ route('register') }}"
                                    class="text-decoration-none fw-semibold text-primary"
                            >
                                {{ __('app.register') }}
                            </a>

                        </div>

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