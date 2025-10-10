<x-profile-layout title="سفارش‌های من">

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>تاریخ سفارش</th>
                <th>مبلغ کل</th>
                <th>وضعیت</th>
                <th>کد تراکنش</th>
                <th>جزئیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ jdate($order->created_at)->format('Y/m/d - H:i') }}</td>
                    <td>{{ number_format($order->total) }} تومان</td>
                    <td>
                        @php
                            $badgeClass = match($order->status) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'canceled' => 'danger',
                                default => 'secondary'
                            };
                            $statusLabel = match($order->status) {
                                'paid' => 'پرداخت‌شده',
                                'pending' => 'در انتظار پرداخت',
                                'canceled' => 'لغوشده',
                                default => 'نامشخص'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>{{ $order->transaction_id ?? '-' }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#orderModal{{ $order->id }}">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>

                <!-- Modal جزئیات -->
                <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
                     aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                                    جزئیات سفارش #{{ $order->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <strong>تاریخ:</strong> {{ jdate($order->created_at)->format('Y/m/d - H:i') }} <br>
                                    <strong>آدرس:</strong> {{ $order->address }} <br>
                                    <strong>مبلغ کل:</strong> {{ number_format($order->total) }} تومان <br>
                                    <strong>وضعیت:</strong> {{ $statusLabel }} <br>
                                    <strong>کد تراکنش:</strong> {{ $order->transaction_id ?? '-' }}
                                </div>

                                <h6 class="mt-4 mb-2">اقلام سفارش:</h6>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr class="table-light">
                                        <th>محصول</th>
                                        <th>تعداد</th>
                                        <th>قیمت واحد</th>
                                        <th>مجموع</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->title ?? '-' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price) }} تومان</td>
                                            <td>{{ number_format($item->total) }} تومان</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">هیچ سفارشی ثبت نشده است.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>

</x-profile-layout>
