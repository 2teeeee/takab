<x-admin-layout>
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">فروش مستقیم</h4>
                <div class="text-muted small">
                    ثبت فروش محصولات از موجودی انبار مرکزی
                </div>
            </div>
        </div>

        {{-- پیام‌ها --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-2">لطفاً خطاهای زیر را اصلاح کنید:</div>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.direct-sales.store') }}" id="directSaleForm">
            @csrf

            <div class="row g-4">

                {{-- اطلاعات مشتری --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <strong>اطلاعات مشتری</strong>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">
                                        مشتری <span class="text-danger">*</span>
                                    </label>

                                    <select name="customer_id"
                                            class="form-select"
                                            required>
                                        <option value="">انتخاب مشتری</option>

                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                    @selected(old('customer_id') == $customer->id)>
                                                {{ $customer->name }}
                                                ({{ $customer->mobile }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">کد معرف</label>

                                    <input type="text"
                                           name="referral_code"
                                           id="referralCode"
                                           class="form-control"
                                           value="{{ old('referral_code') }}"
                                           placeholder="در صورت وجود کد معرف وارد کنید">

                                    <small class="text-muted">
                                        در صورت معتبر بودن کد معرف، تخفیف مطابق قوانین فعلی اعمال می‌شود.
                                    </small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        آدرس مشتری <span class="text-danger">*</span>
                                    </label>

                                    <textarea name="address"
                                              class="form-control"
                                              rows="2"
                                              required
                                              maxlength="255"
                                              placeholder="آدرس تحویل سفارش">{{ old('address') }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- محصولات --}}
                <div class="col-12">
                    <div class="card shadow-sm">

                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <strong>محصولات سفارش</strong>

                            <button type="button"
                                    class="btn btn-sm btn-primary"
                                    id="addProductBtn">
                                <i class="bi bi-plus-lg"></i>
                                افزودن محصول
                            </button>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center"
                                       id="productsTable">

                                    <thead class="table-light">
                                    <tr>
                                        <th style="min-width:280px">محصول</th>
                                        <th style="width:150px">موجودی</th>
                                        <th style="width:150px">قیمت واحد</th>
                                        <th style="width:130px">تعداد</th>
                                        <th style="width:160px">مبلغ</th>
                                        <th style="width:70px">حذف</th>
                                    </tr>
                                    </thead>

                                    <tbody id="productRows">

                                    {{-- ردیف اولیه --}}
                                    <tr class="product-row">
                                        <td>
                                            <select name="items[0][product_id]"
                                                    class="form-select product-select"
                                                    required>
                                                <option value="">انتخاب محصول</option>

                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                            data-price="{{ $product->sell_price }}"
                                                            data-stock="{{ $product->stock_quantity }}">
                                                        {{ $product->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <span class="stock-text">-</span>
                                        </td>

                                        <td>
                                            <span class="price-text">-</span>
                                        </td>

                                        <td>
                                            <input type="number"
                                                   name="items[0][quantity]"
                                                   class="form-control quantity-input"
                                                   min="1"
                                                   value="1"
                                                   required>
                                        </td>

                                        <td>
                                            <span class="item-total">-</span>
                                        </td>

                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-row">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>

                            @if($products->isEmpty())
                                <div class="alert alert-warning mb-0">
                                    در حال حاضر محصولی با موجودی قابل فروش در انبار مرکزی وجود ندارد.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- پرداخت و جمع سفارش --}}
                <div class="col-12">
                    <div class="card shadow-sm">

                        <div class="card-header bg-white">
                            <strong>مبلغ و نحوه پرداخت</strong>
                        </div>

                        <div class="card-body">
                            <div class="row g-4">

                                <div class="col-md-5">
                                    <label class="form-label">
                                        نوع پرداخت <span class="text-danger">*</span>
                                    </label>

                                    <select name="payment_type"
                                            class="form-select"
                                            required>
                                        <option value="cash"
                                                @selected(old('payment_type', 'cash') === 'cash')>
                                            نقدی / کارت‌خوان
                                        </option>

                                        <option value="cash_on_delivery"
                                                @selected(old('payment_type') === 'cash_on_delivery')>
                                            پرداخت در محل
                                        </option>
                                    </select>

                                    <small class="text-muted">
                                        پرداخت نقدی به‌عنوان پرداخت‌شده ثبت می‌شود.
                                        پرداخت در محل تا زمان دریافت وجه، پرداخت‌نشده باقی می‌ماند.
                                    </small>
                                </div>

                                <div class="col-md-7">
                                    <div class="border rounded p-3 bg-light">

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>جمع کل محصولات:</span>
                                            <strong id="totalText">۰</strong>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>تخفیف:</span>
                                            <strong id="discountText">۰</strong>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">مبلغ قابل پرداخت:</span>
                                            <strong class="text-success fs-5" id="finalTotalText">
                                                ۰
                                            </strong>
                                        </div>

                                        <small class="text-muted d-block mt-2">
                                            مبلغ تخفیف نهایی پس از بررسی کد معرف در سرور محاسبه می‌شود.
                                        </small>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-end gap-2">

                            <button type="submit"
                                    class="btn btn-success"
                                    id="submitBtn"
                                    @disabled($products->isEmpty())>
                                <i class="bi bi-check-circle"></i>
                                ثبت فروش
                            </button>

                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- داده محصولات برای ساخت ردیف‌های جدید --}}
    <script>
        const productOptions = {{ \Illuminate\Support\Js::from($productOptions) }};

        const rowsContainer = document.getElementById('productRows');
        const addProductBtn = document.getElementById('addProductBtn');

        let rowIndex = 1;

        function formatMoney(value) {
            return new Intl.NumberFormat('fa-IR').format(value) + ' تومان';
        }

        function createProductOptions() {
            let html = '<option value="">انتخاب محصول</option>';

            productOptions.forEach(product => {
                html += `
                    <option value="${product.id}"
                            data-price="${product.price}"
                            data-stock="${product.stock}">
                        ${escapeHtml(product.title)}
                    </option>
                `;
            });

            return html;
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[char]);
        }

        function addProductRow() {
            const tr = document.createElement('tr');

            tr.className = 'product-row';

            tr.innerHTML = `
                <td>
                    <select name="items[${rowIndex}][product_id]"
                            class="form-select product-select"
                            required>
                        ${createProductOptions()}
                    </select>
                </td>

                <td><span class="stock-text">-</span></td>

                <td><span class="price-text">-</span></td>

                <td>
                    <input type="number"
                           name="items[${rowIndex}][quantity]"
                           class="form-control quantity-input"
                           min="1"
                           value="1"
                           required>
                </td>

                <td><span class="item-total">-</span></td>

                <td>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger remove-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

            rowsContainer.appendChild(tr);

            rowIndex++;
        }

        function updateRow(row) {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');

            const option = select.options[select.selectedIndex];

            if (!option || !option.value) {
                row.querySelector('.stock-text').textContent = '-';
                row.querySelector('.price-text').textContent = '-';
                row.querySelector('.item-total').textContent = '-';
                return;
            }

            const price = Number(option.dataset.price || 0);
            const stock = Number(option.dataset.stock || 0);

            let quantity = Number(quantityInput.value || 1);

            if (quantity < 1) {
                quantity = 1;
                quantityInput.value = 1;
            }

            quantityInput.max = stock;

            row.querySelector('.stock-text').textContent =
                new Intl.NumberFormat('fa-IR').format(stock);

            row.querySelector('.price-text').textContent =
                formatMoney(price);

            row.querySelector('.item-total').textContent =
                formatMoney(price * quantity);

            if (quantity > stock) {
                quantityInput.classList.add('is-invalid');
            } else {
                quantityInput.classList.remove('is-invalid');
            }
        }

        function updateSummary() {
            let total = 0;
            let hasInvalidQuantity = false;

            document.querySelectorAll('.product-row').forEach(row => {
                updateRow(row);

                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');

                const option = select.options[select.selectedIndex];

                if (!option || !option.value) {
                    return;
                }

                const price = Number(option.dataset.price || 0);
                const stock = Number(option.dataset.stock || 0);
                const quantity = Number(quantityInput.value || 0);

                if (quantity < 1 || quantity > stock) {
                    hasInvalidQuantity = true;
                }

                total += price * quantity;
            });

            const referralCode =
                document.getElementById('referralCode').value.trim();

            // نمایش تخمینی تخفیف؛ اعتبار کد در سرور بررسی می‌شود.
            const discount = referralCode ? 1000000 : 0;
            const finalTotal = Math.max(0, total - discount);

            document.getElementById('totalText').textContent =
                formatMoney(total);

            document.getElementById('discountText').textContent =
                formatMoney(discount);

            document.getElementById('finalTotalText').textContent =
                formatMoney(finalTotal);

            document.getElementById('submitBtn').disabled =
                hasInvalidQuantity || productOptions.length === 0;
        }

        addProductBtn.addEventListener('click', addProductRow);

        rowsContainer.addEventListener('change', function (event) {
            if (event.target.classList.contains('product-select')) {
                updateSummary();
            }
        });

        rowsContainer.addEventListener('input', function (event) {
            if (event.target.classList.contains('quantity-input')) {
                updateSummary();
            }
        });

        rowsContainer.addEventListener('click', function (event) {
            const button = event.target.closest('.remove-row');

            if (!button) {
                return;
            }

            const rows = document.querySelectorAll('.product-row');

            if (rows.length <= 1) {
                const row = button.closest('.product-row');
                row.querySelector('.product-select').value = '';
                row.querySelector('.quantity-input').value = 1;
            } else {
                button.closest('.product-row').remove();
            }

            updateSummary();
        });

        document.getElementById('referralCode')
            .addEventListener('input', updateSummary);

        document.getElementById('directSaleForm')
            .addEventListener('submit', function (event) {

                const selectedProducts = [];
                let hasError = false;

                document.querySelectorAll('.product-row').forEach(row => {
                    const select = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');

                    if (!select.value) {
                        hasError = true;
                        return;
                    }

                    if (selectedProducts.includes(select.value)) {
                        hasError = true;
                    }

                    selectedProducts.push(select.value);

                    const option = select.options[select.selectedIndex];
                    const stock = Number(option.dataset.stock || 0);
                    const quantity = Number(quantityInput.value || 0);

                    if (quantity < 1 || quantity > stock) {
                        hasError = true;
                    }
                });

                if (hasError) {
                    event.preventDefault();
                    alert('لطفاً محصولات را به‌درستی انتخاب کنید و تعداد را با موجودی انبار تطبیق دهید. هر محصول را فقط یک‌بار به سفارش اضافه کنید.');
                    return;
                }

                if (!confirm('آیا از ثبت این فروش اطمینان دارید؟')) {
                    event.preventDefault();
                }
            });

        updateSummary();
    </script>
</x-admin-layout>
