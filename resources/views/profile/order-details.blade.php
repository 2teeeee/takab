<x-profile-layout title="جزئیات سفارش">

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">جزئیات سفارش #{{ $order->id }}</h4>

            <a href="{{ route('profile.orders') }}" class="btn btn-secondary btn-sm">
                بازگشت
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">وضعیت سفارش:</div>
                    <div class="col-md-9">
                        @if ($order->status === 'paid' || $order->status === 'success')
                            <span class="badge bg-success">پرداخت شده</span>
                        @elseif($order->status === 'canceled')
                            <span class="badge bg-danger">لغو شده</span>
                        @else
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">مبلغ کل:</div>
                    <div class="col-md-9">{{ number_format($order->total) }} تومان</div>
                </div>

                @if($order->authority)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">کد Authority:</div>
                        <div class="col-md-9">{{ $order->authority }}</div>
                    </div>
                @endif

                @if($order->reference_id)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">کد رهگیری (Ref ID):</div>
                        <div class="col-md-9 text-primary fw-bold">{{ $order->reference_id }}</div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3 fw-bold">تاریخ ثبت:</div>
                    <div class="col-md-9">{{ jdate($order->created_at)->format('Y/m/d - H:i') }}</div>
                </div>

            </div>
        </div>

        <h5 class="fw-bold mb-3">آیتم‌های سفارش</h5>

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-striped mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>محصول</th>
                        <th width="100">تعداد</th>
                        <th width="120">قیمت</th>
                        <th width="140">جمع کل</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->product->title ?? 'محصول حذف شده' }}
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price) }}</td>
                            <td class="fw-bold">{{ number_format($item->total) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</x-profile-layout>
