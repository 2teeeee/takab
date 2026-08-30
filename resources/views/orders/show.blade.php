```blade
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


        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4>
                جزئیات سفارش #{{ $order->id }}
            </h4>

            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-secondary">
                بازگشت
            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- Buyer Information --}}
        {{-- ========================================================= --}}

        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-primary text-white">
                <i class="bi bi-person"></i>
                اطلاعات خریدار
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>نام:</strong>

                        <div class="mt-1">
                            {{ $order->user->name ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>موبایل:</strong>

                        <div class="mt-1">
                            {{ $order->user->mobile ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>کد ملی:</strong>

                        <div class="mt-1">
                            {{ $order->user->national_code ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-8 mb-3">

                        <strong>آدرس:</strong>

                        <div class="mt-1">
                            {{ $order->address ?? '—' }}
                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <strong>کد پستی:</strong>

                        <div class="mt-1">
                            {{ $order->postal_code ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- Seller / Sender Information --}}
        {{-- ========================================================= --}}

        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-success text-white">
                <i class="bi bi-shop"></i>
                اطلاعات فروشنده / فرستنده
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Direct seller --}}
                    @if($order->seller)

                        <div class="col-md-4 mb-3">

                            <strong>نوع:</strong>

                            <div class="mt-1">
                                <span class="badge bg-success">
                                    فروش مستقیم
                                </span>
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <strong>نام فروشنده:</strong>

                            <div class="mt-1">
                                {{ $order->seller->name }}
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <strong>موبایل:</strong>

                            <div class="mt-1">
                                {{ $order->seller->mobile ?? '—' }}
                            </div>

                        </div>

                        {{-- Inventory transfer --}}
                    @elseif($order->fromUser)

                        <div class="col-md-4 mb-3">

                            <strong>نوع:</strong>

                            <div class="mt-1">
                                <span class="badge bg-info">
                                    انتقال موجودی
                                </span>
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <strong>فرستنده موجودی:</strong>

                            <div class="mt-1">
                                {{ $order->fromUser->name }}
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <strong>موبایل:</strong>

                            <div class="mt-1">
                                {{ $order->fromUser->mobile ?? '—' }}
                            </div>

                        </div>

                    @else

                        <div class="col-12">

                            <div class="alert alert-secondary mb-0">
                                اطلاعات فروشنده یا فرستنده برای این سفارش ثبت نشده است.
                            </div>

                        </div>

                    @endif

                </div>

            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- Financial Information --}}
        {{-- ========================================================= --}}

        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-warning">
                <i class="bi bi-cash-stack"></i>
                اطلاعات مالی
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <strong>مبلغ کل:</strong>

                        <div class="mt-1 fs-5">
                            {{ number_format($order->total ?? 0) }}
                            <small>تومان</small>
                        </div>

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>تخفیف:</strong>

                        <div class="mt-1 text-danger fs-5">
                            {{ number_format($order->discount ?? 0) }}
                            <small>تومان</small>
                        </div>

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>مبلغ نهایی:</strong>

                        <div class="mt-1 text-success fs-5 fw-bold">
                            {{ number_format($order->final_total ?? 0) }}
                            <small>تومان</small>
                        </div>

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>وضعیت پرداخت:</strong>

                        <div class="mt-1">

                            @if($order->reference_id)

                                <span class="badge bg-success">
                                    پرداخت شده
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    بدون تراکنش
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <strong>Authority:</strong>

                        <div class="mt-1 text-muted">
                            {{ $order->authority ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <strong>Reference ID:</strong>

                        <div class="mt-1 text-muted">
                            {{ $order->reference_id ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <strong>تاریخ ثبت سفارش:</strong>

                        <div class="mt-1">
                            {{ jdate($order->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i') }}
                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- Order Status --}}
        {{-- ========================================================= --}}

        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-info text-white">
                <i class="bi bi-arrow-repeat"></i>
                تغییر وضعیت سفارش
            </div>

            <div class="card-body">

                @if($order->from_user_id && in_array($order->status, ['success', 'rejected']))

                    <div class="alert alert-info mb-0">

                        این سفارش قبلاً بررسی شده و امکان تغییر مجدد وضعیت آن وجود ندارد.

                    </div>

                    <div class="border-top mt-2 pt-2">
                        <strong>توضیحات تغییر وضعیت سفارش</strong>
                        <p>
                            {{$order->status_note ? $order->status_note : '-'}}
                        </p>
                    </div>

                @else

                    <form action="{{ route('admin.orders.updateStatus', $order) }}"
                          method="POST"
                          class="row gy-2 align-items-center">

                        @csrf

                        <div class="col-md-4">

                            <select name="status"
                                    class="form-select">

                                @if($order->from_user_id)

                                    <option value="success"
                                            @selected($order->status === 'success')}>
                                        تایید شده
                                    </option>

                                    <option value="rejected"
                                            @selected($order->status === 'rejected')}>
                                        رد شده
                                    </option>

                                @else

                                    <option value="pending"
                                            @selected($order->status === 'pending')}>
                                        در حال بررسی
                                    </option>

                                    <option value="paid"
                                            @selected($order->status === 'paid')}>
                                        پرداخت شده
                                    </option>

                                    <option value="processing"
                                            @selected($order->status === 'processing')}>
                                        در حال آماده‌سازی
                                    </option>

                                    <option value="shipping"
                                            @selected($order->status === 'shipping')}>
                                        ارسال شده
                                    </option>

                                    <option value="delivered"
                                            @selected($order->status === 'delivered')}>
                                        تحویل شده
                                    </option>

                                    <option value="canceled"
                                            @selected($order->status === 'canceled')}>
                                        لغو شده
                                    </option>

                                @endif

                            </select>

                        </div>

                        <div class="col-md-8">

                            <label class="form-label">
                                توضیح تغییر وضعیت
                                <span class="text-muted small">(اختیاری)</span>
                            </label>

                            <textarea
                                    name="status_note"
                                    rows="3"
                                    class="form-control @error('status_note') is-invalid @enderror"
                                    placeholder="در صورت نیاز دلیل تغییر وضعیت را وارد کنید..."
                            >{{ old('status_note', $order->status_note) }}</textarea>

                            @error('status_note')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        <div class="col-md-4">

                            <button type="submit"
                                    class="btn btn-primary w-100">

                                ذخیره وضعیت

                            </button>

                        </div>

                    </form>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Order Items --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">

                <i class="bi bi-box-seam"></i>
                آیتم‌های سفارش

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-striped table-hover mb-0">

                        <thead>
                        <tr>
                            <th>#</th>
                            <th>محصول</th>
                            <th>تعداد</th>
                            <th>قیمت واحد</th>
                            <th>جمع</th>
                        </tr>
                        </thead>

                        <tbody>

                        @forelse($order->items as $item)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ optional($item->product->translation)->title
                                        ?? 'محصول حذف شده' }}
                                </td>

                                <td>
                                    {{ number_format($item->quantity) }}
                                </td>

                                <td>
                                    {{ number_format($item->price) }}
                                    تومان
                                </td>

                                <td>
                                    {{ number_format(
                                        $item->price * $item->quantity
                                    ) }}
                                    تومان
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="text-center text-muted py-4">
                                    آیتمی برای این سفارش ثبت نشده است.
                                </td>
                            </tr>
                        @endforelse

                        </tbody>

                        <tfoot>

                        <tr>
                            <th colspan="4"
                                class="text-end">
                                مبلغ کل
                            </th>
                            <th>
                                {{ number_format($order->total ?? 0) }}
                                تومان
                            </th>
                        </tr>

                        <tr>
                            <th colspan="4"
                                class="text-end text-danger">
                                تخفیف
                            </th>
                            <th class="text-danger">
                                {{ number_format($order->discount ?? 0) }}
                                تومان
                            </th>
                        </tr>

                        <tr class="table-success">
                            <th colspan="4"
                                class="text-end">
                                مبلغ نهایی
                            </th>

                            <th>
                                {{ number_format($order->final_total ?? 0) }}
                                تومان
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

