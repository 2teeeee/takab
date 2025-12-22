<x-main-layout>
    @section('title', 'پرداخت موفق')

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center border-success shadow">
                    <div class="card-header bg-success text-white">
                        🎉 {{ __('app.successfulPayment') }}!
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ __('message.Your order was successfully paid.') }}</h5>
                        <p class="card-text">{{ __('app.yourTransactionNumber') }}:</p>
                        <h4 class="text-success mb-3">{{ $ref_id }}</h4>

                        <a href="{{ route('profile.orders.index') }}" class="btn btn-success mt-3">
                            {{ __('app.viewOrders') }}
                        </a>
                        <a href="{{ route('main.index') }}" class="btn btn-outline-success mt-3">
                            {{ __('app.returnToHomePage') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
