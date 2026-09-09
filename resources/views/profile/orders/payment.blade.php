<x-profile-layout
        title="پرداخت سفارش"
>

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-12 col-md-8 col-lg-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-bottom py-3">

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-credit-card fs-5 text-secondary"></i>

                            <div>

                                <h6 class="mb-0">
                                    پرداخت سفارش
                                </h6>

                                <small class="text-muted">
                                    اطلاعات سفارش را بررسی و پرداخت را تأیید کنید.
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        {{-- Order Number --}}
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                            <span class="text-muted">
                                شماره سفارش
                            </span>

                            <strong>
                                #{{ $order->id }}
                            </strong>

                        </div>


                        {{-- Customer --}}
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                            <span class="text-muted">
                                خریدار
                            </span>

                            <strong>
                                {{ $order->user->name }}
                            </strong>

                        </div>


                        {{-- Mobile --}}
                        @if($order->user->mobile)

                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                                <span class="text-muted">
                                    شماره موبایل
                                </span>

                                <span>
                                    {{ $order->user->mobile }}
                                </span>

                            </div>

                        @endif


                        {{-- Amount --}}
                        <div class="d-flex justify-content-between align-items-center py-3">

                            <span class="fw-semibold">
                                مبلغ قابل پرداخت
                            </span>

                            <strong class="fs-4">
                                {{ number_format($order->final_total) }}
                                <small class="fs-6 text-muted">
                                    تومان
                                </small>
                            </strong>

                        </div>

                    </div>


                    <div class="card-footer bg-white border-top p-3">

                        <form
                                method="POST"
                                action="{{ route('profile.orders.payment.pay', $order) }}"
                        >

                            @csrf

                            <button
                                    type="submit"
                                    class="btn btn-success w-100"
                            >

                                <i class="bi bi-credit-card me-1"></i>

                                تأیید و پرداخت

                            </button>

                        </form>

                    </div>

                </div>


                <div class="text-center mt-3">

                    <small class="text-muted">
                        پس از تأیید، به درگاه امن بانکی منتقل خواهید شد.
                    </small>

                </div>

            </div>

        </div>

    </div>

</x-profile-layout>