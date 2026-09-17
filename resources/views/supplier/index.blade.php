<x-profile-layout title="درخواست‌های قیمت">

    <div class="container-fluid">

        <div class="mb-4">

            <h4 class="mb-1">
                درخواست‌های قیمت
            </h4>

            <div class="text-muted small">
                درخواست‌های ارسال‌شده برای شما
            </div>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                @if($purchaseRequests->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th>
                                    قطعه
                                </th>

                                <th>
                                    دستگاه
                                </th>

                                <th class="text-center">
                                    مقدار
                                </th>

                                <th class="text-center">
                                    مهلت
                                </th>

                                <th class="text-center">
                                    وضعیت
                                </th>

                                <th class="text-center">
                                    عملیات
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            @foreach($purchaseRequests as $purchaseRequest)

                                @php

                                    $statusMap = [
                                        'sent' => [
                                            'title' => 'جدید',
                                            'class' => 'bg-primary',
                                        ],

                                        'viewed' => [
                                            'title' => 'مشاهده شده',
                                            'class' => 'bg-info text-dark',
                                        ],

                                        'quoted' => [
                                            'title' => 'قیمت ثبت شده',
                                            'class' => 'bg-success',
                                        ],

                                        'expired' => [
                                            'title' => 'منقضی شده',
                                            'class' => 'bg-warning text-dark',
                                        ],

                                        'cancelled' => [
                                            'title' => 'لغو شده',
                                            'class' => 'bg-danger',
                                        ],
                                    ];

                                    $status =
                                        $statusMap[$purchaseRequest->status]
                                        ?? [
                                            'title' => $purchaseRequest->status,
                                            'class' => 'bg-secondary',
                                        ];

                                @endphp

                                <tr>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $purchaseRequest
                                                ->productionRequirement
                                                ->componentProduct
                                                ?->title ?? '-' }}
                                        </div>

                                    </td>

                                    <td>

                                        {{ $purchaseRequest
                                            ->productionRequirement
                                            ->productionPlan
                                            ->product
                                            ?->title ?? '-' }}

                                    </td>

                                    <td class="text-center">

                                        {{ number_format(
                                            $purchaseRequest->quantity,
                                            2
                                        ) }}

                                        {{ $purchaseRequest->unit }}

                                    </td>

                                    <td class="text-center">

                                        {{ jdate($purchaseRequest
                                            ->deadline->setTimezone('Asia/Tehran')
                                            )->format('Y/m/d') ?? '-' }}

                                    </td>

                                    <td class="text-center">

                                        <span class="badge {{ $status['class'] }}">
                                            {{ $status['title'] }}
                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route(
                                            'supplier.purchase-requests.show',
                                            $purchaseRequest
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-eye"></i>

                                            مشاهده

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5 text-muted">

                        <i class="bi bi-inbox fs-1"></i>

                        <div class="mt-3">
                            درخواست قیمتی برای شما ثبت نشده است.
                        </div>

                    </div>

                @endif

            </div>

            @if($purchaseRequests->hasPages())

                <div class="card-footer bg-white">

                    {{ $purchaseRequests->links() }}

                </div>

            @endif

        </div>

    </div>

</x-profile-layout>
