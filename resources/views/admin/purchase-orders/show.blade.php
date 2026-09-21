<x-admin-layout title="جزئیات سفارش خرید">

    @php
        $statusLabels = [
            'pending' => 'در انتظار ارسال',
            'confirmed' => 'تأیید شده',
            'sent' => 'ارسال شده',
            'partially_received' => 'دریافت ناقص',
            'received' => 'دریافت کامل',
            'cancelled' => 'لغو شده',
        ];

        $statusClasses = [
            'pending' => 'bg-secondary',
            'confirmed' => 'bg-primary',
            'sent' => 'bg-info',
            'partially_received' => 'bg-warning text-dark',
            'received' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];

        $statusLabel =
            $statusLabels[$purchaseOrder->status]
            ?? $purchaseOrder->status;

        $statusClass =
            $statusClasses[$purchaseOrder->status]
            ?? 'bg-secondary';
    @endphp

    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    سفارش خرید
                </h4>

                <div class="text-muted">
                    {{ $purchaseOrder->order_number }}
                </div>
            </div>

            <div class="d-flex gap-2">

                <span class="badge {{ $statusClass }} fs-6 px-3 py-2">
                    {{ $statusLabel }}
                </span>

                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-right"></i>
                    بازگشت
                </a>

            </div>

        </div>


        {{-- اطلاعات کلی سفارش --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i>
                    اطلاعات سفارش
                </h6>
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
                            تاریخ سفارش
                        </div>

                        <div>
                            {{ $purchaseOrder->order_date
                                ? jdate($purchaseOrder->order_date)->format('Y/m/d')
                                : '—'
                            }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-muted small">
                            تاریخ تحویل مورد انتظار
                        </div>

                        <div>
                            {{ $purchaseOrder->expected_delivery_date
                                ? jdate($purchaseOrder->expected_delivery_date)->format('Y/m/d')
                                : '—'
                            }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-muted small">
                            مبلغ کل
                        </div>

                        <div class="fw-bold">
                            {{ number_format($purchaseOrder->total_amount) }}
                            تومان
                        </div>
                    </div>

                </div>

            </div>
        </div>


        {{-- تأمین کننده --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-person-badge"></i>
                    اطلاعات تأمین‌کننده
                </h6>
            </div>

            <div class="card-body">

                @if($purchaseOrder->supplier)

                    <div class="row g-4">

                        <div class="col-md-4">
                            <div class="text-muted small">
                                نام تأمین‌کننده
                            </div>

                            <div class="fw-bold">
                                {{ $purchaseOrder->supplier->name
                                    ?? $purchaseOrder->supplier->full_name
                                    ?? '—'
                                }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">
                                موبایل
                            </div>

                            <div>
                                {{ $purchaseOrder->supplier->mobile
                                    ?? $purchaseOrder->supplier->phone
                                    ?? '—'
                                }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">
                                شناسه کاربر
                            </div>

                            <div>
                                {{ $purchaseOrder->supplier->id }}
                            </div>
                        </div>

                    </div>

                @else

                    <div class="text-muted">
                        اطلاعات تأمین‌کننده موجود نیست.
                    </div>

                @endif

            </div>
        </div>


        {{-- برنامه تولید --}}
        @if($purchaseOrder->productionPlan)

            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-gear"></i>
                        برنامه تولید مرتبط
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">
                            <div class="text-muted small">
                                محصول
                            </div>

                            <div class="fw-bold">
                                {{ $purchaseOrder->productionPlan->product->translation->title
                                    ?? '—'
                                }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">
                                تعداد تولید
                            </div>

                            <div>
                                {{ number_format(
                                    $purchaseOrder->productionPlan->quantity
                                ) }}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="text-muted small">
                                شروع
                            </div>

                            <div>
                                {{ $purchaseOrder->productionPlan->start_date
                                    ? jdate(
                                        $purchaseOrder->productionPlan->start_date
                                    )->format('Y/m/d')
                                    : '—'
                                }}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="text-muted small">
                                پایان
                            </div>

                            <div>
                                {{ $purchaseOrder->productionPlan->end_date
                                    ? jdate(
                                        $purchaseOrder->productionPlan->end_date
                                    )->format('Y/m/d')
                                    : '—'
                                }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        @endif


        {{-- اقلام سفارش --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between">

                <h6 class="mb-0">
                    <i class="bi bi-box-seam"></i>
                    اقلام سفارش
                </h6>

                <span class="text-muted">
                    {{ $purchaseOrder->items->count() }} قلم
                </span>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>قطعه</th>
                            <th>مقدار سفارش</th>
                            <th>دریافت شده</th>
                            <th>باقی‌مانده</th>
                            <th>واحد</th>
                            <th>قیمت واحد</th>
                            <th>مبلغ</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($purchaseOrder->items as $index => $item)

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
                                    {{ $index + 1 }}
                                </td>

                                <td>

                                    <div class="fw-bold">
                                        {{ $item->componentProduct->translation->title
                                            ?? '—'
                                        }}
                                    </div>

                                    @if($item->note)
                                        <div class="small text-muted">
                                            {{ $item->note }}
                                        </div>
                                    @endif

                                </td>

                                <td>
                                    {{ number_format(
                                        $ordered,
                                        4
                                    ) }}
                                </td>

                                <td>
                                    {{ number_format(
                                        $received,
                                        4
                                    ) }}
                                </td>

                                <td>

                                    @if($remaining > 0)

                                        <span class="text-danger fw-bold">
                                                {{ number_format(
                                                    $remaining,
                                                    4
                                                ) }}
                                            </span>

                                    @else

                                        <span class="text-success">
                                                0
                                            </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $item->unit ?: '—' }}
                                </td>

                                <td>
                                    {{ number_format(
                                        $item->unit_price
                                    ) }}
                                    تومان
                                </td>

                                <td class="fw-bold">
                                    {{ number_format(
                                        $item->total_price
                                    ) }}
                                    تومان
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center py-4 text-muted">
                                    هیچ قلمی برای این سفارش ثبت نشده است.
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                        @if($purchaseOrder->items->isNotEmpty())

                            <tfoot class="table-light">

                            <tr>

                                <th colspan="7"
                                    class="text-end">
                                    مبلغ کل:
                                </th>

                                <th>
                                    {{ number_format(
                                        $purchaseOrder->total_amount
                                    ) }}
                                    تومان
                                </th>

                            </tr>

                            </tfoot>

                        @endif

                    </table>

                </div>

            </div>
        </div>


        {{-- اطلاعات ارسال --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0">
                    <i class="bi bi-truck"></i>
                    اطلاعات ارسال
                </h6>

            </div>

            <div class="card-body">

                @if($purchaseOrder->shipped_at)

                    <div class="row g-4">

                        <div class="col-md-3">

                            <div class="text-muted small">
                                تاریخ ارسال
                            </div>

                            <div class="fw-bold">
                                {{ jdate(
                                    $purchaseOrder->shipped_at
                                )->format('Y/m/d') }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                نوع خودرو
                            </div>

                            <div>
                                {{ $purchaseOrder->vehicle_type ?: '—' }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                پلاک خودرو
                            </div>

                            <div class="fw-bold">
                                {{ $purchaseOrder->vehicle_plate ?: '—' }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                نام راننده
                            </div>

                            <div>
                                {{ $purchaseOrder->driver_name ?: '—' }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                موبایل راننده
                            </div>

                            <div>
                                {{ $purchaseOrder->driver_mobile ?: '—' }}
                            </div>

                        </div>

                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                توضیحات ارسال
                            </div>

                            <div class="bg-light rounded p-3">
                                {!! nl2br(
                                    e(
                                        $purchaseOrder->shipping_note
                                        ?: 'توضیحی ثبت نشده است.'
                                    )
                                ) !!}
                            </div>

                        </div>

                    </div>

                @else

                    <div class="alert alert-secondary mb-0">

                        <i class="bi bi-info-circle"></i>

                        اطلاعات ارسال هنوز توسط تأمین‌کننده ثبت نشده است.

                    </div>

                @endif

            </div>
        </div>


        {{-- وضعیت دریافت --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0">
                    <i class="bi bi-box-arrow-in-down"></i>
                    وضعیت دریافت کالا
                </h6>

            </div>

            <div class="card-body">

                @php
                    $totalOrdered = $purchaseOrder->items->sum(
                        fn ($item) => (float) $item->quantity
                    );

                    $totalReceived = $purchaseOrder->items->sum(
                        fn ($item) => (float) $item->received_quantity
                    );

                    $receivePercent = $totalOrdered > 0
                        ? min(
                            100,
                            ($totalReceived / $totalOrdered) * 100
                        )
                        : 0;
                @endphp

                <div class="d-flex justify-content-between mb-2">

                    <span>
                        پیشرفت دریافت
                    </span>

                    <strong>
                        {{ number_format(
                            $receivePercent,
                            1
                        ) }}%
                    </strong>

                </div>

                <div class="progress mb-4"
                     style="height: 20px;">

                    <div class="progress-bar
                                {{ $receivePercent >= 100
                                    ? 'bg-success'
                                    : 'bg-info' }}"
                         role="progressbar"
                         style="width: {{ $receivePercent }}%;">

                        {{ number_format(
                            $receivePercent,
                            1
                        ) }}%

                    </div>

                </div>

                <div class="row text-center">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            کل سفارش
                        </div>

                        <div class="fs-5 fw-bold">
                            {{ number_format(
                                $totalOrdered,
                                4
                            ) }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            دریافت شده
                        </div>

                        <div class="fs-5 fw-bold text-success">
                            {{ number_format(
                                $totalReceived,
                                4
                            ) }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            باقی‌مانده
                        </div>

                        <div class="fs-5 fw-bold text-danger">
                            {{ number_format(
                                max(
                                    $totalOrdered - $totalReceived,
                                    0
                                ),
                                4
                            ) }}
                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- تاریخچه دریافت --}}
        @if($purchaseOrder->receipts->isNotEmpty())

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h6 class="mb-0">
                        <i class="bi bi-clock-history"></i>
                        سوابق دریافت
                    </h6>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>شماره رسید</th>
                                <th>تاریخ</th>
                                <th>ثبت‌کننده</th>
                                <th>توضیحات</th>
                            </tr>

                            </thead>

                            <tbody>

                            @foreach(
                                $purchaseOrder->receipts
                                as $index => $receipt
                            )

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="fw-bold">
                                        {{ $receipt->receipt_number }}
                                    </td>

                                    <td>
                                        {{ jdate(
                                            $receipt->receipt_date
                                        )->format('Y/m/d') }}
                                    </td>

                                    <td>
                                        {{ $receipt->receiver->name
                                            ?? $receipt->receiver->full_name
                                            ?? '—'
                                        }}
                                    </td>

                                    <td>
                                        {{ $receipt->note ?: '—' }}
                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @endif


        {{-- توضیحات سفارش --}}
        @if($purchaseOrder->note)

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h6 class="mb-0">
                        <i class="bi bi-chat-left-text"></i>
                        توضیحات سفارش
                    </h6>

                </div>

                <div class="card-body">

                    {!! nl2br(e($purchaseOrder->note)) !!}

                </div>

            </div>

        @endif


        {{-- عملیات --}}
        <div class="card shadow-sm">

            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
{{--

                    @if($purchaseOrder->status === 'pending')

                        <form method="POST"
                              action="{{ route(
                                  'admin.purchase-orders.confirm',
                                  $purchaseOrder
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary"
                                    onclick="return confirm('این سفارش تأیید شود؟')">

                                <i class="bi bi-check-circle"></i>
                                تأیید سفارش

                            </button>

                        </form>

                    @endif

--}}
{{--
                    @if(
                        in_array(
                            $purchaseOrder->status,
                            ['sent', 'partially_received'],
                            true
                        )
                    )

                        <a href="{{ route(
                            'admin.purchase-orders.receipts.create',
                            $purchaseOrder
                        ) }}"
                           class="btn btn-success">

                            <i class="bi bi-box-arrow-in-down"></i>
                            ثبت دریافت کالا

                        </a>

                    @endif
                    --}}

                </div>
            </div>

        </div>

    </div>

</x-admin-layout>
