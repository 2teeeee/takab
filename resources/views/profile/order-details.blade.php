<x-profile-layout title="جزئیات سفارش">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a
                        href="{{ route('profile.orders.index') }}"
                        class="text-decoration-none text-muted"
                >
                    <i class="bi bi-arrow-right"></i>
                    سفارش‌های من
                </a>
            </div>

            <h4 class="fw-bold mb-0">
                سفارش #{{ $order->id }}
            </h4>
        </div>

        <div>
            @switch($order->status)

                @case('pending')
                    <span class="badge bg-warning text-dark px-3 py-2">
                        <i class="bi bi-clock me-1"></i>
                        در انتظار بررسی
                    </span>
                    @break

                @case('approved')
                    <span class="badge bg-info px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i>
                        تأیید شده
                    </span>
                    @break

                @case('completed')
                    <span class="badge bg-success px-3 py-2">
                        <i class="bi bi-check2-circle me-1"></i>
                        تکمیل شده
                    </span>
                    @break

                @case('cancelled')
                    <span class="badge bg-danger px-3 py-2">
                        <i class="bi bi-x-circle me-1"></i>
                        لغو شده
                    </span>
                    @break

                @default
                    <span class="badge bg-secondary px-3 py-2">
                        {{ $order->status }}
                    </span>

            @endswitch
        </div>

    </div>


    {{-- Order information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h6 class="fw-bold mb-4">
                <i class="bi bi-receipt me-2"></i>
                اطلاعات سفارش
            </h6>

            <div class="row g-4">

                <div class="col-6 col-md-3">
                    <div class="text-muted small mb-1">
                        شماره سفارش
                    </div>

                    <div class="fw-bold">
                        #{{ $order->id }}
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="text-muted small mb-1">
                        تاریخ ثبت
                    </div>

                    <div class="fw-bold">
                        {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d') }}
                    </div>

                    <small class="text-muted">
                        {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('H:i') }}
                    </small>
                </div>

                <div class="col-6 col-md-3">
                    <div class="text-muted small mb-1">
                        تعداد کالا
                    </div>

                    <div class="fw-bold">
                        {{ $order->items->sum('quantity') }}
                        عدد
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="text-muted small mb-1">
                        مبلغ سفارش
                    </div>

                    <div class="fw-bold text-primary">
                        {{ number_format($order->final_total) }}
                        <small class="text-muted">تومان</small>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Products --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h6 class="fw-bold mb-4">
                <i class="bi bi-box-seam me-2"></i>
                محصولات سفارش
            </h6>

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                    <tr>
                        <th>محصول</th>
                        <th class="text-center">تعداد</th>
                        <th class="text-end">قیمت واحد</th>
                        <th class="text-end">مبلغ</th>
                    </tr>

                    </thead>

                    <tbody>

                    @foreach($order->items as $item)

                        <tr>

                            <td>
                                <div class="d-flex align-items-center gap-3">

                                    @if($item->product?->image)
                                        <img
                                                src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}"
                                                width="55"
                                                height="55"
                                                class="rounded object-fit-cover"
                                        >
                                    @else
                                        <div
                                                class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width:55px;height:55px;"
                                        >
                                            <i class="bi bi-box text-muted fs-4"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-bold">
                                            {{ optional($item->product->translation)->title
                                            ?? 'محصول حذف شده' }}
                                        </div>

                                        @if($item->product?->sku)
                                            <small class="text-muted">
                                                کد محصول:
                                                {{ $item->product->sku }}
                                            </small>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            <td class="text-end">
                                {{ number_format($item->price) }}
                                <small class="text-muted">تومان</small>
                            </td>

                            <td class="text-end fw-bold">
                                {{ number_format($item->price * $item->quantity) }}
                                <small class="text-muted">تومان</small>
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-4">

        {{-- Address --}}
        <div class="col-md-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h6 class="fw-bold mb-4">
                        <i class="bi bi-geo-alt me-2"></i>
                        آدرس تحویل
                    </h6>

                    <div class="bg-light rounded-3 p-3">

                        @if($order->address)
                            <div class="text-muted">
                                {{ $order->address }}
                            </div>
                        @else
                            <span class="text-muted">
                                آدرسی ثبت نشده است.
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Price summary --}}
        <div class="col-md-5">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="fw-bold mb-4">
                        <i class="bi bi-calculator me-2"></i>
                        خلاصه پرداخت
                    </h6>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            مجموع کالاها
                        </span>

                        <span>
                            {{number_format($order->total)}}
                            تومان
                        </span>
                    </div>

                    @if($order->discount > 0)

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                تخفیف
                            </span>

                            <span class="text-success">
                                -
                                {{ number_format($order->discount) }}
                                تومان
                            </span>

                        </div>

                    @endif

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">

                        <span class="fw-bold">
                            مبلغ نهایی
                        </span>

                        <span class="fs-5 fw-bold text-primary">

                            {{ number_format($order->final_total) }}

                            <small class="fs-6 text-muted">
                                تومان
                            </small>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Back button --}}
    <div class="mt-4">

        <a
                href="{{ route('profile.orders.index') }}"
                class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-right me-1"></i>
            بازگشت به سفارش‌ها
        </a>

    </div>

</x-profile-layout>