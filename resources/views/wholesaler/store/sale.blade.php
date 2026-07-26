<x-admin-layout title="ثبت سفارش فروشگاه" header="ثبت سفارش فروشگاه">

    <div class="container py-4">

        <a href="{{ route('wholesaler.stores.index') }}" class="btn btn-sm btn-secondary mb-3">
            <i class="bi bi-chevron-double-right"></i> بازگشت به لیست فروشگاه ها
        </a>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('wholesaler.stores.sale.store',$store) }}"
              method="POST">
            @csrf

            @error('products')
            <div class="alert alert-danger mt-3">
                {{ $message }}
            </div>
            @enderror

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong>مشتری</strong><br>
                            {{ $store->name }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>موبایل</strong><br>
                            {{ $store->mobile }}
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">
                                آدرس ارسال
                                <span class="text-danger">*</span>
                            </label>
                            <textarea
                                    name="address"
                                    rows="3"
                                    class="form-control @error('address') is-invalid @enderror"
                            >{{ old('address', $store->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th width="70">تصویر</th>
                            <th>محصول</th>
                            <th width="120">قیمت</th>
                            <th width="90">موجودی</th>
                            <th width="120">تعداد</th>
                            <th width="150">جمع</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <img
                                            src="{{ asset('storage/'.$product->small_image_name) }}"
                                            class="img-thumbnail"
                                            style="width:60px">
                                </td>
                                <td>
                                    {{ $product->title }}
                                </td>
                                <td>
                                    {{ number_format($product->sell_price) }}
                                </td>
                                <td>
                                    {{ $product->stock }}
                                </td>
                                <td>
                                    <input
                                            type="number"
                                            min="0"
                                            max="{{ $product->stock }}"
                                            value="{{ old('products.'.$product->id, 0) }}"
                                            class="form-control quantity"
                                            data-price="{{ $product->sell_price }}"
                                            name="products[{{ $product->id }}]">
                                </td>

                                <td>
                                    <span class="line-total">
                                        0
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">
                                جمع کل
                            </th>
                            <th>
                                <span id="total">
                                    0
                                </span>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-end">
                                تخفیف
                            </th>
                            <th>
                                <span id="discount">
                                    0
                                </span>
                            </th>
                        </tr>
                        <tr class="table-success">
                            <th colspan="5" class="text-end">
                                مبلغ نهایی
                            </th>
                            <th>
                                <span id="final">
                                    0
                                </span>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="mt-4 text-end">
                <button class="btn btn-success btn-lg">
                    ثبت سفارش
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>


<script>
    document.addEventListener("DOMContentLoaded",function(){
        function calculate(){
            let total=0;
            let discount=0;
            document.querySelectorAll(".quantity").forEach(function(input){
                let qty=parseInt(input.value)||0;
                let price=parseInt(input.dataset.price);
                let rowTotal=qty*price;
                input.closest("tr")
                    .querySelector(".line-total")
                    .innerHTML=rowTotal.toLocaleString();
                total+=rowTotal;
                discount+=qty*0;
            });

            document.getElementById("total").innerHTML=
                total.toLocaleString();

            document.getElementById("discount").innerHTML=
                discount.toLocaleString();

            document.getElementById("final").innerHTML=
                Math.max(total-discount,0).toLocaleString();

        }

        document.querySelectorAll(".quantity").forEach(function(input){
            input.addEventListener("input",calculate);
        });

        calculate();
    });
</script>