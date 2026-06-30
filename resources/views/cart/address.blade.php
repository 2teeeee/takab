<x-main-layout>
    <div class="container py-4">
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 66%">
                    <span class="fw-bold text-small">{{ __('app.step') }} ۲ {{ __('app.from') }} ۳: {{ __('app.address') }}</span>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2 small text-muted">
                <span>{{ __('app.basket') }}</span>
                <span>{{ __('app.address') }}</span>
                <span>{{ __('app.payment') }}</span>
            </div>
        </div>

        <h4 class="mb-4">{{ __('app.address') }}</h4>

        <div class="row">
            <!-- سمت راست: فرم آدرس -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="address-form" action="{{ route('cart.pay') }}" method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('app.address') }}</label>
                                <textarea name="address" id="address" rows="5" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>

                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('app.phone') }}</label>
                                <input type="text" name="phone" id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">

                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="postal_code" class="form-label">{{ __('app.postalCode') }}</label>
                                <input type="text" name="postal_code" id="postal_code"
                                       class="form-control @error('postal_code') is-invalid @enderror"
                                       value="{{ old('postal_code') }}">

                                @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- سمت چپ: خلاصه سفارش -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ __('app.orderSummary') }}</h5>
                        <p class="mb-2">
                            {{ __('app.total') }}:
                            <strong id="cart-total">{{ number_format($cart->items->sum('total')) }} {{ __('app.toman') }}</strong>
                        </p>
                        <p class="mb-3">
                            {{ __('app.quantity') }}:
                            <strong id="cart-count">{{ $cart->items->sum('quantity') }}</strong>
                        </p>

                        <button type="submit" form="address-form" class="btn btn-success w-100">
                            {{ __('app.nextToPay') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
