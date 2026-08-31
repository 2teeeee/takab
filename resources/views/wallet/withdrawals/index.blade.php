<x-admin-layout
        title="درخواست‌های برداشت"
        header="درخواست‌های برداشت از کیف پول"
>

    <div class="container py-4">

        @if(session('success'))

            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>

        @endif


        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <strong>
                    موجودی کیف پول:
                </strong>

                <span class="text-success fw-bold">
                    {{ number_format($wallet->balance) }}
                    تومان
                </span>

            </div>

            <a
                    href="{{ route('wallet.withdrawals.create') }}"
                    class="btn btn-primary btn-sm"
            >
                <i class="bi bi-cash-stack"></i>
                درخواست برداشت جدید
            </a>

        </div>


        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead>

                        <tr>

                            <th>#</th>
                            <th>مبلغ</th>
                            <th>حساب</th>
                            <th>وضعیت</th>
                            <th>کد پیگیری</th>
                            <th>تاریخ</th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($requests as $withdrawal)

                            <tr>

                                <td>
                                    {{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($withdrawal->amount) }}
                                    </strong>
                                    تومان
                                </td>

                                <td>

                                    {{ $withdrawal->account_holder_name }}

                                    @if($withdrawal->card_number)
                                        <br>
                                        <small class="text-muted">
                                            کارت:
                                            {{ $withdrawal->card_number }}
                                        </small>
                                    @endif

                                </td>

                                <td>

                                    @switch($withdrawal->status)

                                        @case('pending')

                                            <span class="badge bg-warning text-dark">
                                                در انتظار بررسی
                                            </span>

                                            @break

                                        @case('approved')

                                            <span class="badge bg-primary">
                                                تأیید شده
                                            </span>

                                            @break

                                        @case('paid')

                                            <span class="badge bg-success">
                                                پرداخت شده
                                            </span>

                                            @break

                                        @case('rejected')

                                            <span class="badge bg-danger">
                                                رد شده
                                            </span>

                                            @break

                                        @case('cancelled')

                                            <span class="badge bg-secondary">
                                                لغو شده
                                            </span>

                                            @break

                                    @endswitch

                                </td>

                                <td>
                                    {{ $withdrawal->payment_tracking_code ?? '-' }}
                                </td>

                                <td>
                                    {{ jdate($withdrawal->created_at->copy()->timezone('Asia/Tehran'))->format('Y/m/d H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                        colspan="6"
                                        class="text-center py-4"
                                >
                                    هنوز درخواست برداشتی ثبت نشده است.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-3">
            {{ $requests->links() }}
        </div>

    </div>

</x-admin-layout>