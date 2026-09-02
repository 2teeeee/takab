<x-admin-layout title="جزئیات سفارش" header="جزئیات سفارش">

    <div class="container-fluid py-4">

        {{-- ========================================================= --}}
        {{-- Flash Messages --}}
        {{-- ========================================================= --}}

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <div class="fw-semibold mb-2">
                    خطاهای موجود در فرم:
                </div>

                <ul class="mb-0 pe-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- Page Header --}}
        {{-- ========================================================= --}}

        <div class="d-flex flex-column flex-md-row justify-content-between
                    align-items-md-center gap-3 mb-4">

            <div>
                <div class="d-flex align-items-center gap-2 mb-1">

                    <h4 class="mb-0 fw-bold">
                        جزئیات سفارش
                    </h4>

                    <span class="text-muted">
                        #{{ $order->id }}
                    </span>

                </div>

                <div class="text-muted small">
                    اطلاعات کامل مشتری، پرداخت، وضعیت و اقلام سفارش
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-sm btn-outline-secondary">

                <i class="bi bi-arrow-right me-1"></i>
                بازگشت به سفارش‌ها

            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- Buyer Information --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center gap-2">

                    <div class="text-secondary">
                        <i class="bi bi-person fs-5"></i>
                    </div>

                    <div>
                        <div class="fw-semibold">
                            اطلاعات خریدار
                        </div>

                        <div class="small text-muted">
                            مشخصات مشتری ثبت‌کننده سفارش
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            نام و نام خانوادگی
                        </div>

                        <div class="fw-semibold">
                            {{ $order->user->name ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            شماره موبایل
                        </div>

                        <div class="fw-semibold">
                            {{ $order->user->mobile ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            کد ملی
                        </div>

                        <div class="fw-semibold">
                            {{ $order->user->national_code ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-8">

                        <div class="small text-muted mb-1">
                            آدرس
                        </div>

                        <div class="fw-semibold lh-lg">
                            {{ $order->address ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            کد پستی
                        </div>

                        <div class="fw-semibold">
                            {{ $order->postal_code ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Seller / Sender --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center gap-2">

                    <div class="text-secondary">
                        <i class="bi bi-shop fs-5"></i>
                    </div>

                    <div>
                        <div class="fw-semibold">
                            اطلاعات فروشنده / فرستنده
                        </div>

                        <div class="small text-muted">
                            منبع ثبت یا ارسال سفارش
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-body">

                @if($order->seller)

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                نوع
                            </div>

                            <div class="fw-semibold">
                                فروش مستقیم
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                نام فروشنده
                            </div>

                            <div class="fw-semibold">
                                {{ $order->seller->name }}
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                شماره موبایل
                            </div>

                            <div class="fw-semibold">
                                {{ $order->seller->mobile ?? '—' }}
                            </div>

                        </div>

                    </div>

                @elseif($order->fromUser)

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                نوع
                            </div>

                            <div class="fw-semibold">
                                انتقال موجودی
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                فرستنده موجودی
                            </div>

                            <div class="fw-semibold">
                                {{ $order->fromUser->name }}
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                شماره موبایل
                            </div>

                            <div class="fw-semibold">
                                {{ $order->fromUser->mobile ?? '—' }}
                            </div>

                        </div>

                    </div>

                @else

                    <div class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        اطلاعات فروشنده یا فرستنده برای این سفارش ثبت نشده است.
                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Financial Information --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center gap-2">

                    <div class="text-secondary">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>

                    <div>
                        <div class="fw-semibold">
                            اطلاعات مالی
                        </div>

                        <div class="small text-muted">
                            خلاصه مبالغ و اطلاعات پرداخت
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Total --}}
                    <div class="col-12 col-md-4">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="small text-muted mb-2">
                                مبلغ کل
                            </div>

                            <div class="fs-5 fw-semibold">
                                {{ number_format($order->total ?? 0) }}
                                <small class="text-muted">تومان</small>
                            </div>

                        </div>

                    </div>


                    {{-- Discount --}}
                    <div class="col-12 col-md-4">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="small text-muted mb-2">
                                تخفیف
                            </div>

                            <div class="fs-5 fw-semibold">
                                {{ number_format($order->discount ?? 0) }}
                                <small class="text-muted">تومان</small>
                            </div>

                        </div>

                    </div>


                    {{-- Final --}}
                    <div class="col-12 col-md-4">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="small text-muted mb-2">
                                مبلغ نهایی
                            </div>

                            <div class="fs-5 fw-bold">
                                {{ number_format($order->final_total ?? 0) }}
                                <small class="text-muted">تومان</small>
                            </div>

                        </div>

                    </div>


                    {{-- Payment Status --}}
                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            وضعیت پرداخت
                        </div>

                        <div class="fw-semibold">

                            @if($order->reference_id)

                                <span class="badge text-bg-success">
                                    پرداخت شده
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    بدون تراکنش
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Authority --}}
                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            Authority
                        </div>

                        <div class="text-break">
                            {{ $order->authority ?? '—' }}
                        </div>

                    </div>


                    {{-- Reference --}}
                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            Reference ID
                        </div>

                        <div class="text-break">
                            {{ $order->reference_id ?? '—' }}
                        </div>

                    </div>


                    {{-- Created --}}
                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            تاریخ ثبت سفارش
                        </div>

                        <div class="fw-semibold">

                            {{ jdate(
                                $order->created_at->setTimezone('Asia/Tehran')
                            )->format('Y/m/d H:i') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Order Status --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center gap-2">

                    <div class="text-secondary">
                        <i class="bi bi-arrow-repeat fs-5"></i>
                    </div>

                    <div>
                        <div class="fw-semibold">
                            وضعیت سفارش
                        </div>

                        <div class="small text-muted">
                            وضعیت فعلی و تغییر وضعیت سفارش
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="col-md-4 mb-2">

                    <div class="fw-semibold">

                        <x-status_badge status="{{ $order->status }}" />

                    </div>

                </div>


                @if(
                    $order->from_user_id &&
                    in_array($order->status, ['success', 'rejected'])
                )

                    <div class="border rounded-3 p-3">

                        <div class="fw-semibold mb-2">
                            این سفارش بررسی شده است.
                        </div>

                        <div class="small text-muted">
                            امکان تغییر مجدد وضعیت این سفارش وجود ندارد.
                        </div>

                    </div>

                    <div class="mt-4">

                        <div class="small text-muted mb-1">
                            توضیحات بررسی
                        </div>

                        <div class="border rounded-3 p-3">
                            {{ $order->status_note ?: 'توضیحی ثبت نشده است.' }}
                        </div>

                    </div>

                @else

                    <form action="{{ route('admin.orders.updateStatus', $order) }}"
                          method="POST">

                        @csrf

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    وضعیت سفارش
                                </label>

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

                                <label class="form-label fw-semibold">
                                    توضیح تغییر وضعیت
                                    <span class="text-muted fw-normal small">
                                        (اختیاری)
                                    </span>
                                </label>

                                <textarea
                                        name="status_note"
                                        rows="3"
                                        class="form-control @error('status_note') is-invalid @enderror"
                                        placeholder="در صورت نیاز توضیح یا دلیل تغییر وضعیت را وارد کنید..."
                                >{{ old('status_note', $order->status_note) }}</textarea>

                                @error('status_note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            <div class="col-md-4">

                                <button type="submit"
                                        class="btn btn-dark w-100">

                                    <i class="bi bi-check2 me-1"></i>
                                    ذخیره وضعیت

                                </button>

                            </div>

                        </div>

                    </form>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Order Items --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center gap-2">

                        <div class="text-secondary">
                            <i class="bi bi-box-seam fs-5"></i>
                        </div>

                        <div>

                            <div class="fw-semibold">
                                آیتم‌های سفارش
                            </div>

                            <div class="small text-muted">
                                محصولات ثبت‌شده در این سفارش
                            </div>

                        </div>

                    </div>

                    <span class="small text-muted">
                        {{ $order->items->count() }} آیتم
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th class="px-3 py-3">
                                #
                            </th>

                            <th>
                                محصول
                            </th>

                            <th>
                                تعداد
                            </th>

                            <th>
                                قیمت واحد
                            </th>

                            <th class="text-end px-3">
                                جمع
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($order->items as $item)

                            <tr>

                                <td class="px-3 text-muted">
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ optional($item->product->translation)->title
                                            ?? 'محصول حذف شده' }}

                                    </div>

                                </td>


                                <td>

                                    <span class="text-muted">
                                        {{ number_format($item->quantity) }}
                                    </span>

                                </td>


                                <td>

                                    {{ number_format($item->price) }}

                                    <small class="text-muted">
                                        تومان
                                    </small>

                                </td>


                                <td class="text-end px-3 fw-semibold">

                                    {{ number_format(
                                        $item->price * $item->quantity
                                    ) }}

                                    <small class="text-muted">
                                        تومان
                                    </small>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-5">

                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>

                                    آیتمی برای این سفارش ثبت نشده است.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>


                        <tfoot>

                        <tr>

                            <th colspan="4"
                                class="text-end px-3">

                                مبلغ کل

                            </th>

                            <th class="text-end px-3">

                                {{ number_format($order->total ?? 0) }}

                                <small class="text-muted">
                                    تومان
                                </small>

                            </th>

                        </tr>


                        <tr>

                            <th colspan="4"
                                class="text-end px-3">

                                تخفیف

                            </th>

                            <th class="text-end px-3">

                                {{ number_format($order->discount ?? 0) }}

                                <small class="text-muted">
                                    تومان
                                </small>

                            </th>

                        </tr>


                        <tr>

                            <th colspan="4"
                                class="text-end px-3">

                                مبلغ نهایی

                            </th>

                            <th class="text-end px-3 fw-bold fs-6">

                                {{ number_format($order->final_total ?? 0) }}

                                <small class="text-muted">
                                    تومان
                                </small>

                            </th>

                        </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-admin-layout>