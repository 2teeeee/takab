<x-admin-layout title="ویرایش فرمول ساخت">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                ویرایش فرمول ساخت
            </h4>

            <div class="text-muted small">
                {{ $productBom->product?->title }}
            </div>
        </div>

        <a
                href="{{ route('admin.product-boms.show', $productBom) }}"
                class="btn btn-sm btn-outline-secondary"
        >
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
            action="{{ route('admin.product-boms.update', $productBom) }}"
    >

        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            دستگاه
                        </label>

                        <select
                                name="product_id"
                                class="form-select"
                                required
                        >

                            @foreach($products as $product)

                                <option
                                        value="{{ $product->id }}"
                                        @selected(
                                            old(
                                                'product_id',
                                                $productBom->product_id
                                            ) == $product->id
                                        )
                                >
                                    {{ $product->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            قطعه / ماده اولیه
                        </label>

                        <select
                                name="component_product_id"
                                class="form-select"
                                required
                        >

                            @foreach($products as $product)

                                <option
                                        value="{{ $product->id }}"
                                        @selected(
                                            old(
                                                'component_product_id',
                                                $productBom->component_product_id
                                            ) == $product->id
                                        )
                                >
                                    {{ $product->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            مقدار
                        </label>

                        <input
                                type="number"
                                step="0.0001"
                                min="0.0001"
                                name="quantity"
                                class="form-control"
                                value="{{ old('quantity', $productBom->quantity) }}"
                                required
                        >

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            واحد
                        </label>

                        <input
                                type="text"
                                name="unit"
                                class="form-control"
                                value="{{ old('unit', $productBom->unit) }}"
                        >

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            قیمت پایه
                        </label>

                        <input
                                type="number"
                                min="0"
                                name="unit_price"
                                class="form-control"
                                value="{{ old('unit_price', $productBom->unit_price) }}"
                                required
                        >

                    </div>

                    <div class="col-md-3">

                        <label class="form-label d-block">
                            وضعیت
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input
                                    type="hidden"
                                    name="is_active"
                                    value="0"
                            >

                            <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    @checked(
                                        old(
                                            'is_active',
                                            $productBom->is_active
                                        )
                                    )
                            >

                            <label class="form-check-label">
                                فعال
                            </label>

                        </div>

                    </div>

                    <div class="col-12">

                        <label class="form-label">
                            توضیحات
                        </label>

                        <textarea
                                name="note"
                                class="form-control"
                                rows="3"
                        >{{ old('note', $productBom->note) }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white d-flex justify-content-between">

                <strong>
                    تأمین‌کنندگان
                </strong>

                <button
                        type="button"
                        class="btn btn-sm btn-success"
                        id="addSupplier"
                >
                    <i class="bi bi-plus"></i>
                    افزودن تأمین‌کننده
                </button>

            </div>

            <div class="card-body">

                <div id="suppliersContainer">

                    @foreach(
                        old(
                            'suppliers',
                            $productBom->suppliers->map(function ($item) {
                                return [
                                    'supplier_id' => $item->supplier_id,
                                    'unit_price' => $item->unit_price,
                                    'is_default' => $item->is_default,
                                    'is_active' => $item->is_active,
                                    'note' => $item->note,
                                ];
                            })->toArray()
                        )
                        as $index => $supplier
                    )

                        <div class="supplier-row border rounded p-3 mb-3">

                            <div class="row g-3 align-items-end">

                                <div class="col-md-4">

                                    <label class="form-label">
                                        تأمین‌کننده
                                    </label>

                                    <select
                                            name="suppliers[{{ $index }}][supplier_id]"
                                            class="form-select"
                                            required
                                    >

                                        <option value="">
                                            انتخاب تأمین‌کننده
                                        </option>

                                        @foreach($suppliers as $user)

                                            <option
                                                    value="{{ $user->id }}"
                                                    @selected(
                                                        $supplier['supplier_id']
                                                        == $user->id
                                                    )
                                            >
                                                {{ $user->name }}
                                                @if($user->mobile)
                                                    - {{ $user->mobile }}
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
                                            name="suppliers[{{ $index }}][unit_price]"
                                            class="form-control"
                                            value="{{ $supplier['unit_price'] }}"
                                            required
                                    >

                                </div>

                                <div class="col-md-2">

                                    <div class="form-check">

                                        <input
                                                type="hidden"
                                                name="suppliers[{{ $index }}][is_default]"
                                                value="0"
                                        >

                                        <input
                                                type="checkbox"
                                                class="form-check-input"
                                                name="suppliers[{{ $index }}][is_default]"
                                                value="1"
                                                @checked($supplier['is_default'])
                                        >

                                        <label class="form-check-label">
                                            پیش‌فرض
                                        </label>

                                    </div>

                                </div>

                                <div class="col-md-2">

                                    <div class="form-check">

                                        <input
                                                type="hidden"
                                                name="suppliers[{{ $index }}][is_active]"
                                                value="0"
                                        >

                                        <input
                                                type="checkbox"
                                                class="form-check-input"
                                                name="suppliers[{{ $index }}][is_active]"
                                                value="1"
                                                @checked($supplier['is_active'])
                                        >

                                        <label class="form-check-label">
                                            فعال
                                        </label>

                                    </div>

                                </div>

                                <div class="col-md-1">

                                    <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger remove-supplier"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>

                                <div class="col-12">

                                    <input
                                            type="text"
                                            name="suppliers[{{ $index }}][note]"
                                            class="form-control"
                                            value="{{ $supplier['note'] }}"
                                            placeholder="توضیحات"
                                    >

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <div class="text-end">

            <button
                    type="submit"
                    class="btn btn-sm btn-primary px-5"
            >
                <i class="bi bi-check-lg"></i>
                ذخیره تغییرات
            </button>

        </div>

    </form>

    <template id="supplierTemplate">

        <div class="supplier-row border rounded p-3 mb-3">

            <div class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label class="form-label">
                        تأمین‌کننده
                    </label>

                    <select
                            class="form-select"
                            data-field="supplier_id"
                            required
                    >

                        <option value="">
                            انتخاب تأمین‌کننده
                        </option>

                        @foreach($suppliers as $user)

                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                                @if($user->mobile)
                                    - {{ $user->mobile }}
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

                <div class="col-md-2">

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

                <div class="col-md-1">

                    <button
                            type="button"
                            class="btn btn-sm btn-outline-danger remove-supplier"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </div>

                <div class="col-12">

                    <input
                            type="text"
                            class="form-control"
                            data-field="note"
                            placeholder="توضیحات"
                    >

                </div>

            </div>

        </div>

    </template>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const container =
                document.getElementById('suppliersContainer');

            const template =
                document.getElementById('supplierTemplate');

            const addButton =
                document.getElementById('addSupplier');

            function refreshNames() {

                const rows =
                    container.querySelectorAll('.supplier-row');

                rows.forEach((row, index) => {

                    row.querySelectorAll('[data-field]')
                        .forEach(element => {

                            const field =
                                element.dataset.field;

                            element.name =
                                `suppliers[${index}][${field}]`;

                        });

                });
            }

            addButton.addEventListener(
                'click',
                function () {

                    const clone =
                        template.content.cloneNode(true);

                    container.appendChild(clone);

                    refreshNames();
                }
            );

            container.addEventListener(
                'click',
                function (event) {

                    const button =
                        event.target.closest(
                            '.remove-supplier'
                        );

                    if (!button) {
                        return;
                    }

                    button
                        .closest('.supplier-row')
                        .remove();

                    refreshNames();
                }
            );

        });

    </script>

</x-admin-layout>
