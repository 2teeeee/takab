<x-profile-layout
        title="جزئیات فروش من"
>

    <div class="container-fluid px-0">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-1"></i>
                {{ session('error') }}
            </div>
        @endif


        {{-- Page Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-receipt text-primary me-1"></i>
                    جزئیات فروش
                </h5>

                <div class="text-muted small">
                    سفارش شماره #{{ $order->id }}
                </div>
            </div>

            <a href="{{ route('profile.marketing-orders.index') }}"
               class="btn btn-sm btn-outline-secondary">

                <i class="bi bi-arrow-right me-1"></i>
                بازگشت به فروش‌های من

            </a>

        </div>


        {{-- Order Status --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row g-3">

                    {{-- Order Number --}}
                    <div class="col-6 col-md-3">

                        <div class="text-muted small mb-1">
                            شماره سفارش
                        </div>

                        <div class="fw-bold">
                            #{{ $order->id }}
                        </div>

                    </div>


                    {{-- Date --}}
                    <div class="col-6 col-md-3">

                        <div class="text-muted small mb-1">
                            تاریخ ثبت سفارش
                        </div>

                        <div class="fw-bold">

                            {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d') }}

                            <small class="text-muted">
                                {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('H:i') }}
                            </small>

                        </div>

                    </div>


                    {{-- Order Status --}}
                    <div class="col-6 col-md-3">

                        <div class="text-muted small mb-1">
                            وضعیت سفارش
                        </div>

                        @php
                            $statusLabels = [
                                'pending' => 'در انتظار بررسی',
                                'processing' => 'در حال پردازش',
                                'completed' => 'تکمیل شده',
                                'cancelled' => 'لغو شده',
                                'paid' => 'پرداخت شده',
                            ];

                            $statusClasses = [
                                'pending' => 'bg-warning text-dark',
                                'processing' => 'bg-info text-dark',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                'paid' => 'bg-success',
                            ];
                        @endphp

                        <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>

                    </div>


                    {{-- Total --}}
                    <div class="col-6 col-md-3">

                        <div class="text-muted small mb-1">
                            مبلغ سفارش
                        </div>

                        <div class="fw-bold text-primary">
                            {{ number_format($order->total_amount ?? $order->total) }}
                            تومان
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Customer Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-light">

                <strong>
                    <i class="bi bi-person me-1"></i>
                    اطلاعات مشتری
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            نام مشتری
                        </div>

                        <div class="fw-semibold">
                            {{ $order->user->name ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            شماره موبایل
                        </div>

                        <div>
                            {{ $order->user->mobile ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            کد ملی
                        </div>

                        <div>
                            {{ $order->user->national_code ?? '-' }}
                        </div>

                    </div>


                    @if($order->address)

                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                آدرس
                            </div>

                            <div class="bg-light border rounded p-3">

                                <i class="bi bi-geo-alt text-danger me-1"></i>

                                {{ $order->address }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Products --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-light">

                <strong>
                    <i class="bi bi-box-seam me-1"></i>
                    محصولات سفارش
                </strong>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                محصول
                            </th>

                            <th class="text-center">
                                تعداد
                            </th>

                            <th class="text-end">
                                قیمت واحد
                            </th>

                            <th class="text-end">
                                مبلغ
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($order->items as $item)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $item->product->title
                                            ?? $item->product->name
                                            ?? '-' }}
                                    </div>

                                    @if($item->product)
                                        <small class="text-muted">
                                            کد محصول:
                                            {{ $item->product->id }}
                                        </small>
                                    @endif

                                </td>

                                <td class="text-center">

                                    <span class="badge bg-light text-dark border">
                                        {{ $item->quantity }}
                                    </span>

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $item->price
                                        ?? $item->unit_price
                                        ?? 0
                                    ) }}

                                    تومان

                                </td>

                                <td class="text-end fw-semibold">

                                    {{ number_format(
                                        ($item->price
                                        ?? $item->unit_price
                                        ?? 0)
                                        * $item->quantity
                                    ) }}

                                    تومان

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-4">

                                    محصولی برای این سفارش ثبت نشده است.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Commission --}}
        @php
            $commission = $order->commissions
                ->where('user_id', auth()->id())
                ->where('type', 'referral')
                ->first();
        @endphp

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-light">

                <strong>
                    <i class="bi bi-wallet2 text-success me-1"></i>
                    پورسانت من
                </strong>

            </div>

            <div class="card-body">

                @if($commission)

                    <div class="row g-3">

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                مبلغ پورسانت
                            </div>

                            <div class="fs-5 fw-bold text-success">

                                {{ number_format($commission->amount) }}

                                تومان

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                وضعیت پورسانت
                            </div>

                            @php
                                $commissionStatusLabels = [
                                    'pending' => 'در انتظار تایید',
                                    'approved' => 'تایید شده',
                                    'paid' => 'واریز شده به کیف پول',
                                    'cancelled' => 'لغو شده',
                                ];

                                $commissionStatusClasses = [
                                    'pending' => 'bg-warning text-dark',
                                    'approved' => 'bg-info text-dark',
                                    'paid' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                ];
                            @endphp

                            <span class="badge {{ $commissionStatusClasses[$commission->status] ?? 'bg-secondary' }}">

                                {{ $commissionStatusLabels[$commission->status]
                                    ?? $commission->status }}

                            </span>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                تاریخ ثبت پورسانت
                            </div>

                            <div>

                                {{ jdate($commission->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d') }}

                                <small class="text-muted">
                                    {{ jdate($commission->created_at->setTimezone('Asia/Tehran'))->format('H:i') }}
                                </small>

                            </div>

                        </div>


                        @if($commission->paid_at)

                            <div class="col-md-4">

                                <div class="text-muted small mb-1">
                                    تاریخ واریز به کیف پول
                                </div>

                                <div class="text-success">

                                    {{ jdate($commission->paid_at->setTimezone('Asia/Tehran'))->format('Y/m/d') }}

                                    <small>
                                        {{ jdate($commission->paid_at->setTimezone('Asia/Tehran'))->format('H:i') }}
                                    </small>

                                </div>

                            </div>

                        @endif


                        @if($commission->note)

                            <div class="col-12">

                                <div class="text-muted small mb-1">
                                    توضیحات
                                </div>

                                <div class="alert alert-light border mb-0">

                                    {{ $commission->note }}

                                </div>

                            </div>

                        @endif

                    </div>

                @else

                    <div class="text-center py-3 text-muted">

                        <i class="bi bi-info-circle fs-4 d-block mb-2"></i>

                        برای این فروش هنوز پورسانتی برای شما ثبت نشده است.

                    </div>

                @endif

            </div>

        </div>


        {{-- Order Summary --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-light">

                <strong>
                    <i class="bi bi-calculator me-1"></i>
                    خلاصه سفارش
                </strong>

            </div>

            <div class="card-body">

                <div class="row justify-content-end">

                    <div class="col-md-6 col-lg-4">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                مبلغ محصولات
                            </span>

                            <span>
                                {{ number_format($order->subtotal ?? 0) }}
                                تومان
                            </span>

                        </div>


                        @if(($order->discount ?? 0) > 0)

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    تخفیف
                                </span>

                                <span class="text-danger">

                                    -
                                    {{ number_format($order->discount) }}
                                    تومان

                                </span>

                            </div>

                        @endif


                        <hr>


                        <div class="d-flex justify-content-between">

                            <span class="fw-bold">
                                مبلغ نهایی
                            </span>

                            <span class="fw-bold text-primary fs-5">

                                {{ number_format(
                                    $order->total_amount
                                    ?? $order->total
                                    ?? 0
                                ) }}

                                تومان

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-profile-layout>