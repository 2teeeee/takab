<x-main-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-7 col-lg-5">

                <div class="card border-0 shadow-sm">

                    {{-- Header --}}
                    <div class="card-header bg-main-light text-center py-4 border-0">

                        <h4 class="mb-1 fw-bold">
                            {{ __('app.signup_in_takab') }}
                        </h4>

                        <div class="text-muted small">
                            اطلاعات خود را برای ایجاد حساب وارد کنید
                        </div>

                    </div>


                    <div class="card-body p-4 p-md-5">

                        {{-- Validation Errors --}}
                        @if($errors->any())

                            <div class="alert alert-danger">

                                <div class="fw-bold mb-2">
                                    {{ __('app.fix_errors') }}
                                </div>

                                <ul class="mb-0 ps-3">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form method="POST"
                              action="{{ route('register') }}">

                            @csrf


                            {{-- Name --}}
                            <div class="mb-3">

                                <label for="name"
                                       class="form-label fw-semibold">

                                    {{ __('app.name') }}

                                </label>

                                <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        autocomplete="name"
                                        autofocus
                                        class="form-control form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('app.namePlaceholder') }}"
                                >

                                @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>


                            {{-- Mobile --}}
                            <div class="mb-3">

                                <label for="mobile"
                                       class="form-label fw-semibold">

                                    {{ __('app.mobile') }}

                                </label>

                                <input
                                        type="tel"
                                        id="mobile"
                                        name="mobile"
                                        value="{{ old('mobile') }}"
                                        autocomplete="tel"
                                        inputmode="numeric"
                                        maxlength="11"
                                        class="form-control form-control @error('mobile') is-invalid @enderror"
                                        placeholder="{{ __('app.mobilePlaceholder') }}"
                                >

                                @error('mobile')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>


                            {{-- Password --}}
                            <div class="mb-3">

                                <label for="password"
                                       class="form-label fw-semibold">

                                    {{ __('app.password') }}

                                </label>

                                <div class="input-group">

                                    <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            autocomplete="new-password"
                                            class="form-control form-control @error('password') is-invalid @enderror"
                                            placeholder="{{ __('app.passwordPlaceholder') }}"
                                    >

                                    <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="togglePassword('password', this)"
                                            aria-label="{{ __('app.showPassword') }}"
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


                            {{-- Confirm Password --}}
                            <div class="mb-4">

                                <label for="password_confirmation"
                                       class="form-label fw-semibold">

                                    {{ __('app.confirmPassword') }}

                                </label>

                                <div class="input-group">

                                    <input
                                            type="password"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            autocomplete="new-password"
                                            class="form-control form-control @error('password_confirmation') is-invalid @enderror"
                                            placeholder="{{ __('app.confirmPasswordPlaceholder') }}"
                                    >

                                    <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="togglePassword('password_confirmation', this)"
                                            aria-label="{{ __('app.showPassword') }}"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                                @error('password_confirmation')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>


                            {{-- Submit --}}
                            <button
                                    type="submit"
                                    class="btn btn-success w-100"
                            >

                                <i class="bi bi-person-plus me-1"></i>

                                {{ __('app.signup') }}

                            </button>

                        </form>


                        {{-- Login --}}
                        <div class="text-center mt-4">

                            <span class="text-muted">
                                {{ __('app.already_registered') }}
                            </span>

                            <a
                                    href="{{ route('login') }}"
                                    class="text-decoration-none fw-semibold"
                            >
                                {{ __('app.login') }}
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Password visibility --}}
    <script>
        function togglePassword(id, button) {

            const input = document.getElementById(id);
            const icon = button.querySelector('i');

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

            } else {

                input.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

            }
        }
    </script>

</x-main-layout>