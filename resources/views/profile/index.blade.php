<x-profile-layout
        title="داشبورد"
>

    <div class="container py-4">

        {{-- ========================================================= --}}
        {{-- Header --}}
        {{-- ========================================================= --}}

        <div class="d-flex flex-column flex-md-row
                    justify-content-between
                    align-items-md-center
                    gap-3 mb-4">

            <div>

                <h4 class="fw-bold mb-1">
                    سلام {{ $user->name }} 👋
                </h4>

                <div class="text-muted">
                    به داشبورد کاربری خود خوش آمدید.
                </div>

            </div>


            <div class="text-muted small">

                <i class="bi bi-calendar3 me-1"></i>

                {{ jdate(now()->setTimezone('Asia/Tehran'))->format('Y/m/d') }}

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Statistics --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">

            {{-- Wallet --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-start">

                            <div>

                                <div class="text-muted small mb-2">
                                    موجودی کیف پول
                                </div>

                                <div class="fw-bold fs-5">

                                    {{ number_format($wallet?->balance ?? 0) }}

                                    <small class="fw-normal text-muted">
                                        تومان
                                    </small>

                                </div>

                            </div>


                            <div class="bg-success-subtle
                                        text-success
                                        rounded-circle
                                        p-2">

                                <i class="bi bi-wallet2 fs-5"></i>

                            </div>

                        </div>


                        <a
                                href="{{ route('wallet.index') }}"
                                class="small text-decoration-none mt-3 d-inline-block"
                        >
                            مشاهده کیف پول
                            <i class="bi bi-arrow-left ms-1"></i>
                        </a>

                    </div>

                </div>

            </div>


            {{-- My Orders --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-start">

                            <div>

                                <div class="text-muted small mb-2">
                                    سفارش‌های من
                                </div>

                                <div class="fw-bold fs-5">
                                    {{ number_format($ordersCount) }}
                                </div>

                            </div>


                            <div class="bg-primary-subtle
                                        text-primary
                                        rounded-circle
                                        p-2">

                                <i class="bi bi-bag-check fs-5"></i>

                            </div>

                        </div>


                        <a
                                href="{{ route('profile.orders.index') }}"
                                class="small text-decoration-none mt-3 d-inline-block"
                        >
                            مشاهده سفارش‌ها
                            <i class="bi bi-arrow-left ms-1"></i>
                        </a>

                    </div>

                </div>

            </div>


            {{-- Marketing Sales --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-start">

                            <div>

                                <div class="text-muted small mb-2">
                                    فروش‌های معرفی‌شده
                                </div>

                                <div class="fw-bold fs-5">
                                    {{ number_format($marketingOrdersCount) }}
                                </div>

                            </div>


                            <div class="bg-info-subtle
                                        text-info
                                        rounded-circle
                                        p-2">

                                <i class="bi bi-megaphone fs-5"></i>

                            </div>

                        </div>


                        <a
                                href="{{ route('profile.marketing-orders.index') }}"
                                class="small text-decoration-none mt-3 d-inline-block"
                        >
                            مشاهده فروش‌ها
                            <i class="bi bi-arrow-left ms-1"></i>
                        </a>

                    </div>

                </div>

            </div>


            {{-- Commission --}}
            <div class="col-12 col-sm-6 col-xl-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-start">

                            <div>

                                <div class="text-muted small mb-2">
                                    پورسانت بازاریابی
                                </div>

                                <div class="fw-bold fs-5">

                                    {{ number_format($marketingCommission) }}

                                    <small class="fw-normal text-muted">
                                        تومان
                                    </small>

                                </div>

                            </div>


                            <div class="bg-warning-subtle
                                        text-warning
                                        rounded-circle
                                        p-2">

                                <i class="bi bi-cash-coin fs-5"></i>

                            </div>

                        </div>


                        <a
                                href="{{ route('profile.marketing-orders.index') }}"
                                class="small text-decoration-none mt-3 d-inline-block"
                        >
                            جزئیات پورسانت
                            <i class="bi bi-arrow-left ms-1"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Quick Actions --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-lightning-charge text-warning"></i>

                    <span class="fw-semibold">
                        دسترسی سریع
                    </span>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Customer Sale --}}
                    <div class="col-12 col-md-6 col-xl-3">

                        <a
                                href="{{ route('profile.customer.sale.create') }}"
                                class="dashboard-action"
                        >

                            <i class="bi bi-person-plus fs-4 text-success"></i>

                            <div>

                                <div class="fw-semibold">
                                    ثبت فروش به مشتری
                                </div>

                                <small class="text-muted">
                                    ثبت سفارش جدید برای مشتری
                                </small>

                            </div>

                        </a>

                    </div>


                    {{-- Orders --}}
                    <div class="col-12 col-md-6 col-xl-3">

                        <a
                                href="{{ route('profile.orders.index') }}"
                                class="dashboard-action"
                        >

                            <i class="bi bi-bag-check fs-4 text-primary"></i>

                            <div>

                                <div class="fw-semibold">
                                    سفارش‌های من
                                </div>

                                <small class="text-muted">
                                    مشاهده سفارش‌های ثبت‌شده
                                </small>

                            </div>

                        </a>

                    </div>


                    {{-- Marketing --}}
                    <div class="col-12 col-md-6 col-xl-3">

                        <a
                                href="{{ route('profile.marketing-orders.index') }}"
                                class="dashboard-action"
                        >

                            <i class="bi bi-megaphone fs-4 text-info"></i>

                            <div>

                                <div class="fw-semibold">
                                    فروش‌های بازاریابی
                                </div>

                                <small class="text-muted">
                                    فروش‌های معرفی‌شده توسط من
                                </small>

                            </div>

                        </a>

                    </div>


                    {{-- Wallet --}}
                    <div class="col-12 col-md-6 col-xl-3">

                        <a
                                href="{{ route('wallet.index') }}"
                                class="dashboard-action"
                        >

                            <i class="bi bi-wallet2 fs-4 text-warning"></i>

                            <div>

                                <div class="fw-semibold">
                                    کیف پول
                                </div>

                                <small class="text-muted">
                                    مشاهده موجودی و تراکنش‌ها
                                </small>

                            </div>

                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Recent Data --}}
        {{-- ========================================================= --}}

        <div class="row g-4">

            {{-- ===================================================== --}}
            {{-- Recent Orders --}}
            {{-- ===================================================== --}}

            <div class="col-12 col-xl-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white
                                d-flex
                                justify-content-between
                                align-items-center
                                py-3">

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-bag text-primary"></i>

                            <span class="fw-semibold">
                                آخرین سفارش‌های من
                            </span>

                        </div>


                        <a
                                href="{{ route('profile.orders.index') }}"
                                class="small text-decoration-none"
                        >
                            همه
                        </a>

                    </div>


                    <div class="card-body p-0">

                        @forelse($recentOrders as $order)

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center
                                        px-3 py-3
                                        border-bottom">

                                <div>

                                    <div class="fw-semibold">

                                        سفارش #{{ $order->id }}

                                    </div>

                                    <small class="text-muted">

                                        {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d') }}

                                    </small>

                                </div>


                                <div class="text-end">

                                    <div class="fw-semibold">

                                        {{ number_format($order->final_total) }}

                                        <small class="text-muted">
                                            تومان
                                        </small>

                                    </div>

                                    <small>

                                        <x-status_badge status="{{ $order->status }}" />

                                    </small>

                                </div>

                            </div>

                        @empty

                            <div class="text-center text-muted py-5">

                                <i class="bi bi-bag-x fs-2"></i>

                                <div class="mt-2">
                                    هنوز سفارشی ثبت نکرده‌اید.
                                </div>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Recent Wallet --}}
            {{-- ===================================================== --}}

            <div class="col-12 col-xl-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white
                                d-flex
                                justify-content-between
                                align-items-center
                                py-3">

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-wallet2 text-success"></i>

                            <span class="fw-semibold">
                                آخرین تراکنش‌های کیف پول
                            </span>

                        </div>


                        <a
                                href="{{ route('wallet.index') }}"
                                class="small text-decoration-none"
                        >
                            همه
                        </a>

                    </div>


                    <div class="card-body p-0">

                        @forelse($recentTransactions as $transaction)

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center
                                        px-3 py-3
                                        border-bottom">

                                <div class="d-flex
                                            align-items-center
                                            gap-2">

                                    @if($transaction->type === 'credit')

                                        <div class="text-success">

                                            <i class="bi bi-arrow-down-circle fs-5"></i>

                                        </div>

                                    @else

                                        <div class="text-danger">

                                            <i class="bi bi-arrow-up-circle fs-5"></i>

                                        </div>

                                    @endif


                                    <div>

                                        <div class="fw-semibold">
                                            {{ $transaction->title }}
                                        </div>

                                        <small class="text-muted">
                                            {{ jdate($transaction->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i') }}
                                        </small>

                                    </div>

                                </div>


                                <div
                                        class="{{ $transaction->type === 'credit'
                                        ? 'text-success'
                                        : 'text-danger' }}"
                                >

                                    {{ $transaction->type === 'credit' ? '+' : '-' }}

                                    {{ number_format($transaction->amount) }}

                                    <small>
                                        تومان
                                    </small>

                                </div>

                            </div>

                        @empty

                            <div class="text-center text-muted py-5">

                                <i class="bi bi-wallet fs-2"></i>

                                <div class="mt-2">
                                    هنوز تراکنشی ثبت نشده است.
                                </div>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Marketing Sales --}}
            {{-- ===================================================== --}}

            <div class="col-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white
                                d-flex
                                justify-content-between
                                align-items-center
                                py-3">

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-megaphone text-info"></i>

                            <span class="fw-semibold">
                                آخرین فروش‌های معرفی‌شده
                            </span>

                        </div>


                        <a
                                href="{{ route('profile.marketing-orders.index') }}"
                                class="small text-decoration-none"
                        >
                            مشاهده همه
                        </a>

                    </div>


                    <div class="table-responsive">

                        <table class="table
                                      table-hover
                                      align-middle
                                      mb-0">

                            <thead class="table-light">

                            <tr>

                                <th>
                                    سفارش
                                </th>

                                <th>
                                    تاریخ
                                </th>

                                <th>
                                    مبلغ فروش
                                </th>

                                <th>
                                    پورسانت من
                                </th>

                                <th>
                                    وضعیت پورسانت
                                </th>

                                <th>
                                    وضعیت سفارش
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @forelse($recentMarketingOrders as $order)

                                @php
                                    $commission = $order->commissions->first();
                                @endphp

                                <tr>

                                    <td class="fw-semibold">
                                        #{{ $order->id }}
                                    </td>

                                    <td>
                                        {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d') }}
                                    </td>

                                    <td>
                                        {{ number_format($order->final_total) }}
                                        تومان
                                    </td>

                                    <td class="text-success fw-semibold">

                                        {{ number_format($commission?->amount ?? 0) }}

                                        تومان

                                    </td>

                                    <td>
                                        @if(!$commission)

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @else

                                            <x-status_badge status="{{ $commission->status }}" />

                                        @endif
                                    </td>

                                    <td>

                                        <x-status_badge status="{{ $order->status }}" />

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                            colspan="6"
                                            class="text-center
                                               text-muted
                                               py-5"
                                    >

                                        <i class="bi bi-megaphone fs-2"></i>

                                        <div class="mt-2">
                                            هنوز فروش معرفی‌شده‌ای ندارید.
                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    @push('styles')

        <style>

            .dashboard-action {
                display: flex;
                align-items: center;
                gap: 14px;

                height: 100%;

                padding: 16px;

                border: 1px solid var(--bs-border-color);
                border-radius: .75rem;

                text-decoration: none;
                color: inherit;

                transition:
                        background-color .2s ease,
                        border-color .2s ease,
                        transform .2s ease;
            }


            .dashboard-action:hover {
                background-color: var(--bs-light);
                border-color: var(--bs-primary);

                transform: translateY(-2px);
            }


        </style>

    @endpush

</x-profile-layout>