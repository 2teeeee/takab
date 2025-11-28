<x-admin-layout title="لیست سفارش‌ها" header="لیست سفارش‌ها">
    <div class="container py-4">

        <h4 class="fw-bold mb-4">لیست سفارش‌ها</h4>

        <!-- فیلترها -->
        <form method="GET" class="row g-3 mb-4">

            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="جستجو: نام، موبایل یا شماره سفارش"
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">همه وضعیت‌ها</option>
                    <option value="pending" @selected(request('status')==='pending')>در انتظار</option>
                    <option value="paid" @selected(request('status')==='paid')>پرداخت‌شده</option>
                    <option value="success" @selected(request('status')==='success')>موفق</option>
                    <option value="canceled" @selected(request('status')==='canceled')>لغو شده</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">فیلتر</button>
            </div>

        </form>

        <!-- جدول -->
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>کاربر</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th>کد تراکنش</th>
                        <th>تاریخ</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>

                            <td>
                                {{ $order->user->name ?? '---' }}
                                <br>
                                <small class="text-muted">{{ $order->user->mobile ?? '' }}</small>
                            </td>

                            <td>{{ number_format($order->total) }} تومان</td>

                            <td>
                                @if($order->status === 'paid' || $order->status === 'success')
                                    <span class="badge bg-success">موفق / پرداخت‌شده</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">در انتظار</span>
                                @elseif($order->status === 'canceled')
                                    <span class="badge bg-danger">لغو شده</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endif
                            </td>

                            <td>
                                @if($order->reference_id)
                                    <span class="text-primary fw-bold">{{ $order->reference_id }}</span>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>{{ jdate($order->created_at)->format('Y/m/d H:i') }}</td>

                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    مشاهده
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">سفارشی یافت نشد!</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

            </div>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>

    </div>
</x-admin-layout>
