<x-profile-layout
        title="ثبت فروش به مشتری"
>

    <form
            method="POST"
            action="{{ route('profile.customer.sale.store') }}"
    >
        @csrf

        <div class="row g-4">

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

            {{-- Customer Information --}}
            <div class="col-12 col-lg-5">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-person fs-5 text-secondary"></i>

                            <div>
                                <h6 class="mb-0">
                                    اطلاعات مشتری
                                </h6>

                                <small class="text-muted">
                                    اطلاعات خریدار را وارد کنید
                                </small>
                            </div>

                        </div>
                    </div>

                    <div class="card-body">

                        {{-- Mobile --}}
                        <div class="mb-3">

                            <label
                                    for="mobile"
                                    class="form-label fw-semibold"
                            >
                                شماره موبایل
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                    type="tel"
                                    name="mobile"
                                    id="mobile"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile') }}"
                                    maxlength="11"
                                    inputmode="numeric"
                                    autocomplete="tel"
                                    placeholder="مثلاً 09123456789"
                                    required
                            >

                            @error('mobile')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Name --}}
                        <div class="mb-3">

                            <label
                                    for="name"
                                    class="form-label fw-semibold"
                            >
                                نام مشتری
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    placeholder="نام و نام خانوادگی"
                                    required
                            >

                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- National Code --}}
                        <div class="mb-3">

                            <label
                                    for="national_code"
                                    class="form-label fw-semibold"
                            >
                                کد ملی
                            </label>

                            <input
                                    type="text"
                                    name="national_code"
                                    id="national_code"
                                    class="form-control @error('national_code') is-invalid @enderror"
                                    value="{{ old('national_code') }}"
                                    maxlength="10"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    placeholder="کد ملی ۱۰ رقمی"
                            >

                            @error('national_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Address --}}
                        <div class="mb-0">

                            <label
                                    for="address"
                                    class="form-label fw-semibold"
                            >
                                آدرس
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                    name="address"
                                    id="address"
                                    rows="5"
                                    class="form-control @error('address') is-invalid @enderror"
                                    autocomplete="street-address"
                                    placeholder="آدرس کامل مشتری را وارد کنید"
                                    required
                            >{{ old('address') }}</textarea>

                            @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                آدرس کامل برای ثبت و پیگیری فروش الزامی است.
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Products --}}
            <div class="col-12 col-lg-7">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-bottom py-3">

                        <div class="d-flex align-items-center justify-content-between gap-3">

                            <div class="d-flex align-items-center gap-2">

                                <i class="bi bi-box-seam fs-5 text-secondary"></i>

                                <div>
                                    <h6 class="mb-0">
                                        انتخاب محصولات
                                    </h6>

                                    <small class="text-muted">
                                        تعداد محصولات مورد فروش را مشخص کنید
                                    </small>
                                </div>

                            </div>

                            @if($products->count())
                                <span class="badge text-bg-light">
                                    {{ $products->count() }} محصول
                                </span>
                            @endif

                        </div>

                    </div>


                    <div class="card-body p-0">

                        {{-- General Products Error --}}
                        @error('products')
                        <div class="alert alert-danger rounded-0 mb-0">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                        @enderror


                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                <tr>

                                    <th></th>

                                    <th class="px-3 py-3">
                                        محصول
                                    </th>

                                    <th
                                            class="text-center"
                                            style="width: 140px;"
                                    >
                                        تعداد
                                    </th>

                                </tr>

                                </thead>


                                <tbody>

                                @forelse($products as $product)

                                    <tr>

                                        <td class="text-center">
                                            <img src="{{ asset('storage/'.$product->small_image_name) }}"
                                                 width="60">
                                        </td>

                                        <td class="px-3">

                                            <div class="fw-semibold">
                                                {{ $product->title }}
                                            </div>

                                        </td>


                                        <td class="px-3">

                                            <input
                                                    type="number"
                                                    name="products[{{ $product->id }}]"
                                                    class="form-control product-quantity text-center"
                                                    value="{{ old('products.' . $product->id, 0) }}"
                                                    min="0"
                                                    max="{{ $product->stock }}"
                                                    step="1"
                                                    inputmode="numeric"
                                            >

                                            @error('products.' . $product->id)
                                            <div class="text-danger small mt-1">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                                colspan="2"
                                                class="text-center text-muted py-5"
                                        >

                                            <div class="mb-2">
                                                <i class="bi bi-box-seam fs-1"></i>
                                            </div>

                                            <div class="fw-semibold mb-1">
                                                محصولی برای فروش موجود نیست.
                                            </div>

                                            <small>
                                                در حال حاضر محصولی با موجودی قابل فروش وجود ندارد.
                                            </small>

                                        </td>

                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>


                    @if($products->count())

                        <div class="card-footer bg-white border-top p-3">

                            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3">

                                <div class="text-muted small">

                                    <i class="bi bi-info-circle me-1"></i>

                                    برای محصول موردنظر، تعداد فروش را وارد کنید.
                                    مقدار صفر یعنی محصول انتخاب نشده است.

                                </div>

                                <button
                                        type="submit"
                                        class="btn btn-sm btn-success"
                                >
                                    <i class="bi bi-check2-circle me-1"></i>
                                    ثبت فروش
                                </button>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </form>

</x-profile-layout>