<x-admin-layout title="ایجاد فرمول ساخت" header="ایجاد فرمول ساخت">

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">ایجاد فرمول ساخت</h4>
                <div class="text-muted small">
                    تعریف قطعات و تأمین‌کنندگان دستگاه
                </div>
            </div>

            <a
                    href="{{ route('admin.product-boms.index') }}"
                    class="btn btn-sm btn-secondary"
            >
                <i class="bi bi-chevron-double-right"></i>
                بازگشت
            </a>

        </div>

        @if($errors->any())
            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        <form
                action="{{ route('admin.product-boms.store') }}"
                method="POST"
                id="bomForm"
        >

            @csrf

            {{-- دستگاه --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <strong>
                        دستگاه / محصول نهایی
                    </strong>
                </div>

                <div class="card-body">

                    <div class="col-md-6">

                        <label
                                for="product_id"
                                class="form-label"
                        >
                            دستگاه
                        </label>

                        <select
                                id="product_id"
                                name="product_id"
                                class="form-select"
                                required
                        >

                            <option value="">
                                انتخاب دستگاه...
                            </option>

                            @foreach($products as $product)

                                <option
                                        value="{{ $product->id }}"
                                        @selected(
                                            old('product_id') == $product->id
                                        )
                                >
                                    {{ $product->title }}
                                </option>

                            @endforeach

                        </select>

                        @error('product_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- قطعات --}}
            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">
                    قطعات و مواد اولیه
                </h5>

                <button
                        type="button"
                        id="addComponent"
                        class="btn btn-success btn-sm"
                >
                    <i class="bi bi-plus-lg"></i>
                    افزودن قطعه
                </button>

            </div>


            <div id="componentsContainer"></div>


            <div class="d-flex justify-content-between mt-4">

                <a
                        href="{{ route('admin.product-boms.index') }}"
                        class="btn btn-secondary"
                >
                    بازگشت
                </a>

                <button
                        type="submit"
                        class="btn btn-primary px-5"
                >
                    <i class="bi bi-check-lg"></i>
                    ذخیره فرمول ساخت
                </button>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- Template قطعه --}}
    {{-- ========================================================= --}}

    <template id="componentTemplate">

        <div class="component-card card shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <strong>
                    قطعه
                    <span class="component-number"></span>
                </strong>

                <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove-component"
                >
                    <i class="bi bi-trash"></i>
                    حذف قطعه
                </button>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- قطعه --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            قطعه / ماده اولیه
                        </label>

                        <select
                                class="form-select component-product select2-product"
                                data-field="component_product_id"
                                required
                        >

                            <option value="">
                                انتخاب قطعه...
                            </option>

                            @foreach($products as $product)

                                <option value="{{ $product->id }}">
                                    {{ $product->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- مقدار --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            مقدار
                        </label>

                        <input
                                type="number"
                                step="0.0001"
                                min="0.0001"
                                class="form-control"
                                data-field="quantity"
                                required
                        >

                    </div>


                    {{-- واحد --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            واحد
                        </label>

                        <input
                                type="text"
                                class="form-control"
                                data-field="unit"
                                placeholder="عدد"
                        >

                    </div>


                    {{-- قیمت --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            قیمت پایه
                        </label>

                        <input
                                type="number"
                                min="0"
                                class="form-control"
                                data-field="unit_price"
                                required
                        >

                    </div>


                    {{-- توضیحات --}}
                    <div class="col-12">

                        <label class="form-label">
                            توضیحات
                        </label>

                        <textarea
                                class="form-control"
                                rows="2"
                                data-field="note"
                        ></textarea>

                    </div>

                </div>


                <hr class="my-4">


                {{-- تأمین‌کنندگان --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h6 class="mb-0">
                        تأمین‌کنندگان این قطعه
                    </h6>

                    <button
                            type="button"
                            class="btn btn-outline-success btn-sm add-supplier"
                    >
                        <i class="bi bi-plus"></i>
                        افزودن تأمین‌کننده
                    </button>

                </div>


                <div class="suppliers-container"></div>

            </div>

        </div>

    </template>


    {{-- ========================================================= --}}
    {{-- Template تأمین‌کننده --}}
    {{-- ========================================================= --}}

    <template id="supplierTemplate">

        <div class="supplier-row border rounded p-3 mb-3">

            <div class="row g-3 align-items-end">

                {{-- تأمین‌کننده --}}
                <div class="col-md-5">

                    <label class="form-label">
                        تأمین‌کننده
                    </label>

                    <select
                            class="form-select select2-supplier"
                            data-field="supplier_id"
                            required
                    >

                        <option value="">
                            انتخاب تأمین‌کننده...
                        </option>

                        @foreach($suppliers as $supplier)

                            <option value="{{ $supplier->id }}">
                                {{ $supplier->name }}

                                @if($supplier->mobile)
                                    - {{ $supplier->mobile }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- قیمت --}}
                <div class="col-md-3">

                    <label class="form-label">
                        قیمت واحد
                    </label>

                    <input
                            type="number"
                            min="0"
                            class="form-control"
                            data-field="unit_price"
                            required
                    >

                </div>


                {{-- پیش فرض --}}
                <div class="col-md-1">

                    <div class="form-check">

                        <input
                                type="hidden"
                                data-field="is_default"
                                value="0"
                        >

                        <input
                                type="checkbox"
                                class="form-check-input"
                                data-field="is_default"
                                value="1"
                        >

                        <label class="form-check-label">
                            پیش‌فرض
                        </label>

                    </div>

                </div>


                {{-- فعال --}}
                <div class="col-md-1">

                    <div class="form-check">

                        <input
                                type="hidden"
                                data-field="is_active"
                                value="0"
                        >

                        <input
                                type="checkbox"
                                class="form-check-input"
                                data-field="is_active"
                                value="1"
                                checked
                        >

                        <label class="form-check-label">
                            فعال
                        </label>

                    </div>

                </div>


                {{-- حذف --}}
                <div class="col-md-2">

                    <button
                            type="button"
                            class="btn btn-outline-danger w-100 remove-supplier"
                    >
                        <i class="bi bi-trash"></i>
                        حذف
                    </button>

                </div>


                {{-- توضیحات --}}
                <div class="col-12">

                    <input
                            type="text"
                            class="form-control"
                            data-field="note"
                            placeholder="توضیحات تأمین‌کننده"
                    >

                </div>

            </div>

        </div>

    </template>


    @push('scripts')

        <script>

            $(function () {

                /*
                 * ---------------------------------------------------------
                 * Select2 - دستگاه
                 * ---------------------------------------------------------
                 */

                $('#product_id').select2({
                    theme: 'bootstrap-5',
                    dir: 'rtl',
                    width: '100%',
                    placeholder: 'دستگاه را انتخاب کنید',
                    allowClear: true,
                    language: {
                        noResults: function () {
                            return 'موردی یافت نشد';
                        }
                    }
                });


                const componentsContainer =
                    document.getElementById('componentsContainer');

                const componentTemplate =
                    document.getElementById('componentTemplate');

                const supplierTemplate =
                    document.getElementById('supplierTemplate');

                const addComponentButton =
                    document.getElementById('addComponent');


                /*
                 * ---------------------------------------------------------
                 * فعال کردن Select2 برای یک قطعه
                 * ---------------------------------------------------------
                 */

                function initComponentSelect2(card) {

                    $(card)
                        .find('.select2-product')
                        .select2({
                            theme: 'bootstrap-5',
                            dir: 'rtl',
                            width: '100%',
                            placeholder: 'قطعه را انتخاب کنید',
                            allowClear: true,
                            language: {
                                noResults: function () {
                                    return 'موردی یافت نشد';
                                }
                            }
                        });

                }


                /*
                 * ---------------------------------------------------------
                 * فعال کردن Select2 برای تأمین‌کننده
                 * ---------------------------------------------------------
                 */

                function initSupplierSelect2(row) {

                    $(row)
                        .find('.select2-supplier')
                        .select2({
                            theme: 'bootstrap-5',
                            dir: 'rtl',
                            width: '100%',
                            placeholder: 'تأمین‌کننده را انتخاب کنید',
                            allowClear: true,
                            language: {
                                noResults: function () {
                                    return 'موردی یافت نشد';
                                }
                            }
                        });

                }


                /*
                 * ---------------------------------------------------------
                 * افزودن قطعه
                 * ---------------------------------------------------------
                 */

                function addComponent() {

                    const fragment =
                        componentTemplate.content.cloneNode(true);

                    const card =
                        fragment.querySelector('.component-card');

                    componentsContainer.appendChild(card);

                    initComponentSelect2(card);

                    refreshNames();
                }


                /*
                 * ---------------------------------------------------------
                 * افزودن تأمین‌کننده
                 * ---------------------------------------------------------
                 */

                function addSupplier(componentCard) {

                    const container =
                        componentCard.querySelector(
                            '.suppliers-container'
                        );

                    const fragment =
                        supplierTemplate.content.cloneNode(true);

                    const row =
                        fragment.querySelector('.supplier-row');

                    container.appendChild(row);

                    initSupplierSelect2(row);

                    refreshNames();
                }


                /*
                 * ---------------------------------------------------------
                 * ساخت name برای input ها
                 * ---------------------------------------------------------
                 */

                function refreshNames() {

                    const components =
                        componentsContainer.querySelectorAll(
                            '.component-card'
                        );

                    components.forEach(
                        (component, componentIndex) => {

                            component.querySelector(
                                '.component-number'
                            ).textContent = componentIndex + 1;


                            /*
                             * فقط فیلدهای مستقیم قطعه
                             */

                            component
                                .querySelectorAll(
                                    '.component-product, [data-field="quantity"], [data-field="unit"], [data-field="unit_price"], [data-field="note"]'
                                )
                                .forEach(element => {

                                    const field =
                                        element.dataset.field;

                                    element.name =
                                        `components[${componentIndex}][${field}]`;

                                });


                            /*
                             * تأمین‌کنندگان
                             */

                            const suppliers =
                                component.querySelectorAll(
                                    '.supplier-row'
                                );

                            suppliers.forEach(
                                (supplier, supplierIndex) => {

                                    supplier
                                        .querySelectorAll('[data-field]')
                                        .forEach(element => {

                                            const field =
                                                element.dataset.field;

                                            element.name =
                                                `components[${componentIndex}][suppliers][${supplierIndex}][${field}]`;

                                        });

                                }
                            );

                        }
                    );
                }

                /*
                 * ---------------------------------------------------------
                 * حذف قطعه / تأمین‌کننده
                 * ---------------------------------------------------------
                 */

                componentsContainer.addEventListener(
                    'click',
                    function (event) {

                        const removeComponent =
                            event.target.closest(
                                '.remove-component'
                            );

                        if (removeComponent) {

                            removeComponent
                                .closest('.component-card')
                                .remove();

                            refreshNames();

                            return;
                        }


                        const addSupplierButton =
                            event.target.closest(
                                '.add-supplier'
                            );

                        if (addSupplierButton) {

                            addSupplier(
                                addSupplierButton.closest(
                                    '.component-card'
                                )
                            );

                            return;
                        }


                        const removeSupplier =
                            event.target.closest(
                                '.remove-supplier'
                            );

                        if (removeSupplier) {

                            const row =
                                removeSupplier.closest(
                                    '.supplier-row'
                                );

                            /*
                             * قبل از حذف Select2 را destroy می‌کنیم
                             */
                            $(row)
                                .find('.select2-supplier')
                                .select2('destroy');

                            row.remove();

                            refreshNames();
                        }

                    }
                );


                /*
                 * ---------------------------------------------------------
                 * دکمه افزودن قطعه
                 * ---------------------------------------------------------
                 */

                addComponentButton.addEventListener(
                    'click',
                    addComponent
                );


                /*
                 * ---------------------------------------------------------
                 * حداقل یک قطعه
                 * ---------------------------------------------------------
                 */

                addComponent();

            });

        </script>

    @endpush

</x-admin-layout>