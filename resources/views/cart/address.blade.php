<x-main-layout>
    <div class="container py-4">
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 66%">
                    <span class="fw-bold text-small">مرحله ۲ از ۳: ثبت آدرس</span>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2 small text-muted">
                <span>سبد خرید</span>
                <span>آدرس</span>
                <span>پرداخت</span>
            </div>
        </div>

        <h4 class="mb-4">ثبت آدرس</h4>

        <div class="row">
            <!-- سمت راست: فرم آدرس -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="address-form" action="{{ route('cart.pay') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="address" class="form-label">آدرس کامل</label>
                                <textarea name="address" id="address" rows="5" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">شماره تماس</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="postal_code" class="form-label">کد پستی</label>
                                <input type="text" name="postal_code" id="postal_code" class="form-control" required>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- سمت چپ: خلاصه سفارش -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">خلاصه سفارش</h5>
                        <p class="mb-2">
                            جمع کل:
                            <strong id="cart-total">{{ number_format($cart->items->sum('total')) }} تومان</strong>
                        </p>
                        <p class="mb-3">
                            تعداد کالاها:
                            <strong id="cart-count">{{ $cart->items->sum('quantity') }}</strong>
                        </p>

                        <button type="submit" form="address-form" class="btn btn-success w-100">
                            پرداخت و انتقال به درگاه
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
