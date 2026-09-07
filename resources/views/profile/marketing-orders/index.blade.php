<x-profile-layout title="فروش‌های من">

    {{-- Header --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            <i class="bi bi-megaphone me-2"></i>
            فروش‌های من
        </h4>

        <p class="text-muted mb-0">
            سفارش‌هایی که از طریق شما معرفی و ثبت شده‌اند.
        </p>

    </div>


    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        {{-- Orders --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <div class="text-muted small mb-2">
                                تعداد فروش
                            </div>

                            <div class="fs-4 fw-bold">
                                {{ number_format($stats['orders']) }}
                            </div>

                        </div>

                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="bi bi-cart-check fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Products --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <div class="text-muted small mb-2">
                                تعداد محصولات فروخته‌شده
                            </div>

                            <div class="fs-4 fw-bold">
                                {{ number_format($stats['products']) }}
                            </div>

                        </div>

                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Sales --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <div class="text-muted small mb-2">
                                ارزش فروش‌ها
                            </div>

                            <div class="fs-5 fw-bold text-primary">

                                {{ number_format($stats['sales']) }}

                                <small class="text-muted">
                                    تومان
                                </small>

                            </div>

                        </div>

                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="bi bi-currency-exchange fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Orders --}}
    @if($orders->count())

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                سفارش
                            </th>

                            <th>
                                تاریخ
                            </th>

                            <th>
                                محصولات
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
                                وضعیت
                            </th>

                            <th></th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($orders as $order)

                            <tr>

                                {{-- Order --}}
                                <td class="px-4">

                                    <div class="fw-bold">
                                        #{{ $order->id }}
                                    </div>

                                </td>


                                {{-- Date --}}
                                <td>

                                    <div>
                                        {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $order->created_at->setTimezone('Asia/Tehran')->format('H:i') }}
                                    </small>

                                </td>


                                {{-- Products --}}
                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $order->items->sum('quantity') }}

                                        محصول

                                    </span>

                                </td>


                                {{-- Price --}}
                                <td>

                                    <strong>

                                        {{ number_format($order->final_total) }}

                                    </strong>

                                    <small class="text-muted">
                                        تومان
                                    </small>

                                </td>

                                <td>

                                    @php
                                        $commission = $order->commissions->first();
                                    @endphp

                                    @if($commission)

                                        <div class="fw-bold text-success">

                                            {{ number_format($commission->amount) }}

                                            <small class="text-muted">
                                                تومان
                                            </small>

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if(!$commission)

                                        <span class="badge bg-secondary">
                                            ثبت نشده
                                        </span>

                                    @else

                                        <x-status_badge status="{{ $commission->status }}" />

                                    @endif

                                </td>

                                {{-- Status --}}
                                <td>
                                    <x-status_badge status="{{ $order->status }}" />
                                </td>


                                {{-- Details --}}
                                <td class="text-end px-4">

                                    <a
                                            href="{{ route('profile.marketing-orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary"
                                    >

                                        مشاهده

                                        <i class="bi bi-chevron-left ms-1"></i>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Pagination --}}
        <div class="mt-4">

            {{ $orders->links() }}

        </div>

    @else

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div class="mb-3">

                    <i class="bi bi-megaphone fs-1 text-muted"></i>

                </div>

                <h5 class="fw-bold">
                    هنوز فروشی از طریق شما ثبت نشده است
                </h5>

                <p class="text-muted mb-0">
                    سفارش‌هایی که با معرفی شما ثبت شوند،
                    در این قسمت نمایش داده خواهند شد.
                </p>

            </div>

        </div>

    @endif

</x-profile-layout>