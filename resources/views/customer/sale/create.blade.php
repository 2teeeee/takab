<x-profile-layout
        title="ثبت فروش به مشتری"
>

    <div class="container py-4">

        {{-- Messages --}}
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


        <form
                method="POST"
                action="{{ route('profile.customer.sale.store') }}"
        >

            @csrf


            {{-- Customer Information --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white border-bottom py-3">

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-person fs-5 text-secondary"></i>

                        <div>
                            <h6 class="mb-0">
                                اطلاعات مشتری
                            </h6>

                            <small class="text-muted">
                                اطلاعات مشتری و آدرس ارسال
                            </small>
                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row">

                        {{-- Mobile --}}
                        <div class="col-12 col-md-4 mb-3">

                            <label
                                    for="mobile"
                                    class="form-label"
                            >
                                شماره موبایل
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                    type="tel"
                                    name="mobile"
                                    id="mobile"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile') }}"
                                    maxlength="11"
                                    inputmode="numeric"
                                    autocomplete="tel"
                                    placeholder="09123456789"
                                    required
                            >

                            @error('mobile')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Name --}}
                        <div class="col-12 col-md-4 mb-3">

                            <label
                                    for="name"
                                    class="form-label"
                            >
                                نام مشتری
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    placeholder="نام و نام خانوادگی"
                                    required
                            >

                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- National Code --}}
                        <div class="col-12 col-md-4 mb-3">

                            <label
                                    for="national_code"
                                    class="form-label"
                            >
                                کد ملی
                            </label>

                            <input
                                    type="text"
                                    name="national_code"
                                    id="national_code"
                                    class="form-control @error('national_code') is-invalid @enderror"
                                    value="{{ old('national_code') }}"
                                    maxlength="10"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    placeholder="کد ملی ۱۰ رقمی"
                            >

                            @error('national_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Address --}}
                        <div class="col-12">

                            <label
                                    for="address"
                                    class="form-label"
                            >
                                آدرس ارسال
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                    name="address"
                                    id="address"
                                    rows="3"
                                    class="form-control @error('address') is-invalid @enderror"
                                    autocomplete="street-address"
                                    placeholder="آدرس کامل مشتری را وارد کنید"
                                    required
                            >{{ old('address') }}</textarea>

                            @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- Products Error --}}
            @error('products')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
            @enderror


            {{-- Products --}}
            <div class="card shadow-sm">

                <div class="card-header bg-white border-bottom py-3">

                    <div class="d-flex align-items-center justify-content-between">

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-box-seam fs-5 text-secondary"></i>

                            <div>

                                <h6 class="mb-0">
                                    انتخاب محصولات
                                </h6>

                                <small class="text-muted">
                                    محصولات مورد فروش را انتخاب کنید
                                </small>

                            </div>

                        </div>


                        @if($products->count())

                            <span class="badge text-bg-light">
                                {{ $products->count() }} محصول
                            </span>

                        @endif

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th style="width: 70px;">
                                تصویر
                            </th>

                            <th>
                                محصول
                            </th>

                            <th style="width: 130px;">
                                قیمت
                            </th>

                            <th style="width: 120px;">
                                تعداد
                            </th>

                            <th style="width: 150px;">
                                جمع
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($products as $product)

                            <tr>

                                {{-- Image --}}
                                <td>

                                    <img
                                            src="{{ asset('storage/'.$product->small_image_name) }}"
                                            class="img-thumbnail"
                                            style="width:60px; height:60px; object-fit:cover;"
                                            alt="{{ $product->title }}"
                                    >

                                </td>


                                {{-- Product --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $product->title }}
                                    </div>

                                </td>


                                {{-- Price --}}
                                <td>

                                    <span class="text-nowrap">
                                        {{ number_format($product->sell_price) }}
                                    </span>

                                    <small class="text-muted">
                                        تومان
                                    </small>

                                </td>


                                {{-- Quantity --}}
                                <td>

                                    <input
                                            type="number"
                                            min="0"
                                            max="{{ $product->stock }}"
                                            step="1"
                                            inputmode="numeric"
                                            value="{{ old('products.'.$product->id, 0) }}"
                                            class="form-control quantity text-center @error('products.'.$product->id) is-invalid @enderror"
                                            data-price="{{ $product->sell_price }}"
                                            name="products[{{ $product->id }}]"
                                    >

                                    @error('products.'.$product->id)

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </td>


                                {{-- Line Total --}}
                                <td>

                                    <span class="line-total">
                                        0
                                    </span>

                                    <small class="text-muted">
                                        تومان
                                    </small>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                        colspan="5"
                                        class="text-center text-muted py-5"
                                >

                                    <div class="mb-2">

                                        <i class="bi bi-box-seam fs-1"></i>

                                    </div>

                                    <div class="fw-semibold mb-1">
                                        محصولی برای فروش موجود نیست.
                                    </div>

                                    <small>
                                        در حال حاضر محصولی با موجودی قابل فروش وجود ندارد.
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>


                        @if($products->count())

                            <tfoot>

                            {{-- Total --}}
                            <tr>

                                <th
                                        colspan="4"
                                        class="text-end"
                                >
                                    جمع کل
                                </th>

                                <th>

                                    <span id="total">
                                        0
                                    </span>

                                    <small class="text-muted">
                                        تومان
                                    </small>

                                </th>

                            </tr>


                            {{-- Available Discount --}}
                            <tr>

                                <th
                                        colspan="4"
                                        class="text-end"
                                >
                                    تخفیف
                                </th>

                                <th>

                                    <span id="discount_available">
                                        0
                                    </span>

                                    <small class="text-muted">
                                        تومان
                                    </small>

                                </th>

                            </tr>


                            {{-- Final --}}
                            <tr class="table-success">

                                <th
                                        colspan="4"
                                        class="text-end"
                                >
                                    مبلغ نهایی پرداخت
                                </th>

                                <th>

                                    <span id="final">
                                        0
                                    </span>

                                    <small>
                                        تومان
                                    </small>

                                </th>

                            </tr>

                            </tfoot>

                        @endif

                    </table>

                </div>


                {{-- Footer --}}
                @if($products->count())

                    <div class="card-footer bg-white border-top p-3">

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                            <div class="text-muted small">

                                <i class="bi bi-info-circle me-1"></i>

                                تعداد هر محصول را وارد کنید. مقدار صفر یعنی محصول انتخاب نشده است.

                            </div>


                            <button
                                    type="submit"
                                    class="btn btn-sm btn-success px-4"
                            >

                                <i class="bi bi-check2-circle me-1"></i>

                                ثبت سفارش

                            </button>

                        </div>

                    </div>

                @endif

            </div>

        </form>

    </div>


    @push('scripts')

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                const discountInput = document.getElementById('discount');

                function calculate() {

                    let total = 0;
                    let discountAvailable = 0;

                    document.querySelectorAll('.quantity').forEach(function (input) {

                        let quantity =
                            parseInt(input.value) || 0;

                        const price =
                            parseInt(input.dataset.price) || 0;


                        // Prevent negative quantity
                        if (quantity < 0) {
                            quantity = 0;
                            input.value = 0;
                        }


                        // Prevent quantity higher than stock
                        const max =
                            parseInt(input.max) || 0;

                        if (max > 0 && quantity > max) {
                            quantity = max;
                            input.value = max;
                        }


                        const rowTotal =
                            quantity * price;


                        const row =
                            input.closest('tr');


                        const lineTotal =
                            row.querySelector('.line-total');


                        if (lineTotal) {

                            lineTotal.textContent =
                                rowTotal.toLocaleString('fa-IR');

                        }


                        total += rowTotal;


                        // 1,000,000 Toman discount per product
                        discountAvailable +=
                            quantity * 1000000;

                    });


                    const finalAmount =
                        Math.max(total - discountAvailable, 0);


                    document.getElementById('total').textContent =
                        total.toLocaleString('fa-IR');


                    document.getElementById('discount_available').textContent =
                        discountAvailable.toLocaleString('fa-IR');


                    document.getElementById('final').textContent =
                        finalAmount.toLocaleString('fa-IR');

                }


                document.querySelectorAll('.quantity').forEach(function (input) {

                    input.addEventListener('input', calculate);

                });


                discountInput?.addEventListener('input', function () {

                    let value =
                        parseInt(this.value) || 0;


                    if (value < 0) {
                        this.value = 0;
                    }


                    calculate();

                });


                calculate();

            });

        </script>

    @endpush

</x-profile-layout>