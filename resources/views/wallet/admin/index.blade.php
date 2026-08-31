<x-admin-layout
        title="مدیریت درخواست‌های واریز"
        header="مدیریت درخواست‌های واریز"
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


        {{-- Page Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-wallet2 me-1"></i>
                    درخواست‌های برداشت
                </h5>

                <div class="text-muted small">
                    مدیریت و بررسی درخواست‌های برداشت کاربران
                </div>
            </div>

        </div>


        {{-- Search & Filter --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form
                        method="GET"
                        action="{{ route('admin.wallet.withdrawals.index') }}"
                        class="row g-2"
                >

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label small">
                            جستجو
                        </label>

                        <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control form-control-sm"
                                placeholder="نام، موبایل، شماره کارت، شبا یا کد پیگیری..."
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label small">
                            وضعیت
                        </label>

                        <select
                                name="status"
                                class="form-select form-select-sm"
                        >

                            <option value="">
                                همه وضعیت‌ها
                            </option>

                            <option
                                    value="pending"
                                    @selected(request('status') === 'pending')
                            >
                                در انتظار بررسی
                            </option>

                            <option
                                    value="approved"
                                    @selected(request('status') === 'approved')
                            >
                                تایید شده
                            </option>

                            <option
                                    value="rejected"
                                    @selected(request('status') === 'rejected')
                            >
                                رد شده
                            </option>

                            <option
                                    value="paid"
                                    @selected(request('status') === 'paid')
                            >
                                پرداخت شده
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-auto d-flex align-items-end gap-2">

                        <button
                                type="submit"
                                class="btn btn-dark btn-sm"
                        >
                            <i class="bi bi-search"></i>
                            جستجو
                        </button>

                        @if(request()->filled('search') || request()->filled('status'))

                            <a
                                    href="{{ route('admin.wallet.withdrawals.index') }}"
                                    class="btn btn-outline-danger btn-sm"
                            >
                                <i class="bi bi-x-circle"></i>
                                حذف فیلتر
                            </a>

                        @endif

                    </div>

                </form>

            </div>

        </div>


        {{-- Requests Table --}}
        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th class="text-center">
                                #
                            </th>

                            <th>
                                کاربر
                            </th>

                            <th>
                                مبلغ
                            </th>

                            <th>
                                اطلاعات حساب
                            </th>

                            <th>
                                وضعیت
                            </th>

                            <th>
                                تاریخ درخواست
                            </th>

                            <th class="text-center">
                                عملیات
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($withdrawals as $withdrawal)

                            <tr>

                                {{-- Number --}}
                                <td class="text-center">

                                    {{
                                        $loop->iteration
                                        + (
                                            ($withdrawals->currentPage() - 1)
                                            * $withdrawals->perPage()
                                        )
                                    }}

                                </td>


                                {{-- User --}}
                                <td>

                                    @if($withdrawal->user)

                                        <div class="fw-bold">
                                            {{ $withdrawal->user->name }}
                                        </div>

                                        <div class="text-muted small">

                                            <i class="bi bi-phone"></i>

                                            {{ $withdrawal->user->mobile }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            کاربر حذف شده
                                        </span>

                                    @endif

                                </td>


                                {{-- Amount --}}
                                <td>

                                    <span class="fw-bold">
                                        {{ number_format($withdrawal->amount) }}
                                    </span>

                                    <span class="text-muted small">
                                        تومان
                                    </span>

                                </td>


                                {{-- Bank Information --}}
                                <td>

                                    @if($withdrawal->card_number)

                                        <div class="small">

                                            <span class="text-muted">
                                                کارت:
                                            </span>

                                            {{ $withdrawal->card_number }}

                                        </div>

                                    @endif


                                    @if($withdrawal->account_number)

                                        <div class="small">

                                            <span class="text-muted">
                                                حساب:
                                            </span>

                                            {{ $withdrawal->account_number }}

                                        </div>

                                    @endif


                                    @if($withdrawal->sheba_number)

                                        <div class="small">

                                            <span class="text-muted">
                                                شبا:
                                            </span>

                                            {{ $withdrawal->sheba_number }}

                                        </div>

                                    @endif


                                    @if(!$withdrawal->card_number &&
                                        !$withdrawal->account_number &&
                                        !$withdrawal->sheba_number)

                                        <span class="text-muted small">
                                            ثبت نشده
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

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
                                                تایید شده
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

                                </td>


                                {{-- Date --}}
                                <td>

                                    @if($withdrawal->created_at)

                                        <div class="small">

                                            {{ jdate($withdrawal->created_at)
                                                ->format('Y/m/d') }}

                                        </div>

                                        <div class="text-muted small">

                                            {{ jdate($withdrawal->created_at->setTimezone('Asia/Tehran'))
                                                ->format('H:i') }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-center">

                                    <a
                                            href="{{ route(
                                            'admin.wallet.withdrawals.show',
                                            $withdrawal
                                        ) }}"
                                            class="btn btn-sm btn-primary"
                                    >
                                        <i class="bi bi-eye"></i>
                                        مشاهده
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                        colspan="7"
                                        class="text-center py-5 text-muted"
                                >

                                    <i class="bi bi-wallet2 fs-2 d-block mb-2"></i>

                                    هیچ درخواست واریزی یافت نشد.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Pagination --}}
            @if($withdrawals->hasPages())

                <div class="card-footer bg-white">

                    {{ $withdrawals->links() }}

                </div>

            @endif

        </div>

    </div>

</x-admin-layout>
