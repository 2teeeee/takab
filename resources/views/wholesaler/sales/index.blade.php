<x-admin-layout
        title="فروش‌های من"
        header="فروش‌های من"
>

    <div class="container py-4">

        {{-- پیام موفقیت --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- فیلتر --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET"
                      action="{{ route('wholesaler.sales.index') }}"
                      class="row g-2">

                    <div class="col-md-5">

                        <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="شماره سفارش، نام یا موبایل خریدار"
                        >

                    </div>

                    <div class="col-md-4">

                        <select name="status" class="form-select">

                            <option value="">
                                همه وضعیت‌ها
                            </option>

                            <option value="success"
                                    @selected(request('status') === 'success')}>
                                تایید شده
                            </option>

                            <option value="pending"
                                    @selected(request('status') === 'pending')}>
                                در حال بررسی
                            </option>

                            <option value="rejected"
                                    @selected(request('status') === 'rejected')}>
                                رد شده
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <button class="btn btn-primary w-100">
                            جستجو
                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- لیست فروش‌ها --}}
        <div class="card shadow-sm">

            <div class="card-header bg-success text-white">

                <i class="bi bi-cart-check"></i>

                فروش‌های من

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-striped mb-0">

                        <thead>

                        <tr>
                            <th>#</th>
                            <th>خریدار</th>
                            <th>مبلغ</th>
                            <th>تخفیف</th>
                            <th>مبلغ نهایی</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($orders as $order)

                            <tr>

                                <td>
                                    #{{ $order->id }}
                                </td>

                                <td>

                                    <strong>
                                        {{ $order->user?->name ?? '—' }}
                                    </strong>

                                    <div class="small text-muted">
                                        {{ $order->user?->mobile ?? '—' }}
                                    </div>

                                </td>

                                <td>
                                    {{ number_format($order->total) }}
                                    تومان
                                </td>

                                <td class="text-danger">
                                    {{ number_format($order->discount ?? 0) }}
                                    تومان
                                </td>

                                <td class="text-success fw-bold">
                                    {{ number_format($order->final_total ?? $order->total) }}
                                    تومان
                                </td>

                                <td>

                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ __('orders.status.' . $order->status) }}
                                    </span>

                                </td>

                                <td>

                                    {{ jdate($order->created_at)->format('Y/m/d H:i') }}

                                </td>

                                <td>

                                    <a
                                            href="{{ route('admin.orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary"
                                    >
                                        مشاهده
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center text-muted py-4">

                                    هنوز فروشی ثبت نشده است.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="card-footer">

                {{ $orders->links() }}

            </div>

        </div>

    </div>

</x-admin-layout>