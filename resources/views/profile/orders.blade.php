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
                    <td>{{ $order->reference_id ?? '-' }}</td>
                    <td>
                        <a href="{{ route('profile.order.details', ['id'=>$order->id]) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
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
