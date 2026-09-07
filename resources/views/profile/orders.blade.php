<x-profile-layout title="سفارش‌های من">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                سفارش‌های من
            </h4>

            <p class="text-muted mb-0">
                لیست سفارش‌هایی که ثبت کرده‌اید
            </p>
        </div>

        <span class="badge bg-primary rounded-pill px-3 py-2">
            {{ $orders->total() }} سفارش
        </span>
    </div>

    @if($orders->count())

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th class="px-4">شماره سفارش</th>
                            <th>تاریخ</th>
                            <th>تعداد کالا</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($orders as $order)

                            <tr>

                                <td class="px-4">
                                    <div class="fw-bold">
                                        #{{ $order->id }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $order->created_at->setTimezone('Asia/Tehran')->format('H:i') }}
                                    </small>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $order->items_count }} کالا
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($order->final_total) }}
                                    </strong>

                                    <small class="text-muted">
                                        تومان
                                    </small>
                                </td>

                                <td>
                                    <x-status_badge status="{{ $order->status }}" />
                                </td>

                                <td class="text-end px-4">

                                    <a
                                            href="{{ route('profile.orders.show', $order) }}"
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

        <div class="mt-4">
            {{ $orders->links() }}
        </div>

    @else

        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">

                <div class="mb-3">
                    <i class="bi bi-bag-x fs-1 text-muted"></i>
                </div>

                <h5 class="fw-bold">
                    هنوز سفارشی ثبت نکرده‌اید
                </h5>

                <p class="text-muted mb-4">
                    سفارش‌های خریداری‌شده شما در این قسمت نمایش داده می‌شوند.
                </p>

                <a
                        href="{{ route('home') }}"
                        class="btn btn-primary px-4"
                >
                    مشاهده محصولات
                </a>

            </div>
        </div>

    @endif

</x-profile-layout>