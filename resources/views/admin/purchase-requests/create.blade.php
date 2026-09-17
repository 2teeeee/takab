<x-admin-layout title="ایجاد درخواست قیمت">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    ایجاد درخواست قیمت
                </h4>

                <div class="text-muted small">
                    انتخاب تأمین‌کنندگان برای قطعه
                </div>
            </div>

            <a href="{{ route(
                'admin.production-plans.show',
                $productionRequirement->production_plan_id
            ) }}"
               class="btn btn-sm btn-outline-secondary">

                <i class="bi bi-arrow-right"></i>
                بازگشت

            </a>

        </div>


        {{-- Requirement --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    اطلاعات نیازمندی
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            دستگاه تولیدی
                        </div>

                        <div class="fw-bold">
                            {{ $productionRequirement
                                ->productionPlan
                                ->product
                                ?->translation
                                ?->title ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            قطعه / ماده
                        </div>

                        <div class="fw-bold">
                            {{ $productionRequirement
                                ->componentProduct
                                ?->translation
                                ?->title ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            نیاز به خرید
                        </div>

                        <div class="fw-bold text-danger">

                            {{ rtrim(
                                rtrim(
                                    number_format(
                                        $productionRequirement->purchase_quantity,
                                        4,
                                        '.',
                                        ''
                                    ),
                                    '0'
                                ),
                                '.'
                            ) }}

                            {{ $productionRequirement->unit }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <form method="POST"
              action="{{ route(
                  'admin.purchase-requests.store'
              ) }}">

            @csrf

            <input type="hidden"
                   name="production_requirement_id"
                   value="{{ $productionRequirement->id }}">


            {{-- Suppliers --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        انتخاب تأمین‌کنندگان
                    </h5>

                </div>

                <div class="card-body">

                    <div class="alert alert-info">

                        <i class="bi bi-info-circle"></i>

                        می‌توانید یک یا چند تأمین‌کننده را
                        برای دریافت قیمت انتخاب کنید.

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            تأمین‌کنندگان
                            <span class="text-danger">*</span>
                        </label>

                        <select name="supplier_ids[]"
                                id="supplier_ids"
                                class="form-select"
                                multiple
                                required>

                            @foreach($suppliers as $supplier)

                                <option value="{{ $supplier->id }}"
                                        @selected(
                                            in_array(
                                                $supplier->id,
                                                old(
                                                    'supplier_ids',
                                                    []
                                                )
                                            )
                                        )>

                                    {{ $supplier->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('supplier_ids')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Deadline --}}
                    <div class="mb-3">

                        <label class="form-label">
                            مهلت اعلام قیمت
                        </label>

                        <input type="text"
                               name="deadline"
                               class="form-control"
                               data-jdp
                               data-jdp-only-date
                               value="{{ old('deadline') }}"
                               placeholder="مهلت اعلام قیمت"
                               required>

                        @error('deadline')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Note --}}
                    <div class="mb-3">

                        <label class="form-label">
                            توضیحات
                        </label>

                        <textarea name="note"
                                  rows="4"
                                  class="form-control"
                                  placeholder="توضیحات مربوط به درخواست قیمت...">{{ old('note') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.production-plans.show',
                    $productionRequirement->production_plan_id
                ) }}"
                   class="btn btn-sm btn-outline-secondary">

                    انصراف

                </a>

                <button type="submit"
                        class="btn btn-sm btn-primary">

                    <i class="bi bi-send"></i>

                    ایجاد درخواست قیمت

                </button>

            </div>

        </form>

    </div>


    @push('scripts')

        <script>
            $(function () {

                $('#supplier_ids').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dir: 'rtl',
                    placeholder: 'تأمین‌کنندگان را انتخاب کنید',
                    allowClear: true,
                    language: {
                        noResults: function () {
                            return 'تأمین‌کننده‌ای پیدا نشد';
                        }
                    }
                });

            });
        </script>

    @endpush

</x-admin-layout>
