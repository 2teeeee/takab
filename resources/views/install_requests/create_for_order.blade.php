<x-admin-layout
        title="ثبت درخواست نصب / سرویس"
        header="ثبت درخواست نصب / سرویس"
>

    <div class="container py-4">

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-2">
                    <i class="bi bi-exclamation-triangle"></i>
                    لطفاً خطاهای زیر را بررسی کنید:
                </div>

                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-tools"></i>
                    ثبت درخواست نصب / سرویس
                </h5>

                <small class="text-muted">
                    ثبت درخواست برای سفارش شماره {{ $order->id }}
                </small>
            </div>

            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-right"></i>
                بازگشت به سفارش‌ها
            </a>

        </div>


        {{-- Order Information --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">
                <strong>
                    <i class="bi bi-receipt"></i>
                    اطلاعات سفارش
                </strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label text-muted">
                            شماره سفارش
                        </label>

                        <div class="fw-bold">
                            #{{ $order->id }}
                        </div>
                    </div>


                    <div class="col-md-3">
                        <label class="form-label text-muted">
                            مشتری
                        </label>

                        <div class="fw-bold">
                            {{ $order->user->name ?? '-' }}
                        </div>
                    </div>


                    <div class="col-md-3">
                        <label class="form-label text-muted">
                            موبایل
                        </label>

                        <div>
                            {{ $order->user->mobile ?? '-' }}
                        </div>
                    </div>


                    <div class="col-md-3">
                        <label class="form-label text-muted">
                            عمده فروش
                        </label>

                        <div class="fw-bold">
                            {{ $order->wholesaler->name ?? '-' }}
                        </div>
                    </div>


                    <div class="col-md-12">
                        <label class="form-label text-muted">
                            آدرس سفارش
                        </label>

                        <div class="border rounded p-3 bg-light">
                            {{ $order->address ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>
        </div>


        {{-- Installation Request Form --}}
        <form method="POST"
              action="{{ route('admin.install_requests.store_from_order', $order) }}">

            @csrf

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    <strong>
                        <i class="bi bi-tools"></i>
                        اطلاعات نصب / سرویس
                    </strong>
                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- Service Type --}}
                        <div class="col-md-4">

                            <label for="type" class="form-label">
                                نوع درخواست
                                <span class="text-danger">*</span>
                            </label>

                            <select name="type"
                                    id="type"
                                    class="form-select @error('type') is-invalid @enderror"
                                    required>

                                <option value="installation"
                                        @selected(old('type') === 'installation')>
                                    نصب
                                </option>

                                <option value="service"
                                        @selected(old('type') === 'service')>
                                    سرویس
                                </option>

                            </select>

                            @error('type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        <div class="col-md-4">
                            <label class="form-label">تاریخ نصب</label>
                            <input type="date" name="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" required>

                            @error('scheduled_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- Device Model --}}
                        <div class="col-md-4">

                            <label for="device_model" class="form-label">
                                مدل دستگاه
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="device_model"
                                   id="device_model"
                                   class="form-control @error('device_model') is-invalid @enderror"
                                   value="{{ old('device_model', $deviceModel) }}"
                                   placeholder="مدل دستگاه"
                                   required>

                            @error('device_model')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Serial Number --}}
                        <div class="col-md-6">

                            <label for="serial_number" class="form-label">
                                شماره سریال دستگاه
                            </label>

                            <input type="text"
                                   name="serial_number"
                                   id="serial_number"
                                   class="form-control @error('serial_number') is-invalid @enderror"
                                   value="{{ old('serial_number') }}"
                                   placeholder="شماره سریال">

                            @error('serial_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Installer --}}
                        <div class="col-md-6">

                            <label for="installer_id" class="form-label">
                                نصاب / سرویس‌کار
                                <span class="text-danger">*</span>
                            </label>

                            <select name="installer_id"
                                    id="installer_id"
                                    class="form-select @error('installer_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    انتخاب نصاب
                                </option>

                                @foreach($installers as $installer)

                                    <option
                                            value="{{ $installer->id }}"
                                            @selected($installer->id == $defaultInstallerId)
                                    >
                                        {{ $installer->user->name }}
                                        - {{ $installer->user->mobile }}

                                    </option>

                                @endforeach

                            </select>

                            @error('installer_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            @if($installers->isEmpty())
                                <div class="form-text text-danger">
                                    برای عمده فروش این سفارش هنوز نصاب فعالی ثبت نشده است.
                                </div>
                            @else
                                <div class="form-text">
                                    فقط نصاب‌های مرتبط با عمده فروش این سفارش نمایش داده می‌شوند.
                                </div>
                            @endif

                        </div>


                        {{-- Address --}}
                        <div class="col-md-12">

                            <label for="address" class="form-label">
                                آدرس محل نصب / سرویس
                                <span class="text-danger">*</span>
                            </label>

                            <textarea name="address"
                                      id="address"
                                      rows="4"
                                      class="form-control @error('address') is-invalid @enderror"
                                      placeholder="آدرس کامل محل نصب یا سرویس..."
                                      required>{{ old('address', $order->address ?? '') }}</textarea>

                            @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="col-md-12">

                            <label for="description" class="form-label">
                                توضیحات
                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="توضیحات مربوط به نصب یا سرویس...">{{ old('description') }}</textarea>

                            @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="card-footer d-flex justify-content-between">

                    <a href="{{ route('admin.orders.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>
                        انصراف

                    </a>


                    <button type="submit"
                            class="btn btn-primary"
                            @disabled($installers->isEmpty())>

                        <i class="bi bi-check-circle"></i>
                        ثبت درخواست

                    </button>

                </div>

            </div>

        </form>

    </div>

</x-admin-layout>