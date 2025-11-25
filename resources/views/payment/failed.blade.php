<x-main-layout>
    @section('title', 'پرداخت ناموفق')

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center border-danger shadow">
                    <div class="card-header bg-danger text-white">
                        ❌ پرداخت ناموفق
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-3">متاسفانه پرداخت شما انجام نشد.</h5>
                        <p class="card-text">دلیل: {{ $error ?? 'خطای نامشخص' }}</p>

                        <p class="card-text">شناسه سفارش شما: {{ $order->id }}</p>

                        <a href="{{ route('cart.show') }}" class="btn btn-danger mt-3">
                            بازگشت به سبد خرید
                        </a>
                        <a href="{{ route('main.index') }}" class="btn btn-outline-danger mt-3">
                            بازگشت به صفحه اصلی
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
