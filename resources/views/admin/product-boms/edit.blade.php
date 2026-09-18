<x-admin-layout title="ویرایش فرمول دستگاه">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    <i class="bi bi-diagram-3"></i>
                    ویرایش فرمول دستگاه
                </h4>

                <div class="text-muted">
                    تمام قطعات و تأمین‌کنندگان دستگاه را در این صفحه مدیریت کنید.
                </div>
            </div>

            <a href="{{ route('admin.product-boms.index') }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-right"></i>
                بازگشت
            </a>

        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-2">
                    <i class="bi bi-exclamation-triangle"></i>
                    لطفاً خطاهای زیر را بررسی کنید:
                </div>

                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.product-boms.update', $product->boms->first()) }}">

            @csrf
            @method('PUT')


            {{-- Device --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>
                        <i class="bi bi-cpu"></i>
                        دستگاه
                    </strong>
                </div>

                <div class="card-body">

                    <label class="form-label">
                        دستگاه
                    </label>

                    <select id="product_id"
                            class="form-select"
                            disabled>

                        <option value="{{ $product->id }}" selected>
                            {{ $product->title }}
                        </option>

                    </select>

                    <input type="hidden"
                           name="product_id"
                           value="{{ $product->id }}">

                    <div class="form-text">
                        دستگاه در این صفحه قابل تغییر نیست.
                        برای دستگاه دیگر باید فرمول جدید ایجاد شود.
                    </div>

                </div>

            </div>


            {{-- Components --}}
            <div class="card shadow-sm">

                <div class="card-header bg-white
                            d-flex justify-content-between align-items-center">

                    <strong>
                        <i class="bi bi-boxes"></i>
                        قطعات و مواد اولیه
                    </strong>

                    <button type="button"
                            class="btn btn-primary btn-sm"
                            id="addComponentBtn">

                        <i class="bi bi-plus-lg"></i>
                        افزودن قطعه

                    </button>

                </div>


                <div class="card-body">

                    <div id="componentsContainer">

                        @foreach($product->boms as $componentIndex => $bom)

                            <div class="component-card border border-info rounded p-3 mb-4"
                                 data-component-index="{{ $componentIndex }}">

                                <input type="hidden"
                                       class="component-id"
                                       name="components[{{ $componentIndex }}][id]"
                                       value="{{ $bom->id }}">

                                <div class="d-flex justify-content-between
                                            align-items-center mb-3">

                                    <h6 class="mb-0">
                                        قطعه #{{ $componentIndex + 1 }}
                                    </h6>

                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm remove-component">

                                        <i class="bi bi-trash"></i>
                                        حذف قطعه

                                    </button>

                                </div>


                                <div class="row g-3">

                                    {{-- Component --}}
                                    <div class="col-md-5">

                                        <label class="form-label">
                                            قطعه / ماده اولیه
                                        </label>

                                        <select class="form-select select2-product component-product">

                                            <option value=""></option>

                                            @foreach($products as $item)

                                                <option value="{{ $item->id }}"
                                                        @selected($item->id == $bom->component_product_id)>

                                                    {{ $item->title }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    {{-- Quantity --}}
                                    <div class="col-md-2">

                                        <label class="form-label">
                                            مقدار
                                        </label>

                                        <input type="number"
                                               step="0.0001"
                                               min="0.0001"
                                               class="form-control component-quantity"
                                               value="{{ $bom->quantity }}">

                                    </div>


                                    {{-- Unit --}}
                                    <div class="col-md-2">

                                        <label class="form-label">
                                            واحد
                                        </label>

                                        <input type="text"
                                               class="form-control component-unit"
                                               value="{{ $bom->unit }}"
                                               placeholder="عدد / کیلو / متر">

                                    </div>


                                    {{-- Unit Price --}}
                                    <div class="col-md-3">

                                        <label class="form-label">
                                            قیمت واحد
                                        </label>

                                        <input type="number"
                                               min="0"
                                               class="form-control component-price"
                                               value="{{ $bom->unit_price }}">

                                    </div>


                                    {{-- Note --}}
                                    <div class="col-12">

                                        <label class="form-label">
                                            توضیحات
                                        </label>

                                        <textarea class="form-control component-note"
                                                  rows="2"
                                                  placeholder="توضیحات مربوط به این قطعه...">{{ $bom->note }}</textarea>

                                    </div>

                                </div>


                                {{-- Suppliers --}}
                                <div class="mt-4">

                                    <div class="d-flex justify-content-between
                                                align-items-center mb-2">

                                        <strong>
                                            <i class="bi bi-truck"></i>
                                            تأمین‌کنندگان
                                        </strong>

                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm add-supplier">

                                            <i class="bi bi-plus"></i>
                                            افزودن تأمین‌کننده

                                        </button>

                                    </div>


                                    <div class="suppliers-container">

                                        @foreach($bom->suppliers as $supplierIndex => $bomSupplier)

                                            <div class="supplier-row border rounded p-2 mb-2">

                                                <div class="row g-2 align-items-end">

                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            تأمین‌کننده
                                                        </label>

                                                        <select class="form-select select2-supplier supplier-select">

                                                            <option value=""></option>

                                                            @foreach($suppliers as $supplier)

                                                                <option value="{{ $supplier->id }}"
                                                                        @selected($supplier->id == $bomSupplier->supplier_id)>

                                                                    {{ $supplier->name }}
                                                                    {{ $supplier->mobile ? ' - '.$supplier->mobile : '' }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    <div class="col-md-3">

                                                        <label class="form-label">
                                                            قیمت خرید
                                                        </label>

                                                        <input type="number"
                                                               min="0"
                                                               class="form-control supplier-price"
                                                               value="{{ $bomSupplier->unit_price }}">

                                                    </div>


                                                    <div class="col-md-2">

                                                        <div class="form-check mb-2">

                                                            <input type="checkbox"
                                                                   class="form-check-input supplier-default"
                                                                   value="1"
                                                                    @checked($bomSupplier->is_default)>

                                                            <label class="form-check-label">
                                                                تأمین‌کننده پیش‌فرض
                                                            </label>

                                                        </div>

                                                    </div>


                                                    <div class="col-md-2">

                                                        <div class="form-check mb-2">

                                                            <input type="checkbox"
                                                                   class="form-check-input supplier-active"
                                                                   value="1"
                                                                    @checked($bomSupplier->is_active)>

                                                            <label class="form-check-label">
                                                                فعال
                                                            </label>

                                                        </div>

                                                    </div>


                                                    <div class="col-md-1">

                                                        <button type="button"
                                                                class="btn btn-outline-danger w-100 remove-supplier">

                                                            <i class="bi bi-trash"></i>

                                                        </button>

                                                    </div>


                                                    <div class="col-12">

                                                        <input type="text"
                                                               class="form-control supplier-note"
                                                               value="{{ $bomSupplier->note }}"
                                                               placeholder="توضیحات تأمین‌کننده">

                                                    </div>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    @if($product->boms->isEmpty())

                        <div id="emptyComponents"
                             class="text-center text-muted py-5">

                            <i class="bi bi-box-seam fs-1 d-block mb-3"></i>

                            هنوز قطعه‌ای برای این دستگاه تعریف نشده است.

                        </div>

                    @endif

                </div>


                <div class="card-footer bg-white
                            d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.product-boms.index') }}"
                       class="btn btn-secondary">

                        انصراف

                    </a>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-lg"></i>
                        ذخیره فرمول دستگاه

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- Component Template --}}
    <template id="componentTemplate">

        <div class="component-card border border-info rounded p-3 mb-4"
             data-component-index="__INDEX__">

            <input type="hidden"
                   class="component-id"
                   value="">


            <div class="d-flex justify-content-between
                        align-items-center mb-3">

                <h6 class="mb-0">
                    قطعه
                </h6>

                <button type="button"
                        class="btn btn-outline-danger btn-sm remove-component">

                    <i class="bi bi-trash"></i>
                    حذف قطعه

                </button>

            </div>


            <div class="row g-3">

                <div class="col-md-5">

                    <label class="form-label">
                        قطعه / ماده اولیه
                    </label>

                    <select class="form-select select2-product component-product">

                        <option value=""></option>

                        @foreach($products as $item)

                            <option value="{{ $item->id }}">
                                {{ $item->title }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        مقدار
                    </label>

                    <input type="number"
                           step="0.0001"
                           min="0.0001"
                           class="form-control component-quantity">

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        واحد
                    </label>

                    <input type="text"
                           class="form-control component-unit"
                           placeholder="عدد / کیلو / متر">

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        قیمت واحد
                    </label>

                    <input type="number"
                           min="0"
                           class="form-control component-price">

                </div>


                <div class="col-12">

                    <label class="form-label">
                        توضیحات
                    </label>

                    <textarea class="form-control component-note"
                              rows="2"></textarea>

                </div>

            </div>


            <div class="mt-4">

                <div class="d-flex justify-content-between
                            align-items-center mb-2">

                    <strong>
                        <i class="bi bi-truck"></i>
                        تأمین‌کنندگان
                    </strong>

                    <button type="button"
                            class="btn btn-outline-primary btn-sm add-supplier">

                        <i class="bi bi-plus"></i>
                        افزودن تأمین‌کننده

                    </button>

                </div>


                <div class="suppliers-container"></div>

            </div>

        </div>

    </template>


    {{-- Supplier Template --}}
    <template id="supplierTemplate">

        <div class="supplier-row border rounded p-2 mb-2">

            <div class="row g-2 align-items-end">

                <div class="col-md-4">

                    <label class="form-label">
                        تأمین‌کننده
                    </label>

                    <select class="form-select select2-supplier supplier-select">

                        <option value=""></option>

                        @foreach($suppliers as $supplier)

                            <option value="{{ $supplier->id }}">

                                {{ $supplier->name }}
                                {{ $supplier->mobile ? ' - '.$supplier->mobile : '' }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        قیمت خرید
                    </label>

                    <input type="number"
                           min="0"
                           class="form-control supplier-price">

                </div>


                <div class="col-md-2">

                    <div class="form-check mb-2">

                        <input type="checkbox"
                               class="form-check-input supplier-default"
                               value="1">

                        <label class="form-check-label">
                            پیش‌فرض
                        </label>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="form-check mb-2">

                        <input type="checkbox"
                               class="form-check-input supplier-active"
                               value="1"
                               checked>

                        <label class="form-check-label">
                            فعال
                        </label>

                    </div>

                </div>


                <div class="col-md-1">

                    <button type="button"
                            class="btn btn-sm btn-outline-danger w-100 remove-supplier">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>


                <div class="col-12">

                    <input type="text"
                           class="form-control supplier-note"
                           placeholder="توضیحات تأمین‌کننده">

                </div>

            </div>

        </div>

    </template>


    @push('scripts')

        <script>

            $(function () {

                let componentIndex =
                    $('#componentsContainer .component-card').length;


                function initProductSelect(element) {

                    const $element = $(element);

                    if ($element.hasClass('select2-hidden-accessible')) {
                        return;
                    }

                    $element.select2({
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


                function initSupplierSelect(element) {

                    const $element = $(element);

                    if ($element.hasClass('select2-hidden-accessible')) {
                        return;
                    }

                    $element.select2({
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


                $('.component-product').each(function () {
                    initProductSelect(this);
                });


                $('.supplier-select').each(function () {
                    initSupplierSelect(this);
                });


                function refreshNames() {

                    $('#componentsContainer .component-card')
                        .each(function (componentIndex) {

                            const $component = $(this);

                            $component.attr(
                                'data-component-index',
                                componentIndex
                            );


                            $component.find('.component-id')
                                .attr(
                                    'name',
                                    `components[${componentIndex}][id]`
                                );


                            $component.find('.component-product')
                                .attr(
                                    'name',
                                    `components[${componentIndex}][component_product_id]`
                                );


                            $component.find('.component-quantity')
                                .attr(
                                    'name',
                                    `components[${componentIndex}][quantity]`
                                );


                            $component.find('.component-unit')
                                .attr(
                                    'name',
                                    `components[${componentIndex}][unit]`
                                );


                            $component.find('.component-price')
                                .attr(
                                    'name',
                                    `components[${componentIndex}][unit_price]`
                                );


                            $component.find('.component-note')
                                .attr(
                                    'name',
                                    `components[${componentIndex}][note]`
                                );


                            $component.find('.supplier-row')
                                .each(function (supplierIndex) {

                                    const $row = $(this);

                                    $row.find('.supplier-select')
                                        .attr(
                                            'name',
                                            `components[${componentIndex}][suppliers][${supplierIndex}][supplier_id]`
                                        );


                                    $row.find('.supplier-price')
                                        .attr(
                                            'name',
                                            `components[${componentIndex}][suppliers][${supplierIndex}][unit_price]`
                                        );


                                    $row.find('.supplier-default')
                                        .attr(
                                            'name',
                                            `components[${componentIndex}][suppliers][${supplierIndex}][is_default]`
                                        );


                                    $row.find('.supplier-active')
                                        .attr(
                                            'name',
                                            `components[${componentIndex}][suppliers][${supplierIndex}][is_active]`
                                        );


                                    $row.find('.supplier-note')
                                        .attr(
                                            'name',
                                            `components[${componentIndex}][suppliers][${supplierIndex}][note]`
                                        );

                                });

                        });

                }


                function addSupplier(component) {

                    const template =
                        $('#supplierTemplate')
                            .html()
                            .replace(/__INDEX__/g, Date.now());

                    const $row = $(template);

                    component
                        .find('.suppliers-container')
                        .append($row);

                    initSupplierSelect(
                        $row.find('.supplier-select')
                    );

                    refreshNames();
                }


                $('#addComponentBtn').on('click', function () {

                    const index = componentIndex++;

                    const html =
                        $('#componentTemplate')
                            .html()
                            .replace(/__INDEX__/g, index);

                    const $component = $(html);

                    $('#componentsContainer')
                        .append($component);

                    initProductSelect(
                        $component.find('.component-product')
                    );

                    addSupplier($component);

                    $('#emptyComponents').remove();

                    refreshNames();

                });


                $(document).on(
                    'click',
                    '.add-supplier',
                    function () {

                        const $component =
                            $(this).closest('.component-card');

                        addSupplier($component);

                    }
                );


                $(document).on(
                    'click',
                    '.remove-supplier',
                    function () {

                        $(this)
                            .closest('.supplier-row')
                            .remove();

                        refreshNames();

                    }
                );


                $(document).on(
                    'click',
                    '.remove-component',
                    function () {

                        const $component =
                            $(this).closest('.component-card');

                        $component
                            .find('.select2-hidden-accessible')
                            .each(function () {

                                $(this).select2('destroy');

                            });

                        $component.remove();

                        refreshNames();

                    }
                );


                /*
                 * فقط یک تأمین‌کننده پیش‌فرض برای هر قطعه
                 */
                $(document).on(
                    'change',
                    '.supplier-default',
                    function () {

                        if (!this.checked) {
                            return;
                        }

                        const $component =
                            $(this).closest('.component-card');

                        $component
                            .find('.supplier-default')
                            .not(this)
                            .prop('checked', false);

                    }
                );


                refreshNames();

            });

        </script>

    @endpush

</x-admin-layout>