<x-admin-layout title="برنامه‌ریزی تولید">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    <i class="bi bi-gear-wide-connected"></i>
                    برنامه‌ریزی تولید
                </h4>

                <div class="text-muted">
                    میزان تولید دستگاه و بازه زمانی تولید را مشخص کنید.
                </div>
            </div>

            <a href="{{ route('admin.production-plans.index') }}"
               class="btn btn-sm btn-outline-secondary">

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


        <form method="POST"
              action="{{ route('admin.production-plans.store') }}">

            @csrf


            <div class="card shadow-sm">

                <div class="card-header bg-white">

                    <strong>
                        <i class="bi bi-calendar2-plus"></i>
                        مشخصات تولید
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Product --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                دستگاه
                                <span class="text-danger">*</span>
                            </label>

                            <select name="product_id"
                                    id="product_id"
                                    class="form-select"
                                    required>

                                <option value=""></option>

                                @foreach($products as $product)

                                    <option value="{{ $product->id }}"
                                            @selected(old('product_id') == $product->id)>

                                        {{ $product->title }}

                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                فقط دستگاه‌هایی که فرمول فعال دارند نمایش داده می‌شوند.
                            </div>

                        </div>


                        {{-- Quantity --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                میزان تولید
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="quantity"
                                       class="form-control"
                                       min="1"
                                       value="{{ old('quantity') }}"
                                       placeholder="مثلاً 500"
                                       required>

                                <span class="input-group-text">
                                    دستگاه
                                </span>

                            </div>

                        </div>


                        {{-- Start --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                تاریخ شروع تولید
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="start_date"
                                   class="form-control"
                                   data-jdp
                                   data-jdp-only-date
                                   value="{{ old('start_date') }}"
                                   placeholder="تاریخ شروع"
                                   required>

                        </div>


                        {{-- End --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                تاریخ پایان تولید
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="end_date"
                                   class="form-control"
                                   data-jdp
                                   data-jdp-only-date
                                   value="{{ old('end_date') }}"
                                   placeholder="تاریخ پایان"
                                   required>

                        </div>


                        {{-- Note --}}
                        <div class="col-12">

                            <label class="form-label">
                                توضیحات
                            </label>

                            <textarea name="note"
                                      class="form-control"
                                      rows="4"
                                      placeholder="توضیحات مربوط به برنامه تولید...">{{ old('note') }}</textarea>

                        </div>

                    </div>

                    {{-- Materials Preview --}}
                    <div id="materialsPreview"
                         class="mt-4 d-none">

                        <div class="border rounded">

                            <div class="bg-light border-bottom p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <strong>
                                        <i class="bi bi-box-seam"></i>
                                        پیش‌نمایش مواد اولیه
                                    </strong>

                                    <span id="previewProductName"
                                          class="text-muted">
                </span>

                                </div>

                            </div>


                            <div class="p-3">

                                {{-- Loading --}}
                                <div id="previewLoading"
                                     class="text-center py-4 d-none">

                                    <div class="spinner-border text-primary mb-2"
                                         role="status">
                                    </div>

                                    <div>
                                        در حال محاسبه مواد اولیه...
                                    </div>

                                </div>


                                {{-- Error --}}
                                <div id="previewError"
                                     class="alert alert-danger d-none">
                                </div>


                                {{-- Table --}}
                                <div id="previewContent"
                                     class="table-responsive d-none">

                                    <table class="table table-bordered table-hover align-middle mb-0">

                                        <thead class="table-light">

                                        <tr>

                                            <th>
                                                #
                                            </th>

                                            <th>
                                                ماده اولیه
                                            </th>

                                            <th class="text-center">
                                                مقدار / دستگاه
                                            </th>

                                            <th class="text-center">
                                                تولید
                                            </th>

                                            <th class="text-center">
                                                نیاز کل
                                            </th>

                                            <th>
                                                واحد
                                            </th>

                                            <th class="text-end">
                                                قیمت واحد
                                            </th>

                                            <th class="text-end">
                                                هزینه تقریبی
                                            </th>

                                            <th>
                                                تأمین‌کننده
                                            </th>

                                        </tr>

                                        </thead>


                                        <tbody id="previewTableBody">
                                        </tbody>


                                        <tfoot class="table-light">

                                        <tr>

                                            <th colspan="7"
                                                class="text-end">

                                                مجموع تقریبی مواد اولیه:

                                            </th>

                                            <th class="text-end"
                                                id="previewTotalCost">

                                                0

                                            </th>

                                            <th>
                                                تومان
                                            </th>

                                        </tr>

                                        </tfoot>

                                    </table>

                                </div>


                                {{-- Empty --}}
                                <div id="previewEmpty"
                                     class="alert alert-warning d-none mb-0">

                                    برای این دستگاه ماده اولیه فعالی در فرمول تعریف نشده است.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer bg-white
                            d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.production-plans.index') }}"
                       class="btn btn-sm btn-secondary">

                        انصراف

                    </a>

                    <button type="submit"
                            class="btn btn-sm btn-primary">

                        <i class="bi bi-check-lg"></i>

                        ثبت برنامه تولید

                    </button>

                </div>

            </div>

        </form>

    </div>


    @push('scripts')

        <script>

            $(function () {

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
            });


            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    jalaliDatepicker.startWatch({

                        date: true,

                        time: false,

                        persianDigits: true

                    });

                }
            );

            function formatNumber(number) {

                return new Intl.NumberFormat('fa-IR')
                    .format(number);
            }


            function escapeHtml(value) {

                if (value === null || value === undefined) {
                    return '';
                }

                return $('<div>')
                    .text(value)
                    .html();
            }

            let previewTimer = null;

            function loadMaterialsPreview() {

                const productId =
                    $('#product_id').val();

                const quantity =
                    $('input[name="quantity"]').val();


                const $preview =
                    $('#materialsPreview');


                /*
                 * اگر اطلاعات کامل نیست، Preview را مخفی کن.
                 */
                if (!productId || !quantity || Number(quantity) < 1) {

                    $preview.addClass('d-none');

                    return;
                }


                clearTimeout(previewTimer);


                previewTimer = setTimeout(function () {

                    $preview.removeClass('d-none');


                    $('#previewLoading')
                        .removeClass('d-none');


                    $('#previewError')
                        .addClass('d-none')
                        .text('');


                    $('#previewContent')
                        .addClass('d-none');


                    $('#previewEmpty')
                        .addClass('d-none');


                    $.ajax({

                        url:
                            '{{ route('admin.production-plans.preview') }}',

                        method: 'GET',

                        data: {
                            product_id: productId,
                            quantity: quantity
                        },


                        success: function (response) {

                            $('#previewLoading')
                                .addClass('d-none');


                            $('#previewProductName')
                                .text(response.product.title);


                            const requirements =
                                response.requirements;


                            if (!requirements.length) {

                                $('#previewEmpty')
                                    .removeClass('d-none');

                                return;
                            }


                            let html = '';


                            requirements.forEach(function (item, index) {

                                const supplier =
                                    item.default_supplier
                                        ? escapeHtml(item.default_supplier)
                                        : '<span class="text-muted">تعریف نشده</span>';


                                html += `

                        <tr>

                            <td>
                                ${index + 1}
                            </td>


                            <td>
                                <strong>
                                    ${escapeHtml(item.component_name)}
                                </strong>
                            </td>


                            <td class="text-center">

                                ${formatNumber(item.required_per_unit)}

                            </td>


                            <td class="text-center">

                                ${formatNumber(item.production_quantity)}

                            </td>


                            <td class="text-center fw-bold">

                                ${formatNumber(item.required_quantity)}

                            </td>


                            <td>

                                ${escapeHtml(item.unit || '-')}

                            </td>


                            <td class="text-end">

                                ${formatNumber(item.unit_price)}

                            </td>


                            <td class="text-end">

                                ${formatNumber(item.estimated_cost)}

                            </td>


                            <td>

                                ${supplier}

                            </td>

                        </tr>

                    `;

                            });


                            $('#previewTableBody')
                                .html(html);


                            $('#previewTotalCost')
                                .text(
                                    formatNumber(
                                        response.total_estimated_cost
                                    )
                                );


                            $('#previewContent')
                                .removeClass('d-none');

                        },


                        error: function (xhr) {

                            $('#previewLoading')
                                .addClass('d-none');


                            let message =
                                'خطا در محاسبه مواد اولیه.';


                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.message
                            ) {

                                message =
                                    xhr.responseJSON.message;

                            }


                            $('#previewError')
                                .removeClass('d-none')
                                .text(message);

                        }

                    });

                }, 300);
            }

            $('#product_id').on(
                'change',
                function () {

                    loadMaterialsPreview();

                }
            );

            $('input[name="quantity"]').on(
                'input',
                function () {

                    loadMaterialsPreview();

                }
            );


        </script>

    @endpush

</x-admin-layout>
