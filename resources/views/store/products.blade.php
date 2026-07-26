<x-admin-layout title="خرید از شرکت" header="خرید محصولات">

    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('store.products.store') }}">
            @csrf

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th width="80">تصویر</th>
                    <th>نام محصول</th>
                    <th width="170">قیمت</th>
                    <th width="120">موجودی</th>
                    <th width="150">تعداد خرید</th>
                    <th width="170">جمع</th>
                </tr>
                </thead>

                <tbody>

                @foreach($products as $product)

                    <tr>

                        <td class="text-center">
                            <img src="{{ asset('storage/'.$product->small_image_name) }}"
                                 width="60">
                        </td>

                        <td>
                            {{ $product->title }}
                        </td>

                        <td class="price"
                            data-price="{{ $product->sell_price }}">
                            {{ number_format($product->sell_price) }}
                        </td>

                        <td>
                            {{ number_format($product->stock) }}
                        </td>

                        <td>

                            <input
                                    type="number"
                                    min="0"
                                    max="{{ $product->stock }}"
                                    value="0"
                                    class="form-control quantity"
                                    name="products[{{ $product->id }}]"
                            >

                        </td>

                        <td class="row-total">
                            0
                        </td>

                    </tr>

                @endforeach

                </tbody>

                <tfoot>

                <tr class="table-light">

                    <th colspan="5" class="text-end">
                        جمع کل
                    </th>

                    <th id="totalPrice">
                        0
                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end text-danger">
                        پورسانت
                    </th>

                    <th class="text-danger" id="discount">
                        0
                    </th>

                </tr>

                <tr class="table-success">

                    <th colspan="5" class="text-end">
                        مبلغ نهایی
                    </th>

                    <th id="finalPrice">
                        0
                    </th>

                </tr>

                </tfoot>

            </table>

            <button class="btn btn-success btn-lg">
                ثبت سفارش
            </button>

        </form>

        <div class="mt-3">
            {{ $products->links() }}
        </div>

    </div>

</x-admin-layout>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const discountPerProduct = 0;

        function calculate() {

            let total = 0;
            let discount = 0;

            document.querySelectorAll("tbody tr").forEach(function(row){

                const price =
                    parseInt(
                        row.querySelector(".price")
                            .dataset.price
                    );

                const qty =
                    parseInt(
                        row.querySelector(".quantity")
                            .value
                    ) || 0;

                const rowTotal = price * qty;

                row.querySelector(".row-total")
                    .innerHTML =
                    rowTotal.toLocaleString();

                total += rowTotal;

                discount += qty * discountPerProduct;

            });

            document.getElementById("totalPrice").innerHTML =
                total.toLocaleString();

            document.getElementById("discount").innerHTML =
                discount.toLocaleString();

            document.getElementById("finalPrice").innerHTML =
                Math.max(0,total-discount).toLocaleString();

        }

        document.querySelectorAll(".quantity")
            .forEach(function(item){

                item.addEventListener("input",calculate);

            });

        calculate();

    });

</script>