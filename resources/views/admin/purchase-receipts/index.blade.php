<x-admin-layout title="دریافت کالا">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    دریافت کالا
                </h4>

                <div class="text-muted small">
                    سفارش‌های ارسال‌شده برای دریافت در انبار
                </div>

            </div>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                @if($purchaseOrders->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th>
                                    شماره سفارش
                                </th>

                                <th>
                                    تأمین‌کننده
                                </th>

                                <th>
                                    دستگاه
                                </th>

                                <th class="text-center">
                                    تاریخ ارسال
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

                            @foreach($purchaseOrders as $purchaseOrder)

                                @php

                                    $statusMap = [

                                        'sent' => [
                                            'title' => 'ارسال شده',
                                            'class' => 'bg-primary',
                                        ],

                                        'partially_received' => [
                                            'title' => 'دریافت ناقص',
                                            'class' => 'bg-warning text-dark',
                                        ],

                                    ];

                                    $status =
                                        $statusMap[$purchaseOrder->status]
                                        ?? [
                                            'title' => $purchaseOrder->status,
                                            'class' => 'bg-secondary',
                                        ];

                                @endphp

                                <tr>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $purchaseOrder->order_number }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $purchaseOrder->items->count() }}
                                            قلم
                                        </div>

                                    </td>


                                    <td>
                                        {{ $purchaseOrder
                                            ->supplier
                                            ?->name ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $purchaseOrder
                                            ->productionPlan
                                            ?->product
                                            ?->translation
                                            ?->title ?? '-' }}
                                    </td>


                                    <td class="text-center">

                                        @if($purchaseOrder->shipped_at)

                                            {{ jdate(
                                                $purchaseOrder->shipped_at
                                            )->format('Y/m/d') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        <span class="badge {{ $status['class'] }}">
                                            {{ $status['title'] }}
                                        </span>

                                    </td>


                                    <td class="text-center">

                                        @if(in_array(
                                            $purchaseOrder->status,
                                            ['sent', 'partially_received'],
                                            true
                                        ))

                                            <a href="{{ route(
                                                    'admin.purchase-receipts.create',
                                                    $purchaseOrder
                                                ) }}"
                                               class="btn btn-sm btn-success">

                                                <i class="bi bi-box-arrow-in-down"></i>
                                                ثبت دریافت کالا

                                            </a>

                                        @endif

                                        <a href="{{ route(
                                            'admin.purchase-orders.show',
                                            $purchaseOrder
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
                            سفارشی برای دریافت وجود ندارد.
                        </div>

                    </div>

                @endif

            </div>


            @if($purchaseOrders->hasPages())

                <div class="card-footer bg-white">

                    {{ $purchaseOrders->links() }}

                </div>

            @endif

        </div>

    </div>

</x-admin-layout>
