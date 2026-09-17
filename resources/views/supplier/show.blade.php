<x-profile-layout title="اعلام قیمت">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    درخواست قیمت
                </h4>

                <div class="text-muted small">
                    شماره درخواست #{{ $purchaseRequest->id }}
                </div>

            </div>

            <a href="{{ route(
                'supplier.purchase-requests.index'
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-right"></i>
                بازگشت

            </a>

        </div>


        {{-- Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    اطلاعات درخواست
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="text-muted small">
                            دستگاه تولیدی
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseRequest
                                ->productionRequirement
                                ->productionPlan
                                ->product
                                ?->title ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="text-muted small">
                            قطعه / ماده
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseRequest
                                ->productionRequirement
                                ->componentProduct
                                ?->title ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="text-muted small">
                            مقدار مورد نیاز
                        </div>

                        <div class="fw-bold text-primary">

                            {{ number_format(
                                $purchaseRequest->quantity,
                                2
                            ) }}

                            {{ $purchaseRequest->unit }}

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="text-muted small">
                            مهلت پاسخ
                        </div>

                        <div class="fw-bold">

                            {{ jdate($purchaseRequest
                                ->deadline->setTimezone('Asia/Tehran')
                                )->format('Y/m/d') ?? 'بدون مهلت' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Existing quote --}}
        @if($purchaseRequest->quotes->count())

            <div class="alert alert-success">

                <i class="bi bi-check-circle"></i>

                شما قبلاً برای این درخواست قیمت ثبت کرده‌اید.

            </div>

            @foreach($purchaseRequest->quotes as $quote)

                <div class="card border-0 shadow-sm mb-3">

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <div class="text-muted small">
                                    قیمت واحد
                                </div>

                                <div class="fw-bold">
                                    {{ number_format(
                                        $quote->unit_price
                                    ) }}
                                    تومان
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="text-muted small">
                                    مقدار قابل تأمین
                                </div>

                                <div class="fw-bold">
                                    {{ number_format(
                                        $quote->available_quantity,
                                        2
                                    ) }}
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="text-muted small">
                                    زمان تحویل
                                </div>

                                <div class="fw-bold">
                                    {{ $quote->delivery_days ?? '-' }}
                                    روز
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        @else

            {{-- Quote form --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        اعلام قیمت پیشنهادی
                    </h5>

                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'supplier.purchase-requests.quote',
                              $purchaseRequest
                          ) }}">

                        @csrf


                        <div class="row g-3">

                            {{-- Quantity --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    مقدار قابل تأمین
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="available_quantity"
                                       class="form-control"
                                       step="0.0001"
                                       min="0.0001"
                                       value="{{ old(
                                           'available_quantity',
                                           $purchaseRequest->quantity
                                       ) }}"
                                       required>

                                @error('available_quantity')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Price --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    قیمت واحد
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="unit_price"
                                           class="form-control"
                                           min="1"
                                           value="{{ old('unit_price') }}"
                                           required>

                                    <span class="input-group-text">
                                        تومان
                                    </span>

                                </div>

                                @error('unit_price')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Delivery --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    زمان تحویل
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="delivery_days"
                                           class="form-control"
                                           min="0"
                                           value="{{ old('delivery_days') }}">

                                    <span class="input-group-text">
                                        روز
                                    </span>

                                </div>

                                @error('delivery_days')
                                <div class="text-danger small">
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
                                          rows="4"
                                          class="form-control"
                                          placeholder="توضیحات تکمیلی درباره قیمت یا شرایط تأمین...">{{ old('note') }}</textarea>

                                @error('note')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>

                        </div>


                        <div class="mt-4">

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="bi bi-check-circle"></i>

                                ثبت قیمت پیشنهادی

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    </div>

</x-profile-layout>
