<x-admin-layout
        :title="__('store.customer_sale.title')"
        :header="__('store.customer_sale.header')"
>

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

            <div>
                <h4 class="fw-bold mb-1">
                    {{ __('store.customer_sale.title') }}
                </h4>

                <p class="text-muted mb-0">
                    {{ __('store.customer_sale.subtitle') }}
                </p>
            </div>

            <a href="{{ url()->previous() }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-right me-1"></i>
                {{ __('common.back') }}
            </a>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <div class="d-flex align-items-start gap-2">

                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>

                    <div>
                        <strong>
                            {{ __('common.validation_error') }}
                        </strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        @endif


        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
            </div>
        @endif


        <form
                method="POST"
                action="{{ route('store.customers.direct-sale.store') }}"
                id="customerSaleForm"
        >
            @csrf

            <div class="row g-4">

                {{-- ========================================= --}}
                {{-- Customer Information --}}
                {{-- ========================================= --}}

                <div class="col-12 col-lg-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white border-0 pt-4 px-4">

                            <div class="d-flex align-items-center gap-2">

                                <div class="rounded-circle bg-primary-subtle
                                            text-primary d-flex align-items-center
                                            justify-content-center"
                                     style="width:42px;height:42px;">
                                    <i class="bi bi-person fs-5"></i>
                                </div>

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ __('store.customer_sale.customer_information') }}
                                    </h5>

                                    <small class="text-muted">
                                        {{ __('store.customer_sale.customer_information_hint') }}
                                    </small>
                                </div>

                            </div>

                        </div>


                        <div class="card-body px-4 pb-4">

                            {{-- Mobile --}}
                            <div class="mb-3">

                                <label for="mobile" class="form-label fw-semibold">
                                    {{ __('users.mobile') }}
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-phone"></i>
                                    </span>

                                    <input
                                            type="tel"
                                            name="mobile"
                                            id="mobile"
                                            class="form-control @error('mobile') is-invalid @enderror"
                                            value="{{ old('mobile') }}"
                                            placeholder="{{ __('store.customer_sale.mobile_placeholder') }}"
                                            inputmode="numeric"
                                            autocomplete="tel"
                                            maxlength="11"
                                            required
                                    >

                                    <span
                                            id="mobileLoading"
                                            class="input-group-text d-none bg-white"
                                    >
                                        <span
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                        ></span>
                                    </span>

                                </div>

                                <div class="form-text">
                                    {{ __('store.customer_sale.mobile_help') }}
                                </div>

                                @error('mobile')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Customer Existing Status --}}
                            <div
                                    id="customerStatus"
                                    class="d-none mb-3"
                            ></div>


                            {{-- Name --}}
                            <div class="mb-3">

                                <label for="name" class="form-label fw-semibold">
                                    {{ __('users.name') }}
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="{{ __('store.customer_sale.name_placeholder') }}"
                                        autocomplete="name"
                                        required
                                >

                                <div
                                        id="existingCustomerHint"
                                        class="form-text d-none"
                                ></div>

                                @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- National Code --}}
                            <div class="mb-3">

                                <label for="national_code" class="form-label fw-semibold">
                                    {{ __('users.national_code') }}
                                </label>

                                <input
                                        type="text"
                                        name="national_code"
                                        id="national_code"
                                        class="form-control @error('national_code') is-invalid @enderror"
                                        value="{{ old('national_code') }}"
                                        placeholder="{{ __('store.customer_sale.national_code_placeholder') }}"
                                        inputmode="numeric"
                                        maxlength="10"
                                >

                                @error('national_code')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Address --}}
                            <div class="mb-3">

                                <label for="address" class="form-label fw-semibold">
                                    {{ __('users.address') }}

                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                        name="address"
                                        id="address"
                                        rows="4"
                                        class="form-control @error('address') is-invalid @enderror"
                                        placeholder="{{ __('store.customer_sale.address_placeholder') }}"
                                        required
                                >{{ old('address') }}</textarea>

                                <div class="form-text">
                                    {{ __('store.customer_sale.address_help') }}
                                </div>

                                @error('address')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================================= --}}
                {{-- Products --}}
                {{-- ========================================= --}}

                <div class="col-12 col-lg-8">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white border-0 pt-4 px-4">

                            <div class="d-flex flex-wrap justify-content-between
                                        align-items-center gap-3">

                                <div class="d-flex align-items-center gap-2">

                                    <div class="rounded-circle bg-success-subtle
                                                text-success d-flex align-items-center
                                                justify-content-center"
                                         style="width:42px;height:42px;">
                                        <i class="bi bi-box-seam fs-5"></i>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            {{ __('store.customer_sale.products') }}
                                        </h5>

                                        <small class="text-muted">
                                            {{ __('store.customer_sale.products_hint') }}
                                        </small>
                                    </div>

                                </div>


                                {{-- Product Count --}}
                                <span
                                        id="selectedProductsCount"
                                        class="badge bg-light text-dark px-3 py-2"
                                >
                                    0 {{ __('store.customer_sale.selected') }}
                                </span>

                            </div>

                        </div>


                        <div class="card-body px-4">

                            {{-- Search Products --}}
                            <div class="mb-4">

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>

                                    <input
                                            type="search"
                                            id="productSearch"
                                            class="form-control"
                                            placeholder="{{ __('store.customer_sale.search_product') }}"
                                            autocomplete="off"
                                    >

                                </div>

                            </div>


                            @if ($products->count())

                                <div
                                        id="productsContainer"
                                        class="row g-3"
                                >

                                    @foreach ($products as $product)

                                        @php
                                            $quantity = old("products.{$product->id}", 0);
                                        @endphp

                                        <div
                                                class="col-12 col-md-6 product-item"
                                                data-product-name="{{ strtolower($product->title ?? $product->name ?? '') }}"
                                                data-price="{{ $product->sell_price ?? 0 }}"
                                        >

                                            <div
                                                    class="product-card border rounded-3 p-3 h-100"
                                            >

                                                <div class="d-flex gap-3">

                                                    {{-- Product Image --}}
                                                    <div
                                                            class="product-image flex-shrink-0"
                                                            style="width:80px;height:80px;"
                                                    >

                                                        @if (!empty($product->image))
                                                            <img
                                                                    src="{{ $product->image }}"
                                                                    alt="{{ $product->title ?? $product->name }}"
                                                                    class="w-100 h-100 rounded-3 object-fit-cover"
                                                            >
                                                        @else
                                                            <div
                                                                    class="w-100 h-100 rounded-3
                                                                       bg-light d-flex
                                                                       align-items-center
                                                                       justify-content-center"
                                                            >
                                                                <i class="bi bi-box-seam
                                                                          text-muted fs-3"></i>
                                                            </div>
                                                        @endif

                                                    </div>


                                                    {{-- Product Information --}}
                                                    <div class="flex-grow-1">

                                                        <div class="d-flex
                                                                    justify-content-between
                                                                    gap-2">

                                                            <div>

                                                                <h6 class="fw-bold mb-1">
                                                                    {{ $product->title ?? $product->name }}
                                                                </h6>

                                                                @if(isset($product->stock))
                                                                    <small class="text-muted">
                                                                        {{ __('store.customer_sale.available_stock') }}:
                                                                        <strong>
                                                                            {{ $product->stock }}
                                                                        </strong>
                                                                    </small>
                                                                @endif

                                                            </div>

                                                        </div>


                                                        @if(isset($product->sell_price))

                                                            <div class="mt-2">

                                                                <span class="fw-bold text-primary">
                                                                    {{ number_format($product->sell_price) }}
                                                                </span>

                                                                <small class="text-muted">
                                                                    {{ __('common.toman') }}
                                                                </small>

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>


                                                {{-- Quantity --}}
                                                <div class="d-flex align-items-center
                                                            justify-content-between
                                                            gap-3 mt-3 pt-3 border-top">

                                                    <span class="small text-muted">
                                                        {{ __('store.customer_sale.quantity') }}
                                                    </span>

                                                    <div
                                                            class="quantity-control d-flex
                                                               align-items-center"
                                                    >

                                                        <button
                                                                type="button"
                                                                class="btn btn-outline-secondary
                                                                   quantity-minus"
                                                                aria-label="{{ __('store.customer_sale.decrease_quantity') }}"
                                                        >
                                                            <i class="bi bi-dash"></i>
                                                        </button>

                                                        <input
                                                                type="number"
                                                                name="products[{{ $product->id }}]"
                                                                value="{{ $quantity }}"
                                                                min="0"
                                                                @if(isset($product->stock))
                                                                    max="{{ $product->stock }}"
                                                                @endif
                                                                class="form-control text-center
                                                                   quantity-input mx-1 quantity"
                                                                style="width:65px;"
                                                        >

                                                        <button
                                                                type="button"
                                                                class="btn btn-outline-secondary
                                                                   quantity-plus"
                                                                aria-label="{{ __('store.customer_sale.increase_quantity') }}"
                                                        >
                                                            <i class="bi bi-plus"></i>
                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                {{-- Empty Products --}}
                                <div class="text-center py-5">

                                    <div
                                            class="rounded-circle bg-light mx-auto mb-3
                                               d-flex align-items-center
                                               justify-content-center"
                                            style="width:70px;height:70px;"
                                    >
                                        <i class="bi bi-box-seam
                                                  text-muted fs-2"></i>
                                    </div>

                                    <h6 class="fw-bold">
                                        {{ __('store.customer_sale.no_products') }}
                                    </h6>

                                    <p class="text-muted mb-0">
                                        {{ __('store.customer_sale.no_products_hint') }}
                                    </p>

                                </div>

                            @endif

                            <div
                                    id="noSearchResult"
                                    class="d-none text-center py-5"
                            >
                                <i class="bi bi-search text-muted fs-1"></i>

                                <p class="text-muted mt-3 mb-0">
                                    {{ __('store.customer_sale.no_search_result') }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- ========================================= --}}
                    {{-- Order Summary --}}
                    {{-- ========================================= --}}

                    <div class="card border-0 shadow-sm mt-4">

                        <div class="card-header bg-white border-0 pt-4 px-4">

                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-receipt me-1"></i>
                                {{ __('store.customer_sale.order_summary') }}
                            </h5>

                        </div>


                        <div class="card-body px-4">

                            <div class="row g-3">

                                <div class="col-6 col-md-4">

                                    <div class="bg-light rounded-3 p-3">

                                        <small class="text-muted d-block">
                                            {{ __('store.customer_sale.products_count') }}
                                        </small>

                                        <strong
                                                id="summaryProductsCount"
                                                class="fs-5"
                                        >
                                            0
                                        </strong>

                                    </div>

                                </div>


                                <div class="col-6 col-md-4">

                                    <div class="bg-light rounded-3 p-3">

                                        <small class="text-muted d-block">
                                            {{ __('store.customer_sale.total_quantity') }}
                                        </small>

                                        <strong
                                                id="summaryQuantity"
                                                class="fs-5"
                                        >
                                            0
                                        </strong>

                                    </div>

                                </div>


                                <div class="col-12 col-md-4">

                                    <div class="bg-primary-subtle rounded-3 p-3">

                                        <small class="text-muted d-block">
                                            {{ __('store.customer_sale.total_amount') }}
                                        </small>

                                        <strong
                                                id="summaryTotal"
                                                class="fs-5 text-primary"
                                        >
                                            0
                                            <small>
                                                {{ __('common.toman') }}
                                            </small>
                                        </strong>

                                    </div>

                                    <div class="bg-warning-subtle mt-2 rounded-3 p-3">

                                        <label for="discount" class="small text-muted d-block mb-2">
                                            {{ __('store.customer_sale.discount') }}
                                        </label>

                                        <div class="input-group">

                                            <input
                                                    type="number"
                                                    name="discount"
                                                    id="discount"
                                                    class="form-control @error('discount') is-invalid @enderror"
                                                    value="{{ old('discount', 0) }}"
                                                    min="0"
                                                    step="1000"
                                                    inputmode="numeric"
                                                    placeholder="{{ __('store.customer_sale.discount_placeholder') }}"
                                            >

                                            <span class="input-group-text">
                                                {{ __('common.toman') }}
                                            </span>

                                        </div>

                                        @error('discount')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                        @enderror

                                    </div>

                                    <div class="bg-success-subtle mt-2 rounded-3 p-3">

                                        <small class="text-muted d-block">
                                            {{ __('store.customer_sale.final_amount') }}
                                        </small>

                                        <strong
                                                id="summaryFinalTotal"
                                                class="fs-5 text-success"
                                        >
                                            0
                                            <small>
                                                {{ __('common.toman') }}
                                            </small>
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ========================================= --}}
                    {{-- Submit --}}
                    {{-- ========================================= --}}

                    <div class="d-flex flex-wrap justify-content-end
                                gap-2 mt-4">

                        <a
                                href="{{ url()->previous() }}"
                                class="btn btn-sm btn-light px-4"
                        >
                            {{ __('common.cancel') }}
                        </a>

                        <button
                                type="submit"
                                id="submitButton"
                                class="btn btn-sm btn-primary px-4"
                                disabled
                        >
                            <i class="bi bi-check2-circle me-1"></i>

                            {{ __('store.customer_sale.submit') }}
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- ========================================= --}}
    {{-- Styles --}}
    {{-- ========================================= --}}

    <style>

        .product-card {
            transition: all .2s ease;
            background: #fff;
        }

        .product-card:hover {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .06);
        }

        .product-card.has-quantity {
            border-color: var(--bs-primary) !important;
            background: rgba(13, 110, 253, .025);
        }

        .quantity-control .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-input {
            height: 36px;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        @media (max-width: 575.98px) {

            .product-image {
                width: 65px !important;
                height: 65px !important;
            }

            .quantity-control .btn {
                width: 34px;
            }

            .quantity-input {
                width: 55px !important;
            }

        }

    </style>


    {{-- ========================================= --}}
    {{-- JavaScript --}}
    {{-- ========================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('customerSaleForm');

            const productSearch = document.getElementById('productSearch');

            const productItems = document.querySelectorAll('.product-item');

            const noSearchResult = document.getElementById('noSearchResult');

            const submitButton = document.getElementById('submitButton');

            const selectedProductsCount =
                document.getElementById('selectedProductsCount');

            const summaryProductsCount =
                document.getElementById('summaryProductsCount');

            const summaryQuantity =
                document.getElementById('summaryQuantity');

            const summaryTotal =
                document.getElementById('summaryTotal');

            const discountInput =
                document.getElementById('discount');

            const summaryFinalTotal =
                document.getElementById('summaryFinalTotal');

            function formatNumber(number) {
                return new Intl.NumberFormat('en-US').format(number);
            }

            function updateSummary() {

                let productsCount = 0;
                let totalQuantity = 0;
                let totalAmount = 0;


                document.querySelectorAll('.product-item').forEach(item => {

                    const input =
                        item.querySelector('.quantity-input');

                    if (!input) {
                        return;
                    }

                    let quantity = parseInt(input.value) || 0;

                    if (quantity < 0) {
                        quantity = 0;
                        input.value = 0;
                    }


                    if (quantity > 0) {

                        productsCount++;

                        totalQuantity += quantity;


                        const price =
                            parseInt(
                                item.dataset.price || '0'
                            );

                        totalAmount += price * quantity;

                    }


                    const card =
                        item.querySelector('.product-card');

                    if (quantity > 0) {
                        card.classList.add('has-quantity');
                    } else {
                        card.classList.remove('has-quantity');
                    }

                });


                selectedProductsCount.innerHTML =
                    `${productsCount} {{ __('store.customer_sale.selected') }}`;

                summaryProductsCount.textContent =
                    formatNumber(productsCount);

                summaryQuantity.textContent =
                    formatNumber(totalQuantity);

                let discount =
                    parseInt(discountInput.value) || 0;

                if (discount < 0) {
                    discount = 0;
                }

                if (discount > totalAmount) {
                    discount = totalAmount;
                    discountInput.value = totalAmount;
                }

                const finalAmount =
                    Math.max(totalAmount - discount, 0);

                summaryTotal.innerHTML =
                    `${formatNumber(totalAmount)}
                    <small>{{ __('common.toman') }}</small>`;

                summaryFinalTotal.innerHTML =
                    `${formatNumber(finalAmount)}
                    <small>{{ __('common.toman') }}</small>`;

                submitButton.disabled =
                    productsCount === 0;

            }

            // Quantity +/- buttons
            document.querySelectorAll('.product-item').forEach(item => {

                const input =
                    item.querySelector('.quantity-input');

                const minus =
                    item.querySelector('.quantity-minus');

                const plus =
                    item.querySelector('.quantity-plus');


                if (!input) {
                    return;
                }


                minus.addEventListener('click', function () {

                    let value = parseInt(input.value) || 0;

                    value--;

                    if (value < 0) {
                        value = 0;
                    }

                    input.value = value;

                    updateSummary();

                });


                plus.addEventListener('click', function () {

                    let value = parseInt(input.value) || 0;

                    const max =
                        parseInt(input.getAttribute('max'));

                    value++;


                    if (!isNaN(max) && value > max) {
                        value = max;
                    }


                    input.value = value;

                    updateSummary();

                });


                input.addEventListener('input', updateSummary);

                discountInput?.addEventListener('input', function () {

                    let value = parseInt(this.value) || 0;

                    if (value < 0) {
                        this.value = 0;
                    }

                    updateSummary();

                });

            });

            // Product Search
            productSearch?.addEventListener('input', function () {

                const search =
                    this.value.trim().toLowerCase();

                let visibleCount = 0;


                productItems.forEach(item => {

                    const name =
                        item.dataset.productName || '';

                    const matched =
                        !search || name.includes(search);


                    item.classList.toggle(
                        'd-none',
                        !matched
                    );


                    if (matched) {
                        visibleCount++;
                    }

                });


                noSearchResult.classList.toggle(
                    'd-none',
                    visibleCount !== 0
                );

            });

            // Prevent duplicate submission
            form.addEventListener('submit', function (event) {

                if (submitButton.disabled) {

                    event.preventDefault();

                    return;

                }


                submitButton.disabled = true;

                submitButton.innerHTML =
                    `<span class="spinner-border spinner-border-sm me-1"></span>
                     {{ __('store.customer_sale.creating_order') }}`;

            });

            updateSummary();


            const mobileInput = document.getElementById('mobile');
            const nameInput = document.getElementById('name');
            const customerStatus = document.getElementById('customerStatus');
            const existingCustomerHint =
            document.getElementById('existingCustomerHint');
            const mobileLoading = document.getElementById('mobileLoading');

            let checkTimeout = null;
            let lastCheckedMobile = null;


            function normalizeMobile(value) {
                return value
                .replace(/\D/g, '')
                .trim();
            }

            function resetCustomerState() {

                customerStatus.className = 'd-none mb-3';
                customerStatus.innerHTML = '';

                existingCustomerHint.classList.add('d-none');
                existingCustomerHint.textContent = '';

                nameInput.readOnly = false;
                nameInput.classList.remove('bg-light');

            }

            function showLoading() {

                mobileLoading.classList.remove('d-none');

            }

            function hideLoading() {

                mobileLoading.classList.add('d-none');

            }

            function showExistingCustomer(customer) {

                customerStatus.className =
                'alert alert-success py-2 px-3 mb-3';

                customerStatus.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-check-fill"></i>

                    <div>
                        <strong>
                            {{ __('store.customer_sale.existing_customer') }}
                        </strong>

                        <div class="small mt-1">
                            ${escapeHtml(customer.name)}
                                </div>
                            </div>
                        </div>
                    `;

                nameInput.value = customer.name;
                nameInput.readOnly = true;
                nameInput.classList.add('bg-light');

                existingCustomerHint.classList.remove('d-none');

                existingCustomerHint.innerHTML =
                `<i class="bi bi-info-circle me-1"></i>
                 {{ __('store.customer_sale.existing_customer_hint') }}`;

            }

            function showNewCustomer() {

            customerStatus.className =
            'alert alert-info py-2 px-3 mb-3';

            customerStatus.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill"></i>

                <span>
                    {{ __('store.customer_sale.new_customer') }}
            </span>
        </div>
`;

            nameInput.readOnly = false;
            nameInput.classList.remove('bg-light');

            existingCustomerHint.classList.add('d-none');

        }

            function showError() {

            customerStatus.className =
            'alert alert-warning py-2 px-3 mb-3';

            customerStatus.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle"></i>

                <span>
                    {{ __('store.customer_sale.mobile_check_error') }}
            </span>
        </div>
`;

        }

            function escapeHtml(value) {

            const div = document.createElement('div');

            div.textContent = value ?? '';

            return div.innerHTML;

        }

            async function checkCustomer() {

            const mobile = normalizeMobile(mobileInput.value);

            resetCustomerState();

            if (mobile.length !== 11) {
                return;
            }

            if (lastCheckedMobile === mobile) {
            return;
        }

            lastCheckedMobile = mobile;

            showLoading();

            try {

            const url =
            `{{ route('store.customers.check-mobile') }}`
            + `?mobile=${encodeURIComponent(mobile)}`;


            const response = await fetch(url, {

            method: 'GET',

            headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },

            credentials: 'same-origin',

        });


            if (!response.ok) {
            throw new Error('Request failed');
        }


            const data = await response.json();


            if (data.exists && data.customer) {

            showExistingCustomer(data.customer);

        } else {

            showNewCustomer();

        }

        } catch (error) {

            console.error(error);

            showError();

        } finally {

            hideLoading();

        }

        }

            mobileInput.addEventListener('input', function () {

            const mobile = normalizeMobile(this.value);

            lastCheckedMobile = null;

            clearTimeout(checkTimeout);

            resetCustomerState();

            if (mobile.length !== 11) {
            return;
        }

            checkTimeout = setTimeout(() => {
            checkCustomer();
        }, 400);

        });

            mobileInput.addEventListener('blur', function () {

                clearTimeout(checkTimeout);

                checkCustomer();

            });

        });
    </script>

</x-admin-layout>