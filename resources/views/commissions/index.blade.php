<x-admin-layout
        title="گزارش پورسانت‌ها"
        header="گزارش پورسانت‌ها"
>

    <div class="container py-4">

        {{-- ===================================================== --}}
        {{-- Header --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    گزارش پورسانت‌ها
                </h4>

                <div class="text-muted small">
                    گزارش درآمد، پرداخت ‌شده و پرداخت‌ نشده
                </div>
            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Summary --}}
        {{-- ===================================================== --}}

        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">
                            کل پورسانت
                        </div>
                        <div class="fs-4 fw-bold mt-2">
                            {{ number_format($totalAmount) }}
                            <small class="fs-6">تومان</small>
                        </div>
                        <div class="text-muted small mt-2">
                            {{ number_format($totalCount) }} رکورد
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">
                            پرداخت شده
                        </div>
                        <div class="fs-4 fw-bold text-success mt-2">
                            {{ number_format($paidAmount) }}
                            <small class="fs-6">تومان</small>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">
                            پرداخت نشده
                        </div>
                        <div class="fs-4 fw-bold text-danger mt-2">
                            {{ number_format($unpaidAmount) }}
                            <small class="fs-6">تومان</small>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">
                            قابل پرداخت
                        </div>
                        <div class="fs-4 fw-bold text-warning mt-2">
                            {{ number_format($unpaidAmount) }}
                            <small class="fs-6">
                                تومان
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Filters --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                فیلتر گزارش
            </div>
            <div class="card-body">
                <form method="GET"
                      action="{{ route('admin.commissions.index') }}">
                    <div class="row g-3">
                        {{-- Search --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                جستجو
                            </label>
                            <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    value="{{ request('search') }}"
                                    placeholder="نام، موبایل یا شماره پورسانت"
                            >
                        </div>

                        {{-- User --}}

                        <div class="col-md-3">
                            <label class="form-label">
                                کاربر
                            </label>
                            <select
                                    name="user_id"
                                    class="form-select"
                            >
                                <option value="">
                                    همه کاربران
                                </option>
                                @foreach($users as $user)
                                    <option
                                            value="{{ $user->id }}"
                                            @selected(request('user_id') == $user->id)
                                    >
                                        {{ $user->name }}
                                        -
                                        {{ $user->mobile }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Type --}}

                        <select name="type" class="form-select">
                            <option value="">
                                همه
                            </option>
                            <option
                                    value="wholesaler"
                                    @selected(request('type') === 'wholesaler')
                            >
                                عمده فروش
                            </option>
                            <option
                                    value="store"
                                    @selected(request('type') === 'store')
                            >
                                فروشگاه
                            </option>
                            <option
                                    value="referral"
                                    @selected(request('type') === 'referral')
                            >
                                معرف
                            </option>
                            <option
                                    value="customer_discount"
                                    @selected(request('type') === 'customer_discount')
                            >
                                تخفیف مشتری
                            </option>
                        </select>

                        {{-- Status --}}

                        <div class="col-md-2">
                            <label class="form-label">
                                وضعیت پرداخت
                            </label>
                            <select name="is_paid" class="form-select">
                                <option value="">
                                    همه
                                </option>
                                <option
                                        value="1"
                                        @selected(request('is_paid') === '1')
                                >
                                    پرداخت شده
                                </option>
                                <option
                                        value="0"
                                        @selected(request('is_paid') === '0')
                                >
                                    پرداخت نشده
                                </option>
                            </select>
                        </div>

                        {{-- From date --}}

                        <div class="col-md-2">
                            <label class="form-label">
                                از تاریخ
                            </label>
                            <input
                                    type="date"
                                    name="from"
                                    class="form-control"
                                    value="{{ request('from') }}"
                            >
                        </div>

                        {{-- To date --}}

                        <div class="col-md-2">
                            <label class="form-label">
                                تا تاریخ
                            </label>
                            <input
                                    type="date"
                                    name="to"
                                    class="form-control"
                                    value="{{ request('to') }}"
                            >
                        </div>

                        {{-- Buttons --}}

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button
                                    type="submit"
                                    class="btn btn-primary"
                            >
                                <i class="bi bi-search"></i>
                                جستجو
                            </button>
                            <a
                                    href="{{ route('admin.commissions.index') }}"
                                    class="btn btn-secondary"
                            >
                                حذف فیلترها
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {{-- ===================================================== --}}
        {{-- Commission Table --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">
                لیست پورسانت‌ها
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">

                        <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                شخص
                            </th>
                            <th>
                                سفارش
                            </th>
                            <th>
                                نوع
                            </th>
                            <th>
                                مبلغ
                            </th>
                            <th>
                                وضعیت
                            </th>
                            <th>
                                تاریخ
                            </th>
                        </tr>
                        </thead>


                        <tbody>
                        @forelse($commissions as $commission)
                            <tr>
                                <td>
                                    {{ $commissions->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    @if($commission->user)
                                        <div class="fw-bold">
                                            {{ $commission->user->name }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $commission->user->mobile }}
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            کاربر حذف شده
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($commission->order)
                                        <a
                                                href="{{ route(
                                                'admin.orders.show',
                                                $commission->order
                                            ) }}"
                                        >
                                            #{{ $commission->order->id }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @switch($commission->type)
                                        @case('wholesaler')
                                            <span class="badge bg-primary">
                                                عمده فروش
                                            </span>
                                            @break
                                        @case('store')
                                            <span class="badge bg-success">
                                                فروشگاه
                                            </span>
                                            @break
                                        @case('referral')
                                            <span class="badge bg-info">
                                                معرف
                                            </span>
                                            @break
                                        @case('customer_discount')
                                            <span class="badge bg-warning text-dark">
                                                تخفیف مشتری
                                            </span>
                                            @break
                                        @default
                                        <span class="badge bg-secondary">
                                            {{ $commission->type }}
                                        </span>
                                    @endswitch
                                </td>
                                <td class="fw-bold">
                                    {{ number_format($commission->amount) }}
                                    <small>
                                        تومان
                                    </small>
                                </td>
                                <td>
                                    @if($commission->is_paid)
                                        <span class="badge bg-success">
                                            پرداخت شده
                                        </span>
                                        @if($commission->paid_at)
                                            <div class="small text-muted mt-1">
                                                {{ jdate($commission->paid_at)->format('Y/m/d H:i') }}
                                            </div>
                                        @endif
                                        @if($commission->paidBy)
                                            <div class="small text-muted">
                                                توسط: {{ $commission->paidBy->name }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">
                                            پرداخت نشده
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ jdate($commission->created_at)->format('Y/m/d H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                        colspan="7"
                                        class="text-center text-muted py-5"
                                >
                                    هیچ پورسانتی یافت نشد.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            @if($commissions->hasPages())
                <div class="card-footer">
                    {{ $commissions->links() }}
                </div>
            @endif
        </div>
    </div>

</x-admin-layout>
