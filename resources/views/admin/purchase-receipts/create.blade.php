<x-admin-layout title="ثبت دریافت کالا">

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    ثبت دریافت کالا
                </h4>

                <div class="text-muted small">
                    سفارش {{ $purchaseOrder->order_number }}
                </div>

            </div>

            <a href="{{ route(
                'admin.purchase-receipts.index'
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-right"></i>
                بازگشت

            </a>

        </div>


        {{-- Order information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    اطلاعات سفارش
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            شماره سفارش
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseOrder->order_number }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            تأمین‌کننده
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseOrder
                                ->supplier
                                ?->name ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            دستگاه
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseOrder
                                ->productionPlan
                                ?->product
                                ?->translation
                                ?->title ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            تاریخ ارسال
                        </div>

                        <div class="fw-bold">

                            @if($purchaseOrder->shipped_at)

                                {{ jdate(
                                    $purchaseOrder->shipped_at
                                )->format('Y/m/d') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Shipping information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    اطلاعات حمل
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            نوع وسیله
                        </div>

                        <div>
                            {{ $purchaseOrder->vehicle_type ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            پلاک
                        </div>

                        <div>
                            {{ $purchaseOrder->vehicle_plate ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            راننده
                        </div>

                        <div>
                            {{ $purchaseOrder->driver_name ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            موبایل راننده
                        </div>

                        <div>
                            {{ $purchaseOrder->driver_mobile ?? '-' }}
                        </div>

                    </div>


                    @if($purchaseOrder->shipping_note)

                        <div class="col-12">

                            <div class="text-muted small">
                                توضیحات ارسال
                            </div>

                            <div>
                                {{ $purchaseOrder->shipping_note }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Receive form --}}
        <form method="POST"
              action="{{ route(
                  'admin.purchase-receipts.store',
                  $purchaseOrder
              ) }}">

            @csrf


            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        اقلام دریافتی
                    </h5>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th>
                                    قطعه
                                </th>

                                <th class="text-center">
                                    مقدار سفارش
                                </th>

                                <th class="text-center">
                                    قبلاً دریافت شده
                                </th>

                                <th class="text-center">
                                    باقی‌مانده
                                </th>

                                <th style="width: 180px;">
                                    مقدار دریافتی
                                </th>

                                <th>
                                    توضیح
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @foreach($purchaseOrder->items as $index => $item)

                                @php

                                    $ordered =
                                        (float) $item->quantity;

                                    $received =
                                        (float) $item->received_quantity;

                                    $remaining =
                                        max(
                                            $ordered - $received,
                                            0
                                        );

                                @endphp

                                <tr>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item
                                                ->componentProduct
                                                ?->translation
                                                ?->title
                                                ?? '-' }}

                                        </div>

                                        @if($item->unit)

                                            <div class="text-muted small">
                                                واحد: {{ $item->unit }}
                                            </div>

                                        @endif

                                        <input type="hidden"
                                               name="items[{{ $index }}][purchase_order_item_id]"
                                               value="{{ $item->id }}">

                                    </td>


                                    <td class="text-center">

                                        {{ number_format(
                                            $ordered,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format(
                                            $received,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-center">

                                        <span class="fw-bold">

                                            {{ number_format(
                                                $remaining,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    <td>

                                        @if($remaining > 0)

                                            <input type="number"
                                                   name="items[{{ $index }}][quantity]"
                                                   class="form-control receive-quantity"
                                                   min="0"
                                                   max="{{ $remaining }}"
                                                   step="0.0001"
                                                   value="">

                                        @else

                                            <span class="badge bg-success">
                                                کامل دریافت شده
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($remaining > 0)

                                            <input type="text"
                                                   name="items[{{ $index }}][note]"
                                                   class="form-control"
                                                   maxlength="1000">

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- Receipt information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        اطلاعات رسید
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <label class="form-label">
                                تاریخ دریافت
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="receipt_date"
                                   class="form-control"
                                   data-jdp
                                   data-jdp-only-date
                                   value="{{ old('receipt_date') }}"
                                   placeholder="تاریخ دریافت"
                                   required>

                            @error('receipt_date')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        <div class="col-md-8">

                            <label class="form-label">
                                توضیحات رسید
                            </label>

                            <textarea name="note"
                                      class="form-control"
                                      rows="3"
                                      maxlength="5000">{{ old('note') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.purchase-receipts.index'
                ) }}"
                   class="btn btn-outline-secondary">

                    انصراف

                </a>


                <button type="submit"
                        class="btn btn-success"
                        onclick="return confirm('دریافت کالا ثبت شود و موجودی انبار افزایش پیدا کند؟')">

                    <i class="bi bi-check-circle"></i>

                    ثبت دریافت

                </button>

            </div>

        </form>

    </div>

    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                document
                    .querySelectorAll('.receive-quantity')
                    .forEach(function (input) {

                        input.addEventListener('input', function () {

                            const max = parseFloat(
                                this.getAttribute('max')
                            );

                            const value = parseFloat(
                                this.value
                            );

                            if (!isNaN(max) && value > max) {
                                this.value = max;
                            }

                            if (value < 0) {
                                this.value = 0;
                            }

                        });

                    });

            });
        </script>

    @endpush

</x-admin-layout>
