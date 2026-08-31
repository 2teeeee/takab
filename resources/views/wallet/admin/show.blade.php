<x-admin-layout
        title="جزئیات درخواست برداشت"
        header="جزئیات درخواست برداشت"
>

    <div class="container-fluid py-4">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}

                <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-1"></i>
                {{ session('error') }}

                <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- Back --}}
        <div class="mb-3">

            <a
                    href="{{ route('admin.wallet.withdrawals.index') }}"
                    class="btn btn-sm btn-outline-secondary"
            >
                <i class="bi bi-arrow-right"></i>
                بازگشت به لیست
            </a>

        </div>


        <div class="row g-4">


            {{-- Main Information --}}
            <div class="col-lg-8">

                {{-- Request Information --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            <i class="bi bi-wallet2"></i>
                            اطلاعات درخواست
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Request ID --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    شماره درخواست
                                </div>

                                <div class="fw-bold">
                                    #{{ $withdrawal->id }}
                                </div>

                            </div>


                            {{-- Amount --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    مبلغ برداشت
                                </div>

                                <div class="fw-bold fs-5">
                                    {{ number_format($withdrawal->amount) }}

                                    <span class="fs-6 text-muted">
                                        تومان
                                    </span>
                                </div>

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    وضعیت
                                </div>

                                @switch($withdrawal->status)

                                    @case('pending')

                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i>
                                            در انتظار بررسی
                                        </span>

                                        @break

                                    @case('approved')

                                        <span class="badge bg-info">
                                            <i class="bi bi-check"></i>
                                            تایید شده - در انتظار پرداخت
                                        </span>

                                        @break

                                    @case('rejected')

                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i>
                                            رد شده
                                        </span>

                                        @break

                                    @case('paid')

                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i>
                                            پرداخت شده
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-secondary">
                                            {{ $withdrawal->status }}
                                        </span>

                                @endswitch

                            </div>


                            {{-- Created At --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    تاریخ ثبت درخواست
                                </div>

                                <div>
                                    {{ jdate($withdrawal->created_at->setTimezone('Asia/Tehran'))
                                        ->format('Y/m/d - H:i') }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- User Information --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            <i class="bi bi-person"></i>
                            اطلاعات کاربر
                        </h6>

                    </div>

                    <div class="card-body">

                        @if($withdrawal->user)

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        نام
                                    </div>

                                    <div class="fw-bold">
                                        {{ $withdrawal->user->name }}
                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        موبایل
                                    </div>

                                    <div>
                                        {{ $withdrawal->user->mobile }}
                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="alert alert-warning mb-0">
                                کاربر مربوط به این درخواست حذف شده است.
                            </div>

                        @endif

                    </div>

                </div>


                {{-- Bank Information --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            <i class="bi bi-bank"></i>
                            اطلاعات حساب مقصد
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            @if($withdrawal->account_holder_name)

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        نام صاحب حساب
                                    </div>

                                    <div class="fw-bold">
                                        {{ $withdrawal->account_holder_name }}
                                    </div>

                                </div>

                            @endif


                            @if($withdrawal->card_number)

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        شماره کارت
                                    </div>

                                    <div dir="ltr" class="fw-bold">
                                        {{ $withdrawal->card_number }}
                                    </div>

                                </div>

                            @endif


                            @if($withdrawal->account_number)

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        شماره حساب
                                    </div>

                                    <div dir="ltr">
                                        {{ $withdrawal->account_number }}
                                    </div>

                                </div>

                            @endif


                            @if($withdrawal->sheba_number)

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        شماره شبا
                                    </div>

                                    <div dir="ltr" class="fw-bold">
                                        {{ $withdrawal->sheba_number }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- User Description --}}
                @if($withdrawal->description)

                    <div class="card shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h6 class="mb-0">
                                <i class="bi bi-chat-left-text"></i>
                                توضیحات کاربر
                            </h6>

                        </div>

                        <div class="card-body">

                            <div class="bg-light rounded p-3">
                                {{ $withdrawal->description }}
                            </div>

                        </div>

                    </div>

                @endif


                {{-- Admin Note --}}
                @if($withdrawal->admin_note)

                    <div class="card shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h6 class="mb-0">
                                <i class="bi bi-sticky"></i>
                                توضیحات مدیریت
                            </h6>

                        </div>

                        <div class="card-body">

                            <div class="bg-light rounded p-3">
                                {{ $withdrawal->admin_note }}
                            </div>

                        </div>

                    </div>

                @endif

            </div>


            {{-- Actions --}}
            <div class="col-lg-4">


                {{-- Pending --}}
                @if($withdrawal->status === 'pending')

                    <div class="card shadow-sm mb-4">

                        <div class="card-header bg-warning">

                            <h6 class="mb-0">
                                <i class="bi bi-exclamation-circle"></i>
                                بررسی درخواست
                            </h6>

                        </div>

                        <div class="card-body">

                            <p class="small text-muted">
                                پس از بررسی اطلاعات حساب، می‌توانید
                                درخواست را تأیید یا رد کنید.
                            </p>


                            {{-- Approve --}}
                            <form
                                    action="{{ route(
                                    'admin.wallet.withdrawals.approve',
                                    $withdrawal
                                ) }}"
                                    method="POST"
                                    class="mb-3"
                            >

                                @csrf

                                <button
                                        type="submit"
                                        class="btn btn-success w-100"
                                        onclick="return confirm(
                                        'آیا از تأیید این درخواست اطمینان دارید؟'
                                    )"
                                >

                                    <i class="bi bi-check-circle"></i>

                                    تأیید درخواست

                                </button>

                            </form>


                            {{-- Reject --}}
                            <form
                                    action="{{ route(
                                    'admin.wallet.withdrawals.reject',
                                    $withdrawal
                                ) }}"
                                    method="POST"
                            >

                                @csrf

                                <label class="form-label">
                                    دلیل رد درخواست
                                </label>

                                <textarea
                                        name="admin_note"
                                        class="form-control mb-2"
                                        rows="4"
                                        required
                                        maxlength="1000"
                                        placeholder="دلیل رد درخواست را وارد کنید..."
                                >{{ old('admin_note') }}</textarea>

                                @error('admin_note')

                                <div class="text-danger small mb-2">
                                    {{ $message }}
                                </div>

                                @enderror

                                <button
                                        type="submit"
                                        class="btn btn-danger w-100"
                                        onclick="return confirm(
                                        'آیا از رد این درخواست اطمینان دارید؟'
                                    )"
                                >

                                    <i class="bi bi-x-circle"></i>

                                    رد درخواست

                                </button>

                            </form>

                        </div>

                    </div>

                @endif


                {{-- Approved --}}
                @if($withdrawal->status === 'approved')

                    <div class="card shadow-sm mb-4">

                        <div class="card-header bg-info text-white">

                            <h6 class="mb-0">
                                <i class="bi bi-bank"></i>
                                ثبت پرداخت
                            </h6>

                        </div>

                        <div class="card-body">

                            <div class="alert alert-info small">

                                پس از واریز واقعی مبلغ به حساب کاربر،
                                کد پیگیری پرداخت را وارد کرده و پرداخت
                                را ثبت کنید.

                            </div>


                            <form
                                    action="{{ route(
                                    'admin.wallet.withdrawals.paid',
                                    $withdrawal
                                ) }}"
                                    method="POST"
                            >

                                @csrf

                                <div class="mb-3">

                                    <label class="form-label">
                                        کد پیگیری پرداخت
                                    </label>

                                    <input
                                            type="text"
                                            name="payment_tracking_code"
                                            class="form-control"
                                            value="{{ old('payment_tracking_code') }}"
                                            maxlength="255"
                                            required
                                            placeholder="کد پیگیری واریز..."
                                    >

                                    @error('payment_tracking_code')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                <button
                                        type="submit"
                                        class="btn btn-success w-100"
                                        onclick="return confirm(
                                        'آیا مبلغ واقعاً به حساب کاربر واریز شده است؟'
                                    )"
                                >

                                    <i class="bi bi-check-circle"></i>

                                    ثبت پرداخت

                                </button>

                            </form>

                        </div>

                    </div>

                @endif


                {{-- Processing Information --}}
                @if($withdrawal->processor)

                    <div class="card shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h6 class="mb-0">
                                <i class="bi bi-person-check"></i>
                                اطلاعات بررسی
                            </h6>

                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <div class="text-muted small">
                                    بررسی توسط
                                </div>

                                <div class="fw-bold">
                                    {{ $withdrawal->processor->name }}
                                </div>

                            </div>


                            @if($withdrawal->processed_at)

                                <div>

                                    <div class="text-muted small">
                                        تاریخ بررسی
                                    </div>

                                    <div>
                                        {{ jdate($withdrawal->processed_at->setTimezone('Asia/Tehran'))
                                            ->format('Y/m/d - H:i') }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif


                {{-- Payment Information --}}
                @if($withdrawal->status === 'paid')

                    <div class="card shadow-sm border-success">

                        <div class="card-header bg-success text-white">

                            <h6 class="mb-0">
                                <i class="bi bi-check-circle"></i>
                                اطلاعات پرداخت
                            </h6>

                        </div>

                        <div class="card-body">

                            @if($withdrawal->payment_tracking_code)

                                <div class="mb-3">

                                    <div class="text-muted small">
                                        کد پیگیری پرداخت
                                    </div>

                                    <div
                                            dir="ltr"
                                            class="fw-bold"
                                    >
                                        {{ $withdrawal->payment_tracking_code }}
                                    </div>

                                </div>

                            @endif


                            @if($withdrawal->paid_at)

                                <div>

                                    <div class="text-muted small">
                                        تاریخ پرداخت
                                    </div>

                                    <div>
                                        {{ jdate($withdrawal->paid_at->setTimezone('Asia/Tehran'))
                                            ->format('Y/m/d - H:i') }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif


                {{-- Rejected Information --}}
                @if($withdrawal->status === 'rejected')

                    <div class="card shadow-sm border-danger">

                        <div class="card-header bg-danger text-white">

                            <h6 class="mb-0">
                                <i class="bi bi-x-circle"></i>
                                درخواست رد شده
                            </h6>

                        </div>

                        <div class="card-body">

                            @if($withdrawal->admin_note)

                                <div class="small text-muted mb-1">
                                    دلیل رد:
                                </div>

                                <div>
                                    {{ $withdrawal->admin_note }}
                                </div>

                            @endif

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-admin-layout>
