<x-admin-layout>

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">تنظیمات پورسانت و تخفیف</h4>
                <p class="text-muted mb-0">
                    تعیین مبالغ پورسانت و تخفیف به ازای هر محصول
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">

            <div class="card-header bg-white">
                <h5 class="mb-0">مبالغ قابل تنظیم</h5>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('admin.settings.commissions.update') }}">

                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- پورسانت عمده فروش --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                پورسانت عمده‌فروش
                            </label>

                            <div class="input-group">
                                <input
                                        type="number"
                                        name="wholesaler_commission"
                                        class="form-control"
                                        min="0"
                                        step="1"
                                        value="{{ old('wholesaler_commission', $settings['wholesaler_commission'] ?? 1000000) }}"
                                        required
                                >
                                <span class="input-group-text">تومان</span>
                            </div>

                            <small class="text-muted">
                                مبلغ پورسانت عمده‌فروش به ازای هر محصول
                            </small>
                        </div>

                        {{-- پورسانت فروشگاه --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                پورسانت فروشگاه
                            </label>

                            <div class="input-group">
                                <input
                                        type="number"
                                        name="store_commission"
                                        class="form-control"
                                        min="0"
                                        step="1"
                                        value="{{ old('store_commission', $settings['store_commission'] ?? 1000000) }}"
                                        required
                                >
                                <span class="input-group-text">تومان</span>
                            </div>

                            <small class="text-muted">
                                مبلغ پایه پورسانت فروشگاه به ازای هر محصول
                            </small>
                        </div>

                        {{-- پورسانت معرف --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                پورسانت معرف
                            </label>

                            <div class="input-group">
                                <input
                                        type="number"
                                        name="referral_commission"
                                        class="form-control"
                                        min="0"
                                        step="1"
                                        value="{{ old('referral_commission', $settings['referral_commission'] ?? 1000000) }}"
                                        required
                                >
                                <span class="input-group-text">تومان</span>
                            </div>

                            <small class="text-muted">
                                مبلغ پورسانت معرف به ازای هر محصول
                            </small>
                        </div>

                        {{-- پورسانت نصاب --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                پورسانت نصاب
                            </label>

                            <div class="input-group">
                                <input
                                        type="number"
                                        name="installer_commission"
                                        class="form-control"
                                        min="0"
                                        step="1"
                                        value="{{ old('installer_commission', $settings['installer_commission'] ?? 1000000) }}"
                                        required
                                >
                                <span class="input-group-text">تومان</span>
                            </div>

                            <small class="text-muted">
                                مبلغ پورسانت نصاب به ازای هر نصب محصول موفق
                            </small>
                        </div>

                        {{-- تخفیف مشتری --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                تخفیف مشتری
                            </label>

                            <div class="input-group">
                                <input
                                        type="number"
                                        name="customer_discount"
                                        class="form-control"
                                        min="0"
                                        step="1"
                                        value="{{ old('customer_discount', $settings['customer_discount'] ?? 1000000) }}"
                                        required
                                >
                                <span class="input-group-text">تومان</span>
                            </div>

                            <small class="text-muted">
                                مبلغ تخفیف مشتری به ازای هر محصول با کد معرف معتبر
                            </small>
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            ذخیره تنظیمات
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <div class="alert alert-info mt-4">
            <strong>توجه:</strong>
            مبالغ واردشده بر حسب تومان هستند و برای هر محصول محاسبه می‌شوند.
            همچنین منطق فعلی دو برابر شدن پورسانت فروشگاه برای نقش‌های غیر از
            <code>user</code> در محاسبات قبلی حفظ می‌شود.
        </div>

    </div>

</x-admin-layout>
