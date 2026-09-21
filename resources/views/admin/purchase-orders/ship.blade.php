<x-admin-layout title="ثبت ارسال سفارش خرید">

    <div class="container py-4">

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">
                    ثبت ارسال سفارش
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-4">
                    <div>
                        <strong>شماره سفارش:</strong>
                        {{ $purchaseOrder->order_number }}
                    </div>

                    <div>
                        <strong>تاریخ سفارش:</strong>
                        {{ jdate($purchaseOrder->order_date)->format('Y/m/d') }}
                    </div>

                    <div>
                        <strong>مبلغ سفارش:</strong>
                        {{ number_format($purchaseOrder->total_amount) }}
                    </div>
                </div>

                <form method="POST"
                      action="{{ route(
                          'admin.purchase-orders.ship.store',
                          $purchaseOrder
                      ) }}">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                تاریخ ارسال
                            </label>

                            <input type="text"
                                   name="shipped_at"
                                   class="form-control"
                                   data-jdp
                                   data-jdp-only-date
                                   value="{{ old('shipped_at') }}"
                                   placeholder="تاریخ ارسال"
                                   required>

                            @error('shipped_at')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                نوع خودرو
                            </label>

                            <input type="text"
                                   name="vehicle_type"
                                   class="form-control @error('vehicle_type') is-invalid @enderror"
                                   value="{{ old('vehicle_type') }}"
                                   placeholder="مثلاً کامیون، وانت، نیسان">

                            @error('vehicle_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                پلاک خودرو
                            </label>

                            <input type="text"
                                   name="vehicle_plate"
                                   class="form-control @error('vehicle_plate') is-invalid @enderror"
                                   value="{{ old('vehicle_plate') }}"
                                   placeholder="شماره پلاک">

                            @error('vehicle_plate')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                نام راننده
                            </label>

                            <input type="text"
                                   name="driver_name"
                                   class="form-control @error('driver_name') is-invalid @enderror"
                                   value="{{ old('driver_name') }}"
                                   required>

                            @error('driver_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                شماره موبایل راننده
                            </label>

                            <input type="text"
                                   name="driver_mobile"
                                   class="form-control @error('driver_mobile') is-invalid @enderror"
                                   value="{{ old('driver_mobile') }}"
                                   placeholder="09xxxxxxxxx">

                            @error('driver_mobile')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                توضیحات ارسال
                            </label>

                            <textarea name="shipping_note"
                                      class="form-control @error('shipping_note') is-invalid @enderror"
                                      rows="4"
                                      placeholder="توضیحات مربوط به ارسال، تعداد بسته‌ها، وضعیت کالا و ...">{{ old('shipping_note') }}</textarea>

                            @error('shipping_note')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-success">
                            <i class="bi bi-truck"></i>
                            ثبت ارسال
                        </button>

                        <a href="{{ url()->previous() }}"
                           class="btn btn-outline-secondary">
                            انصراف
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </div>

</x-admin-layout>