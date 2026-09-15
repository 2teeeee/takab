<x-admin-layout title="فرمول ساخت جدید">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">فرمول ساخت جدید</h4>
            <div class="text-muted small">
                تعریف قطعات و تأمین‌کنندگان دستگاه
            </div>
        </div>

        <a
                href="{{ route('admin.product-boms.index') }}"
                class="btn btn-sm btn-outline-secondary"
        >
            <i class="bi bi-arrow-right"></i>
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
            method="POST"
            action="{{ route('admin.product-boms.store') }}"
            id="bomForm"
    >

        @csrf

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label">
                            دستگاه / محصول نهایی
                        </label>

                        <select
                                name="product_id"
                                id="product_id"
                                class="form-select"
                                required
                        >

                            <option value="">
                                انتخاب دستگاه
                            </option>

                            @foreach($products as $product)

                                <option
                                        value="{{ $product->id }}"
                                        @selected(old('product_id') == $product->id)
                                >
                                    {{ $product->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">
                قطعات و مواد اولیه
            </h5>

            <button
                    type="button"
                    class="btn btn-success btn-sm"
                    id="addComponent"
            >
                <i class="bi bi-plus-lg"></i>
                افزودن قطعه
            </button>

        </div>

        <div id="componentsContainer"></div>

        <div class="text-end mt-4">

            <button
                    type="submit"
                    class="btn btn-sm btn-primary px-5"
            >
                <i class="bi bi-check-lg"></i>
                ذخیره فرمول ساخت
            </button>

        </div>

    </form>

    {{-- Template قطعه --}}
    <template id="componentTemplate">

        <div class="component-card card border-0 shadow-sm mb-4">

            <div class="card-header bg-light d-flex justify-content-between">

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

                    <div class="col-md-6">

                        <label class="form-label">
                            قطعه / ماده اولیه
                        </label>

                        <select
                                class="form-select component-product"
                                data-field="component_product_id"
                                required
                        >

                            <option value="">
                                انتخاب قطعه
                            </option>

                            @foreach($products as $product)

                                <option value="{{ $product->id }}">
                                    {{ $product->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>

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

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h6 class="mb-0">
                        تأمین‌کنندگان
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

    {{-- Template تأمین‌کننده --}}
    <template id="supplierTemplate">

        <div class="supplier-row border rounded p-3 mb-2">

            <div class="row g-2 align-items-end">

                <div class="col-md-4">

                    <label class="form-label">
                        تأمین‌کننده
                    </label>

                    <select
                            class="form-select supplier-select"
                            data-field="supplier_id"
                            required
                    >

                        <option value="">
                            انتخاب تأمین‌کننده
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

                <div class="col-md-2">

                    <div class="form-check mb-2">

                        <input
                                type="checkbox"
                                class="form-check-input"
                                data-field="is_default"
                        >

                        <label class="form-check-label">
                            پیش‌فرض
                        </label>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="form-check mb-2">

                        <input
                                type="checkbox"
                                class="form-check-input"
                                data-field="is_active"
                                checked
                        >

                        <label class="form-check-label">
                            فعال
                        </label>

                    </div>

                </div>

                <div class="col-md-1">

                    <button
                            type="button"
                            class="btn btn-sm btn-outline-danger w-100 remove-supplier"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </div>

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

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const componentsContainer =
                document.getElementById('componentsContainer');

            const componentTemplate =
                document.getElementById('componentTemplate');

            const supplierTemplate =
                document.getElementById('supplierTemplate');

            const addComponentButton =
                document.getElementById('addComponent');

            function addComponent() {

                const clone =
                    componentTemplate.content.cloneNode(true);

                const card =
                    clone.querySelector('.component-card');

                componentsContainer.appendChild(card);

                refreshNames();
            }

            function addSupplier(componentCard) {

                const container =
                    componentCard.querySelector(
                        '.suppliers-container'
                    );

                const clone =
                    supplierTemplate.content.cloneNode(true);

                container.appendChild(
                    clone.querySelector('.supplier-row')
                );

                refreshNames();
            }

            function refreshNames() {

                const components =
                    componentsContainer.querySelectorAll(
                        '.component-card'
                    );

                components.forEach((component, componentIndex) => {

                    component.querySelector(
                        '.component-number'
                    ).textContent = componentIndex + 1;

                    component
                        .querySelectorAll('[data-field]')
                        .forEach(element => {

                            const field =
                                element.dataset.field;

                            if (
                                field === 'supplier_id' ||
                                field === 'unit_price' ||
                                field === 'is_default' ||
                                field === 'is_active' ||
                                field === 'note'
                            ) {
                                return;
                            }

                            element.name =
                                `components[${componentIndex}][${field}]`;
                        });

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

                });
            }

            addComponentButton.addEventListener(
                'click',
                addComponent
            );

            componentsContainer.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target.closest(
                            '.remove-component'
                        )
                    ) {

                        event.target
                            .closest('.component-card')
                            .remove();

                        refreshNames();
                    }

                    if (
                        event.target.closest(
                            '.add-supplier'
                        )
                    ) {

                        addSupplier(
                            event.target.closest(
                                '.component-card'
                            )
                        );
                    }

                    if (
                        event.target.closest(
                            '.remove-supplier'
                        )
                    ) {

                        event.target
                            .closest('.supplier-row')
                            .remove();

                        refreshNames();
                    }

                }
            );

            /*
             * حداقل یک قطعه
             */
            addComponent();

        });

    </script>

</x-admin-layout>
