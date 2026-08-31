<x-admin-layout
        title="درخواست برداشت"
        header="درخواست برداشت از کیف پول"
>

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow-sm">

                    <div class="card-header">
                        <i class="bi bi-wallet2"></i>
                        درخواست واریز مبلغ کیف پول
                    </div>

                    <div class="card-body">

                        <div class="alert alert-info">
                            موجودی فعلی:
                            <strong>
                                {{ number_format($wallet->balance) }}
                                تومان
                            </strong>
                        </div>


                        @if($errors->any())

                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form
                                method="POST"
                                action="{{ route('wallet.withdrawals.store') }}"
                        >

                            @csrf


                            <div class="mb-3">

                                <label class="form-label">
                                    مبلغ برداشت
                                </label>

                                <div class="input-group">

                                    <input
                                            type="number"
                                            name="amount"
                                            class="form-control"
                                            min="1"
                                            max="{{ $wallet->balance }}"
                                            value="{{ old('amount') }}"
                                            required
                                    >

                                    <span class="input-group-text">
                                        تومان
                                    </span>

                                </div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    نام صاحب حساب
                                </label>

                                <input
                                        type="text"
                                        name="account_holder_name"
                                        class="form-control"
                                        value="{{ old('account_holder_name', auth()->user()->name) }}"
                                        required
                                >

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    شماره کارت
                                </label>

                                <input
                                        type="text"
                                        name="card_number"
                                        class="form-control"
                                        maxlength="16"
                                        inputmode="numeric"
                                        value="{{ old('card_number') }}"
                                        placeholder="شماره کارت 16 رقمی"
                                >

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    شماره حساب
                                </label>

                                <input
                                        type="text"
                                        name="account_number"
                                        class="form-control"
                                        value="{{ old('account_number') }}"
                                >

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    شماره شبا
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        IR
                                    </span>

                                    <input
                                            type="text"
                                            name="sheba_number"
                                            class="form-control"
                                            maxlength="24"
                                            value="{{ old('sheba_number') }}"
                                            placeholder="24 رقم شماره شبا"
                                    >

                                </div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    توضیحات
                                </label>

                                <textarea
                                        name="description"
                                        class="form-control"
                                        rows="3"
                                >{{ old('description') }}</textarea>

                            </div>


                            <div class="d-flex gap-2">

                                <button
                                        type="submit"
                                        class="btn btn-sm btn-primary"
                                >
                                    <i class="bi bi-send"></i>
                                    ثبت درخواست
                                </button>

                                <a
                                        href="{{ route('wallet.withdrawals.index') }}"
                                        class="btn btn-sm btn-secondary"
                                >
                                    انصراف
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-admin-layout>