<x-main-layout>
    @section('title', 'پرداخت موفق')

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center border-success shadow">
                    <div class="card-header bg-success text-white">
                        🎉 پرداخت موفق!
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-3">سفارش شما با موفقیت پرداخت شد.</h5>
                        <p class="card-text">شماره تراکنش شما:</p>
                        <h4 class="text-success mb-3">{{ $ref_id }}</h4>

                        <p class="card-text">شناسه سفارش شما: {{ $order->id }}</p>

                        <a href="{{ route('profile.orders') }}" class="btn btn-success mt-3">
                            مشاهده سفارش‌ها
                        </a>
                        <a href="{{ route('main.index') }}" class="btn btn-outline-success mt-3">
                            بازگشت به صفحه اصلی
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
