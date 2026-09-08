<x-profile-layout
        title="ثبت درخواست سرویس"
>

    <div class="container py-4">

        {{-- Success --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- Error --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ session('error') }}
            </div>
        @endif


        <form
                method="POST"
                action="{{ route('profile.service-requests.store') }}"
        >

            @csrf


            {{-- ===================================================== --}}
            {{-- Request Type --}}
            {{-- ===================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-tools fs-5 text-secondary"></i>

                        <div>

                            <h6 class="mb-0">
                                نوع درخواست
                            </h6>

                            <small class="text-muted">
                                نوع خدمات مورد نیاز خود را انتخاب کنید
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- Installation --}}
                        <div class="col-12 col-md-4">

                            <label
                                    class="request-type-card
                                       border rounded p-3
                                       h-100 d-block"
                            >

                                <input
                                        type="radio"
                                        name="request_type"
                                        value="installation"
                                        class="form-check-input me-2"
                                        {{ old('request_type') === 'installation' ? 'checked' : '' }}
                                        required
                                >

                                <i class="bi bi-house-add text-primary fs-4"></i>

                                <div class="fw-semibold mt-2">
                                    نصب دستگاه
                                </div>

                                <small class="text-muted">
                                    درخواست نصب و راه‌اندازی دستگاه
                                </small>

                            </label>

                        </div>


                        {{-- Service --}}
                        <div class="col-12 col-md-4">

                            <label
                                    class="request-type-card
                                       border rounded p-3
                                       h-100 d-block"
                            >

                                <input
                                        type="radio"
                                        name="request_type"
                                        value="service"
                                        class="form-check-input me-2"
                                        {{ old('request_type', 'service') === 'service' ? 'checked' : '' }}
                                        required
                                >

                                <i class="bi bi-gear text-success fs-4"></i>

                                <div class="fw-semibold mt-2">
                                    سرویس دستگاه
                                </div>

                                <small class="text-muted">
                                    درخواست سرویس و بررسی دوره‌ای دستگاه
                                </small>

                            </label>

                        </div>


                        {{-- Repair --}}
                        <div class="col-12 col-md-4">

                            <label
                                    class="request-type-card
                                       border rounded p-3
                                       h-100 d-block"
                            >

                                <input
                                        type="radio"
                                        name="request_type"
                                        value="repair"
                                        class="form-check-input me-2"
                                        {{ old('request_type') === 'repair' ? 'checked' : '' }}
                                        required
                                >

                                <i class="bi bi-wrench-adjustable text-danger fs-4"></i>

                                <div class="fw-semibold mt-2">
                                    تعمیر دستگاه
                                </div>

                                <small class="text-muted">
                                    درخواست تعمیر و رفع مشکل دستگاه
                                </small>

                            </label>

                        </div>

                    </div>


                    @error('request_type')

                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>

                    @enderror

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Device --}}
            {{-- ===================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-cpu fs-5 text-secondary"></i>

                        <div>

                            <h6 class="mb-0">
                                انتخاب دستگاه
                            </h6>

                            <small class="text-muted">
                                دستگاه مورد نظر خود را انتخاب کنید
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    @if($devices->count())

                        <div class="row g-3">

                            @foreach($devices as $device)
                                <label class="device-card border rounded p-3 d-block mb-3">
                                    <div class="form-check">
                                        <input
                                                class="form-check-input"
                                                type="radio"
                                                name="device_id"
                                                value="{{ $device['item']->id }}"
                                                required
                                        >

                                        <div class="d-flex align-items-center gap-3">
                                            @if($device['product']->image)
                                                <img
                                                        src="{{ asset('storage/' . $device['product']->image) }}"
                                                        alt="{{ $device['device_model'] }}"
                                                        width="70"
                                                        height="70"
                                                        class="rounded object-fit-cover"
                                                >
                                            @endif

                                            <div>
                                                <div class="fw-bold">
                                                    {{ $device['device_model'] }}
                                                </div>

                                                <div class="text-muted small">
                                                    سفارش #{{ $device['order']->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                        </div>


                        @error('device_id')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                        @enderror

                    @else

                        <div class="alert alert-warning mb-0">

                            <i class="bi bi-info-circle me-1"></i>

                            در حال حاضر دستگاهی برای حساب کاربری شما ثبت نشده است.

                        </div>

                    @endif

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Address --}}
            {{-- ===================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-geo-alt fs-5 text-secondary"></i>

                        <div>

                            <h6 class="mb-0">
                                آدرس
                            </h6>

                            <small class="text-muted">
                                آدرس محل نصب یا ارائه خدمات
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <label
                            for="address"
                            class="form-label"
                    >
                        آدرس کامل
                        <span class="text-danger">*</span>
                    </label>


                    <textarea
                            name="address"
                            id="address"
                            rows="4"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="آدرس کامل محل را وارد کنید"
                            required
                    >{{ old('address') }}</textarea>


                    @error('address')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                    @enderror

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Description --}}
            {{-- ===================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-chat-left-text fs-5 text-secondary"></i>

                        <div>

                            <h6 class="mb-0">
                                توضیحات درخواست
                            </h6>

                            <small class="text-muted">
                                مشکل یا توضیحات مورد نیاز را وارد کنید
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <label
                            for="description"
                            class="form-label"
                    >
                        توضیحات
                        <span class="text-danger">*</span>
                    </label>


                    <textarea
                            name="description"
                            id="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="مثلاً دستگاه روشن نمی‌شود، نیاز به سرویس دارد یا درخواست نصب دستگاه..."
                            required
                    >{{ old('description') }}</textarea>


                    @error('description')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                    @enderror

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Submit --}}
            {{-- ===================================================== --}}

            @if($devices->count())

                <div class="d-flex justify-content-end">

                    <button
                            type="submit"
                            class="btn btn-success px-4"
                    >

                        <i class="bi bi-send me-1"></i>

                        ثبت درخواست

                    </button>

                </div>

            @endif

        </form>

    </div>


    @push('styles')

        <style>

            .request-type-card,
            .device-card {
                cursor: pointer;
                transition: all .2s ease;
            }


            .request-type-card:hover,
            .device-card:hover {
                border-color: var(--bs-primary) !important;
                background-color: var(--bs-light);
            }


            .request-type-card:has(input:checked),
            .device-card:has(input:checked) {
                border-color: var(--bs-primary) !important;
                background-color: rgba(
                        var(--bs-primary-rgb),
                        .05
                );
            }


            .request-type-card input,
            .device-card input {
                cursor: pointer;
            }

        </style>

    @endpush

</x-profile-layout>