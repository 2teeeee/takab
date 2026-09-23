<x-admin-layout title="درخواست تأمین جدید">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    درخواست تأمین جدید
                </h4>

                <div class="text-muted small">
                    درخواست خرید مستقیم از تأمین‌کننده
                </div>

            </div>

            <a href="{{ route(
                'admin.purchase-requests.index'
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-right"></i>
                بازگشت

            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    اطلاعات درخواست
                </h5>

            </div>


            <form method="POST"
                  action="{{ route(
                      'admin.purchase-requests.store'
                  ) }}">

                @csrf


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Supplier --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                تأمین‌کننده
                                <span class="text-danger">*</span>
                            </label>

                            <select name="supplier_id"
                                    id="supplier_id"
                                    class="form-select select2"
                                    required>

                                <option value="">
                                    انتخاب تأمین‌کننده
                                </option>

                                @foreach($suppliers as $supplier)

                                <option value="{{ $supplier->id }}"
                                        @selected(
                                        old('supplier_id') == $supplier->id
                                )>

                                {{ $supplier->name }}

                                </option>

                                @endforeach

                            </select>

                            @error('supplier_id')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Product --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                محصول / قطعه
                                <span class="text-danger">*</span>
                            </label>

                            <select name="component_product_id"
                                    id="component_product_id"
                                    class="form-select select2"
                                    required>

                                <option value="">
                                    انتخاب محصول
                                </option>

                                @foreach($products as $product)

                                <option value="{{ $product->id }}"
                                        @selected(
                                        old('component_product_id')
                                == $product->id
                                )>

                                {{ $product
                                ->translation
                                ?->title ?? '-' }}

                                </option>

                                @endforeach

                            </select>

                            @error('component_product_id')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Quantity --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                مقدار
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="quantity"
                                   class="form-control"
                                   min="0.0001"
                                   step="0.0001"
                                   value="{{ old('quantity') }}"
                                   required>

                            @error('quantity')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Unit --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                واحد
                            </label>

                            <input type="text"
                                   name="unit"
                                   class="form-control"
                                   maxlength="50"
                                   value="{{ old('unit') }}"
                                   placeholder="عدد، کیلوگرم، متر و ...">

                            @error('unit')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Deadline --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                مهلت پاسخ
                                <span class="text-danger">*</span>
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
                        <div class="col-12">

                            <label class="form-label">
                                توضیحات
                            </label>

                            <textarea name="note"
                                      class="form-control"
                                      rows="4"
                                      maxlength="5000"
                                      placeholder="توضیحات مورد نیاز برای تأمین‌کننده...">{{ old('note') }}</textarea>

                            @error('note')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>

                    </div>

                </div>


                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.purchase-requests.index'
                    ) }}"
                       class="btn btn-outline-secondary">

                        انصراف

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-send"></i>
                        ارسال درخواست

                    </button>

                </div>

            </form>

        </div>

    </div>

    @push('scripts')

        <script>

            $(function () {

                $('#component_product_id').select2({
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
                $('#supplier_id').select2({
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

        </script>

    @endpush

</x-admin-layout>
