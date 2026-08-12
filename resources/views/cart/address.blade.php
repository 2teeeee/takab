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

                                <label for="referral_code" class="form-label">
                                    {{ __('app.referralCode') }}
                                </label>

                                <div class="input-group">

                                    <input
                                            type="text"
                                            name="referral_code"
                                            id="referral_code"
                                            class="form-control @error('referral_code') is-invalid @enderror"
                                            value="{{ old('referral_code') }}"
                                            placeholder="{{ __('app.referralCodePlaceholder') }}"
                                    >

                                    <button
                                            type="button"
                                            id="check-referral"
                                            class="btn btn-outline-primary">
                                        {{ __('app.checkReferralCode') }}
                                    </button>

                                </div>

                                <div id="referral-message" class="mt-2"></div>

                                <div class="form-text">
                                    {{ __('app.referralCodeHelp') }}
                                </div>

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

                            <strong>
                                {{ number_format($cart->items->sum('total')) }}
                                {{ __('app.toman') }}
                            </strong>
                        </p>

                        <p class="mb-2 text-danger">
                            {{ __('app.discount') }}:

                            <strong id="referral-discount">
                                0
                            </strong>

                            {{ __('app.toman') }}
                        </p>

                        <p class="mb-3">
                            {{ __('app.finalTotal') }}:

                            <strong id="final-total">
                                {{ number_format($cart->items->sum('total')) }}
                            </strong>

                            {{ __('app.toman') }}
                        </p>

                        <p class="mb-3">
                            {{ __('app.quantity') }}:
                            <strong id="cart-count">
                                {{ $cart->items->sum('quantity') }}
                            </strong>
                        </p>

                        <button type="submit" form="address-form" class="btn btn-success w-100">
                            {{ __('app.nextToPay') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const referralInput = document.getElementById('referral_code');
            const checkButton = document.getElementById('check-referral');
            const message = document.getElementById('referral-message');
            const discountElement = document.getElementById('referral-discount');
            const finalElement = document.getElementById('final-total');

            const total = {{ $cart->items->sum('total') }};

            checkButton.addEventListener('click', async function () {

                const code = referralInput.value.trim();

                if (!code) {
                    message.innerHTML = `
                    <div class="alert alert-danger py-2 mb-0">
                        {{ __('orders.referral_code_required') }}
                    </div>
                `;

                    return;
                }

                checkButton.disabled = true;
                checkButton.innerText = '{{ __('app.checking') }}';

                try {

                    const response = await fetch(
                        '{{ route('cart.checkReferral') }}',
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },

                            body: JSON.stringify({
                                referral_code: code
                            })
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {

                        discountElement.innerText = '0';

                        finalElement.innerText =
                            new Intl.NumberFormat('fa-IR')
                                .format(total);

                        message.innerHTML = `
                        <div class="alert alert-danger py-2 mb-0">
                            ${data.message}
                        </div>
                    `;

                        return;
                    }

                    const discount = Math.min(
                        data.discount,
                        total
                    );

                    const finalTotal = Math.max(
                        total - discount,
                        0
                    );

                    discountElement.innerText =
                        new Intl.NumberFormat('fa-IR')
                            .format(discount);

                    finalElement.innerText =
                        new Intl.NumberFormat('fa-IR')
                            .format(finalTotal);

                    message.innerHTML = `
                    <div class="alert alert-success py-2 mb-0">
                        ${data.message}
                    </div>
                `;

                } catch (error) {

                    message.innerHTML = `
                    <div class="alert alert-danger py-2 mb-0">
                        {{ __('app.connectionError') }}
                    </div>
                `;

                } finally {

                    checkButton.disabled = false;
                    checkButton.innerText =
                        '{{ __('app.checkReferralCode') }}';
                }
            });

        });
    </script>

</x-main-layout>
