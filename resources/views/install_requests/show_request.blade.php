<x-admin-layout title="جزئیات درخواست">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    درخواست #{{ $installRequest->id }}
                </h4>

                <div class="text-muted">
                    جزئیات و زمان‌بندی درخواست
                </div>
            </div>

            <a
                    href="{{ route('admin.service_requests.index') }}"
                    class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-right"></i>
                بازگشت
            </a>

        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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

        <div class="row g-4">

            {{-- اطلاعات درخواست --}}

            <div class="col-lg-7">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">
                        <strong>
                            اطلاعات درخواست
                        </strong>
                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    کاربر
                                </small>

                                <strong>
                                    {{ $installRequest->user?->name ?? '---' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    موبایل
                                </small>

                                <strong>
                                    {{ $installRequest->user?->mobile ?? '---' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    دستگاه
                                </small>

                                <strong>
                                    {{ $installRequest->device_model }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    نوع درخواست
                                </small>

                                @php
                                    $typeLabels = [
                                        'installation' => 'نصب',
                                        'service' => 'سرویس',
                                        'repair' => 'تعمیر',
                                    ];
                                @endphp

                                <strong>
                                    {{ $typeLabels[$installRequest->request_type] ?? $installRequest->request_type }}
                                </strong>

                            </div>


                            <div class="col-12">

                                <small class="text-muted d-block">
                                    آدرس
                                </small>

                                <div>
                                    {{ $installRequest->address }}
                                </div>

                            </div>


                            <div class="col-12">

                                <small class="text-muted d-block">
                                    توضیحات
                                </small>

                                <div class="bg-light rounded p-3">
                                    {{ $installRequest->description }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- زمان‌بندی --}}

            <div class="col-lg-5">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-calendar-check text-primary"></i>

                            <strong>
                                زمان‌بندی درخواست
                            </strong>

                        </div>

                    </div>


                    <div class="card-body">

                        @if($installRequest->schedule)

                            <div class="alert alert-info">

                                <div class="fw-semibold mb-2">
                                    زمان‌بندی فعلی
                                </div>

                                <div>
                                    نصاب:
                                    {{ $installRequest->schedule->installer?->name ?? '---' }}
                                </div>

                                <div>
                                    تاریخ:
                                    {{ jdate($installRequest->schedule->scheduled_date)->format('Y/m/d') }}
                                </div>

                            </div>

                        @endif


                        <form
                                method="POST"
                                action="{{ route(
                                'admin.install_requests.schedule.request',
                                $installRequest
                            ) }}"
                        >

                            @csrf


                            <div class="mb-3">

                                <label class="form-label">
                                    انتخاب نصاب
                                </label>

                                <select
                                        name="installer_id"
                                        class="form-select"
                                        required
                                >

                                    <option value="">
                                        انتخاب کنید
                                    </option>

                                    @foreach($installers as $installer)

                                        <option
                                                value="{{ $installer->id }}"
                                                @selected(
                                                    $installRequest->schedule?->installer_id === $installer->user_id
                                                )
                                        >
                                            {{ $installer->user?->name ?? '---' }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    تاریخ مراجعه
                                </label>

                                <input
                                        type="text"
                                        name="scheduled_date"
                                        class="form-control"
                                        data-jdp
                                        value="{{
                                        old(
                                            'scheduled_date',
                                            $installRequest->schedule?->scheduled_date
                                                ? jdate($installRequest->schedule->scheduled_date)->format('Y/m/d')
                                                : ''
                                        )
                                    }}"
                                        placeholder="1405/06/22"
                                        required
                                >

                            </div>


                            {{-- Description --}}
                            <div class="mb-4">

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


                            <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                            >
                                <i class="bi bi-calendar-check me-1"></i>

                                {{ $installRequest->schedule
                                    ? 'ویرایش زمان‌بندی'
                                    : 'ثبت زمان‌بندی'
                                }}

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-admin-layout>