<x-admin-layout>

    <div class="container py-4">

        @php
            $isWholesaler = auth()->user()->hasRole('wholesaler');
            $isStore = auth()->user()->hasRole('store');

            $pageTitle = $isWholesaler
                ? 'خریدهای من'
                : 'خریدهای فروشگاه';

            $supplierTitle = $isWholesaler
                ? 'تأمین‌کننده'
                : 'عمده‌فروش';

            $purchaseRoute = $isWholesaler
                ? route('wholesaler.orders.purchases')
                : route('store.orders.purchases');
        @endphp


        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    {{ $pageTitle }}
                </h4>

                <div class="text-muted small">
                    لیست سفارش‌هایی که برای تأمین کالا ثبت کرده‌اید
                </div>

            </div>

        </div>


        {{-- Messages --}}
        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif

        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        {{-- Filters --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET"
                      action="{{ $purchaseRoute }}"
                      class="row g-2">

                    <div class="col-md-5">

                        <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="شماره سفارش، نام {{ $supplierTitle }} یا موبایل"
                        >

                    </div>


                    <div class="col-md-3">

                        <select name="status"
                                class="form-select">

                            <option value="">
                                همه وضعیت‌ها
                            </option>

                            <option value="pending"
                                    @selected(request('status') === 'pending')>
                                در حال بررسی
                            </option>

                            <option value="success"
                                    @selected(request('status') === 'success')>
                                تأیید شده
                            </option>

                            <option value="rejected"
                                    @selected(request('status') === 'rejected')>
                                رد شده
                            </option>

                            <option value="paid"
                                    @selected(request('status') === 'paid')>
                                پرداخت شده
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            جستجو

                        </button>

                    </div>


                    <div class="col-md-2">

                        <a href="{{ $purchaseRoute }}"
                           class="btn btn-outline-secondary w-100">

                            پاک کردن

                        </a>

                    </div>

                </form>

            </div>

        </div>


        {{-- Orders --}}
        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">

                {{ $pageTitle }}

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>شماره سفارش</th>

                            <th>{{ $supplierTitle }}</th>

                            <th>محصولات</th>

                            <th>مبلغ</th>

                            <th>وضعیت</th>

                            <th>تاریخ</th>

                            <th>عملیات</th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($orders as $order)

                            <tr>

                                {{-- Number --}}
                                <td>

                                    {{ $loop->iteration
                                        + (($orders->currentPage() - 1)
                                        * $orders->perPage()) }}

                                </td>


                                {{-- Order --}}
                                <td>

                                    <strong>
                                        #{{ $order->id }}
                                    </strong>

                                </td>


                                {{-- Supplier --}}
                                <td>

                                    @if($order->wholesaler)

                                        <div>
                                            {{ $order->wholesaler->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $order->wholesaler->mobile ?? '—' }}
                                        </small>

                                    @elseif($order->fromUser)

                                        <div>
                                            {{ $order->fromUser->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $order->fromUser->mobile ?? '—' }}
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Products --}}
                                <td>

                                    @foreach($order->items->take(2) as $item)

                                        <div class="small">

                                            {{ optional(
                                                $item->product->translation
                                            )->title ?? 'محصول حذف شده' }}

                                            ×

                                            {{ number_format($item->quantity) }}

                                        </div>

                                    @endforeach


                                    @if($order->items->count() > 2)

                                        <div class="small text-muted">

                                            و
                                            {{ $order->items->count() - 2 }}
                                            محصول دیگر

                                        </div>

                                    @endif

                                </td>


                                {{-- Amount --}}
                                <td>

                                    <strong>

                                        {{ number_format(
                                            $order->final_total
                                            ?? $order->total
                                            ?? 0
                                        ) }}

                                    </strong>

                                    <small>
                                        تومان
                                    </small>


                                    @if(($order->discount ?? 0) > 0)

                                        <div class="text-danger small">

                                            تخفیف:

                                            {{ number_format(
                                                $order->discount
                                            ) }}

                                            تومان

                                        </div>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    <x-status_badge status="{{ $order->status }}" />

                                </td>


                                {{-- Date --}}
                                <td>

                                    <small>

                                        {{ jdate($order->created_at)
                                            ->format('Y/m/d') }}

                                    </small>

                                    <br>

                                    <small class="text-muted">

                                        {{ jdate($order->created_at)
                                            ->format('H:i') }}

                                    </small>

                                </td>


                                {{-- Action --}}
                                <td>

                                    <a
                                            href="{{ route(
                                            'admin.orders.show',
                                            $order
                                        ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                    >

                                        جزئیات

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                        colspan="8"
                                        class="text-center text-muted py-5"
                                >

                                    هنوز هیچ سفارش خریدی ثبت نشده است.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            @if($orders->hasPages())

                <div class="card-footer">

                    {{ $orders->links() }}

                </div>

            @endif

        </div>

    </div>

</x-admin-layout>