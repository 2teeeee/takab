<x-admin-layout title="درخواست‌های قیمت">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    درخواست‌های قیمت
                </h4>

                <div class="text-muted small">
                    درخواست‌های ارسال‌شده برای تأمین‌کنندگان
                </div>
            </div>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                @if($purchaseRequests->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th class="text-center">
                                    #
                                </th>

                                <th>
                                    قطعه
                                </th>

                                <th>
                                    دستگاه
                                </th>

                                <th class="text-center">
                                    مقدار
                                </th>

                                <th>
                                    تأمین‌کننده
                                </th>

                                <th class="text-center">
                                    وضعیت
                                </th>

                                <th class="text-center">
                                    پیشنهاد قیمت
                                </th>

                                <th class="text-center">
                                    عملیات
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            @foreach($purchaseRequests as $index => $request)

                                @php

                                    $statusMap = [
                                        'pending' => [
                                            'title' => 'در انتظار ارسال',
                                            'class' => 'bg-secondary',
                                        ],

                                        'sent' => [
                                            'title' => 'ارسال شده',
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
                                        $statusMap[$request->status]
                                        ?? [
                                            'title' => $request->status,
                                            'class' => 'bg-secondary',
                                        ];

                                @endphp

                                <tr>

                                    <td class="text-center text-muted">
                                        {{ $purchaseRequests->firstItem() + $index }}
                                    </td>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $request
                                                ->productionRequirement
                                                ->componentProduct
                                                ?->translation
                                                ?->title ?? '-' }}
                                        </div>

                                    </td>

                                    <td>

                                        {{ $request
                                            ->productionRequirement
                                            ->productionPlan
                                            ->product
                                            ?->translation
                                            ?->title ?? '-' }}

                                    </td>

                                    <td class="text-center">

                                        {{ rtrim(
                                            rtrim(
                                                number_format(
                                                    $request->quantity,
                                                    4,
                                                    '.',
                                                    ''
                                                ),
                                                '0'
                                            ),
                                            '.'
                                        ) }}

                                        {{ $request->unit }}

                                    </td>

                                    <td>

                                        {{ $request->supplier?->name ?? '-' }}

                                    </td>

                                    <td class="text-center">

                                        <span class="badge {{ $status['class'] }}">
                                            {{ $status['title'] }}
                                        </span>

                                    </td>

                                    <td class="text-center">

                                        @if($request->quotes_count)

                                            <span class="badge bg-success">
                                                {{ $request->quotes_count }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                بدون پاسخ
                                            </span>

                                        @endif

                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route(
                                            'admin.purchase-requests.show',
                                            $request
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

                    <div class="text-center py-5">

                        <i class="bi bi-send fs-1 text-muted"></i>

                        <h6 class="mt-3">
                            هنوز درخواست قیمتی ثبت نشده است.
                        </h6>

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

</x-admin-layout>
