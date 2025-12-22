<x-admin-layout title="جزئیات سفارش" header="جزئیات سفارش">

    <div class="container py-4">

        <!-- پیام موفقیت -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>جزئیات سفارش #{{ $order->id }}</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">بازگشت</a>
        </div>

        <!-- اطلاعات اصلی سفارش -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                اطلاعات سفارش
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>کاربر:</strong><br>
                        {{ $order->user->name }}
                        <div class="text-muted small">{{ $order->user->mobile }}</div>
                    </div>

                    <!-- نمایش آدرس -->
                    <div class="col-md-8 mb-3">
                        <strong>آدرس:</strong><br>
                        {{ $order->address ?? '—' }}
                        @if($order->postal_code)
                            <div class="text-muted">کد پستی: {{ $order->postal_code }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>وضعیت سفارش:</strong><br>
                        <span class="badge bg-{{ $order->status_color }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>مبلغ کل:</strong><br>
                        {{ number_format($order->amount) }} تومان
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>شماره تراکنش:</strong><br>
                        {{ $order->reference_id ?? '—' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Authority:</strong><br>
                        {{ $order->authority ?? '—' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Reference ID:</strong><br>
                        {{ $order->reference_id ?? '—' }}
                    </div>

                </div>
            </div>
        </div>

        <!-- دکمه تغییر وضعیت -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                تغییر وضعیت سفارش
            </div>
            <div class="card-body">

                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="row gy-2 align-items-center">
                    @csrf

                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="paid"      @selected($order->status=='paid')>پرداخت شده</option>
                            <option value="processing"@selected($order->status=='processing')>در حال آماده‌سازی</option>
                            <option value="shipping"  @selected($order->status=='shipping')>ارسال شده</option>
                            <option value="delivered" @selected($order->status=='delivered')>تحویل شده</option>
                            <option value="canceled"  @selected($order->status=='canceled')>لغو شده</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-primary w-100">ذخیره وضعیت</button>
                    </div>

                </form>

            </div>
        </div>

        <!-- آیتم‌های سفارش -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                آیتم‌های سفارش
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th>محصول</th>
                        <th>تعداد</th>
                        <th>قیمت</th>
                        <th>جمع</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->translation->title }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price) }} تومان</td>
                            <td>{{ number_format($item->price * $item->quantity) }} تومان</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>
