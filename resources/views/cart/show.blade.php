<x-main-layout>
    @section('title', 'سبد خرید')

    <div class="container my-4">
        <h3 class="mb-4">سبد خرید شما</h3>

        @if($cart->items->count() > 0)
            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger mb-3">خالی کردن سبد</button>
            </form>

            <table class="table table-bordered">
                <thead>
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
                        <td>{{ $item->product->title }}</td>
                        <td>{{ number_format($item->price) }} تومان</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary decrease-btn" data-id="{{ $item->product->id }}">-</button>
                                <span class="mx-2 quantity-{{ $item->product->id }}">{{ $item->quantity }}</span>
                                <button class="btn btn-sm btn-outline-secondary increase-btn" data-id="{{ $item->product->id }}">+</button>
                            </div>
                        </td>
                        <td class="item-total-{{ $item->product->id }}">
                            {{ number_format($item->total) }} تومان
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-btn" data-id="{{ $item->product->id }}">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <h5>
                    جمع کل:
                    <span id="cart-total">{{ number_format($cart->items->sum('total')) }} تومان</span>
                </h5>
                <a href="" class="btn btn-success mt-3">
                    ادامه به تسویه حساب
                </a>
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
            document.querySelector("#cart-count").textContent = data.cart_count; // badge
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
