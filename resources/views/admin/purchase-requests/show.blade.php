<x-admin-layout title="جزئیات درخواست قیمت">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    جزئیات درخواست قیمت
                </h4>

                <div class="text-muted small">
                    درخواست شماره #{{ $purchaseRequest->id }}
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


        {{-- Request --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    اطلاعات درخواست
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            قطعه
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseRequest
                                ->productionRequirement
                                ->componentProduct
                                ?->translation
                                ?->title ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            دستگاه
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseRequest
                                ->productionRequirement
                                ->productionPlan
                                ->product
                                ?->translation
                                ?->title ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            تأمین‌کننده
                        </div>

                        <div class="fw-bold">
                            {{ $purchaseRequest
                                ->supplier
                                ?->name ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            مقدار مورد نیاز
                        </div>

                        <div class="fw-bold">
                            {{ number_format(
                                $purchaseRequest->quantity,
                                2
                            ) }}
                            {{ $purchaseRequest->unit }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            تاریخ درخواست
                        </div>

                        <div>
                            {{ jdate($purchaseRequest
                                ->requested_at->setTimezone('Asia/Tehran')
                                )->format('Y/m/d H:i') ?? '-' }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            مهلت پاسخ
                        </div>

                        <div>
                            {{ jdate($purchaseRequest
                                ->deadline
                                )->format('Y/m/d') ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Quotes --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    پیشنهاد قیمت تأمین‌کننده
                </h5>

            </div>

            <div class="card-body p-0">

                @if($purchaseRequest->quotes->count())

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                            <tr>

                                <th>
                                    قیمت واحد
                                </th>

                                <th>
                                    تعداد قابل تأمین
                                </th>

                                <th>
                                    قیمت کل
                                </th>

                                <th>
                                    زمان تحویل
                                </th>

                                <th>
                                    تاریخ
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            @foreach($purchaseRequest->quotes as $quote)

                                <tr>

                                    <td>
                                        {{ number_format(
                                            $quote->unit_price
                                        ) }}
                                        تومان
                                    </td>

                                    <td>
                                        {{ number_format(
                                            $quote->available_quantity,
                                            2
                                        ) }}
                                    </td>

                                    <td>
                                        {{ number_format(
                                            $quote->total_price
                                        ) }}
                                        تومان
                                    </td>

                                    <td>
                                        {{ $quote->delivery_days
                                            ?? '-' }}
                                        روز
                                    </td>

                                    <td>
                                        {{ $quote->quoted_at
                                            ?->format('Y/m/d H:i') }}
                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center text-muted py-5">

                        هنوز پیشنهادی ثبت نشده است.

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-admin-layout>
