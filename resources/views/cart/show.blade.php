<x-main-layout>
    @section('title', 'سبد خرید')
    <div class="container py-4">
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 33%">
                    <span class="fw-bold text-small">مرحله ۱ از ۳: سبد خرید</span>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2 small text-muted">
                <span>سبد خرید</span>
                <span>آدرس</span>
                <span>پرداخت</span>
            </div>
        </div>

        <h3 class="mb-4">سبد خرید شما</h3>

        @if($cart->items->count() > 0)
            <div class="row">
                <!-- سمت راست: لیست محصولات -->
                <div class="col-lg-8 mb-4">
                    <form method="POST" action="{{ route('cart.clear') }}" class="mb-3">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">خالی کردن سبد</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>محصول</th>
                                <th>قیمت</th>
                                <th>تعداد</th>
                                <th>جمع</th>
                                <th>حذف</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cart->items as $item)
                                <tr id="row-{{$item->product->id}}">
                                    <td>
                                        <strong>{{ $item->product->title }}</strong>

                                        {{-- اگر محصول اسمبل‌شده است --}}
                                        @if(!empty($item->meta['parts']))
                                            <div class="mt-2 p-2 bg-light rounded small text-muted">
                                                <div class="fw-bold text-success mb-1">
                                                    <i class="bi bi-gear-wide-connected me-1"></i> قطعات دستگاه:
                                                </div>
                                                <ul class="list-unstyled mb-0 ps-2">
                                                    @foreach($item->meta['parts'] as $part)
                                                        <li class="border-bottom pb-1 mb-1">
                                                            <span class="d-block">{{ $part['name'] }}</span>
                                                            <span class="text-secondary">{{ number_format($part['price']) }} تومان</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->price) }} تومان</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-sm btn-outline-secondary decrease-btn"
                                                    data-id="{{ $item->product->id }}">-</button>
                                            <span class="mx-2 quantity-{{ $item->product->id }}">
                                                {{ $item->quantity }}
                                            </span>
                                            <button class="btn btn-sm btn-outline-secondary increase-btn"
                                                    data-id="{{ $item->product->id }}">+</button>
                                        </div>
                                    </td>
                                    <td class="item-total-{{ $item->product->id }}">
                                        {{ number_format($item->total) }} تومان
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger remove-btn"
                                                data-id="{{ $item->product->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- سمت چپ: اطلاعات سبد -->
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
                            <a href="{{route('cart.address')}}" class="btn btn-success w-100">
                                ادامه به تسویه حساب
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                سبد خرید شما خالی است.
            </div>
        @endif
    </div>
</x-main-layout>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateUI(productId, data) {
            const qtyEl = document.querySelector(".quantity-" + productId);
            const totalEl = document.querySelector(".item-total-" + productId);

            if (qtyEl && data.quantity > 0) {
                qtyEl.textContent = data.quantity;
                totalEl.textContent = data.item_total + " تومان";
            } else {
                const row = document.querySelector("#row-" + productId);
                if (row) row.remove();
            }

            document.querySelector("#cart-total").textContent = data.cart_total + " تومان";
            document.querySelector("#cart-count").textContent = data.cart_count;
        }

        // افزایش
        document.querySelectorAll(".increase-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                let id = this.dataset.id;
                fetch(`/cart/increase/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => updateUI(id, data));
            });
        });

        // کاهش
        document.querySelectorAll(".decrease-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                let id = this.dataset.id;
                fetch(`/cart/decrease/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => updateUI(id, data));
            });
        });

        // حذف
        document.querySelectorAll(".remove-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                let id = this.dataset.id;
                fetch(`/cart/remove/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        const row = document.querySelector("#row-" + id);
                        if (row) row.remove();

                        document.querySelector("#cart-total").textContent = data.cart_total + " تومان";
                        document.querySelector("#cart-count").textContent = data.cart_count;
                    });
            });
        });
    });
</script>
