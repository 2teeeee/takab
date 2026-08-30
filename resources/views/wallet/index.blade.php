<x-admin-layout
        title="کیف پول"
        header="کیف پول"
>

    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- User Info --}}
        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-primary text-white">
                <i class="bi bi-person"></i>
                اطلاعات کاربر
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>نام:</strong>

                        <div class="mt-1">
                            {{ $user->name ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>موبایل:</strong>

                        <div class="mt-1">
                            {{ $user->mobile ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>کد ملی:</strong>

                        <div class="mt-1">
                            {{ $user->national_code ?? '—' }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- Balance --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body text-center">

                <div class="text-muted mb-2">
                    موجودی کیف پول
                </div>

                <h2 class="fw-bold text-success">

                    {{ number_format($wallet->balance) }}

                    <small class="fs-6">
                        تومان
                    </small>

                </h2>

            </div>

        </div>


        {{-- Transactions --}}
        <div class="card shadow-sm">

            <div class="card-header">
                <i class="bi bi-wallet2"></i>
                گردش حساب
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead>

                        <tr>

                            <th>#</th>

                            <th>
                                کد تراکنش
                            </th>

                            <th>
                                نوع
                            </th>

                            <th>
                                مبلغ
                            </th>

                            <th>
                                موجودی
                            </th>

                            <th>
                                توضیحات
                            </th>

                            <th>
                                تاریخ
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($transactions as $transaction)

                            <tr>

                                <td>
                                    {{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                </td>

                                <td>
                                    {{ $transaction->transaction_code }}
                                </td>

                                <td>

                                    @if($transaction->type === 'credit')

                                        <span class="badge bg-success">
                                            واریز
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            برداشت
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <span class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">

                                        {{ $transaction->type === 'credit' ? '+' : '-' }}

                                        {{ number_format($transaction->amount) }}

                                    </span>

                                    تومان

                                </td>

                                <td>
                                    {{ number_format($transaction->balance_after) }}
                                    تومان
                                </td>

                                <td>
                                    {{ $transaction->description }}
                                </td>

                                <td>
                                    {{ $transaction->created_at->format('Y/m/d H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                        colspan="7"
                                        class="text-center py-4"
                                >
                                    هنوز تراکنشی ثبت نشده است.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-3">

            {{ $transactions->links() }}

        </div>

    </div>

</x-admin-layout>