<x-admin-layout title="جزئیات سفارش" header="جزئیات سفارش">

    <div class="container py-4">

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error message --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>
                جزئیات سفارش #{{ $order->id }}
            </h4>

            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-secondary">
                بازگشت
            </a>
        </div>


        {{-- Order information --}}
        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-primary text-white">
                اطلاعات سفارش
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>کاربر:</strong><br>
                        {{ $order->user->name }}

                        <div class="text-muted small">
                            {{ $order->user->mobile }}
                        </div>
                    </div>


                    <div class="col-md-8 mb-3">
                        <strong>آدرس:</strong><br>
                        {{ $order->address ?? '—' }}
                        @if($order->postal_code)
                            <div class="text-muted">
                                کد پستی: {{ $order->postal_code }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>وضعیت سفارش:</strong><br>
                        <span class="badge bg-{{ $order->status_color }}">
                            {{ __('orders.status.' . $order->status) }}
                        </span>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>مبلغ کل:</strong><br>

                        {{ number_format($order->amount) }}
                        تومان

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


        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-info text-white">
                تغییر وضعیت سفارش
            </div>

            <div class="card-body">

                @if($order->from_user_id && in_array($order->status, ['success', 'rejected']))
                    <div class="alert alert-info">
                        این سفارش قبلاً بررسی شده و امکان تغییر مجدد وضعیت آن وجود ندارد.
                    </div>
                @else
                    <form action="{{ route('admin.orders.updateStatus', $order) }}"
                          method="POST"
                          class="row gy-2 align-items-center">

                        @csrf

                        <div class="col-md-4">
                            <select name="status" class="form-select">

                                @if($order->from_user_id)

                                    <option value="success" @selected($order->status=='success')>تایید شده</option>
                                    <option value="rejected" @selected($order->status=='rejected')>رد شده</option>

                                @else

                                    <option value="pending" @selected($order->status=='pending')>در حال بررسی</option>
                                    <option value="paid" @selected($order->status=='paid')>پرداخت شده</option>
                                    <option value="processing" @selected($order->status=='processing')>در حال آماده‌سازی</option>
                                    <option value="shipping" @selected($order->status=='shipping')>ارسال شده</option>
                                    <option value="delivered" @selected($order->status=='delivered')>تحویل شده</option>
                                    <option value="canceled" @selected($order->status=='canceled')>لغو شده</option>

                                @endif

                            </select>
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary w-100">
                                ذخیره وضعیت
                            </button>
                        </div>

                    </form>
                @endif
            </div>
        </div>

        {{-- Order items --}}
        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">
                آیتم‌های سفارش
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-striped table-hover mb-0">

                        <thead>

                        <tr>
                            <th>محصول</th>
                            <th>تعداد</th>
                            <th>قیمت</th>
                            <th>جمع</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($order->items as $item)

                            <tr>

                                <td>
                                    {{ optional($item->product->translation)->title ?? 'محصول حذف شده' }}
                                </td>

                                <td>
                                    {{ number_format($item->quantity) }}
                                </td>

                                <td>
                                    {{ number_format($item->price) }}
                                    تومان
                                </td>

                                <td>
                                    {{ number_format($item->price * $item->quantity) }}
                                    تومان
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center text-muted py-4">

                                    آیتمی برای این سفارش ثبت نشده است.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
