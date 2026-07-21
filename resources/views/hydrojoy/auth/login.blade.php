<x-main-layout>
    <div class="row justify-content-center mx-0 my-5 py-5">
        <div class="col-md-4 border rounded-1 px-0">
            <h5 class="text-center p-2 rounded-top-1">
                <img src="{{asset("img/hydrojoy-logo.png")}}" alt="heydojoy logo" width="200">
            </h5>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('hydrojoy.login') }}" class="p-2">
                @csrf
                <div class="input-group mb-3">
                    <span class="input-group-text" id="national-code">کد ملی</span>
                    <input type="text" class="form-control" aria-label="national-code" aria-describedby="کد ملی" name="national_code">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="password">{{ __('app.password') }}</span>
                    <input type="password" class="form-control" aria-label="password" aria-describedby="{{ __('app.password') }}" name="password">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-text border rounded me-1">
                        <input class="form-check-input mt-0" type="checkbox" id="remember_me" name="remember" value="1" aria-label="Checkbox for following text input">
                    </div>
                    <label class="form-check-label form-control border-0" for="remember">{{ __('app.Remember_me') }}</label>
                </div>
                <div class="d-flex">
                    <button type="submit" class="btn btn-success ms-auto">
                        {{ __('app.login') }}</button>
                </div>

            </form>
        </div>

    </div>

</x-main-layout>
