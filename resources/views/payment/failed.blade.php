<x-main-layout>
    @section('title', 'پرداخت ناموفق')

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center border-danger shadow">
                    <div class="card-header bg-danger text-white">
                        ❌ {{ __('app.unsuccessfulPayment') }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ __('message.Your order was not successfully paid.') }}</h5>
                        <p class="card-text">{{ __('app.reason') }}: {{ $error ?? __('app.unspecifiedError') }}</p>

                        <p class="card-text">{{ __('app.yourOrderID') }}: {{ $order->id }}</p>

                        <a href="{{ route('cart.show') }}" class="btn btn-danger mt-3">
                            {{ __('app.returnToShoppingCart') }}
                        </a>
                        <a href="{{ route('main.index') }}" class="btn btn-outline-danger mt-3">
                            {{ __('app.returnToHomePage') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
