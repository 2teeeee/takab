<x-admin-layout
        title="گزارش انجام کار"
        header="گزارش انجام کار"
>

    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif


        <div class="card shadow-sm">

            <div class="card-header">
                <strong>
                    <i class="bi bi-clipboard-check"></i>
                    ثبت گزارش انجام کار
                </strong>
            </div>


            <div class="card-body">

                {{-- Customer --}}

                <div class="row mb-4">

                    <div class="col-md-6">

                        <div class="border rounded p-3">

                            <h6 class="fw-bold">
                                اطلاعات مشتری
                            </h6>

                            <div class="mb-2">
                                <strong>نام:</strong>

                                {{ $install_schedule->installRequest->user?->name ?? '-' }}
                            </div>

                            <div>
                                <strong>موبایل:</strong>

                                {{ $install_schedule->installRequest->user?->mobile ?? '-' }}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="border rounded p-3">

                            <h6 class="fw-bold">
                                اطلاعات دستگاه
                            </h6>

                            <div class="mb-2">

                                <strong>
                                    مدل:
                                </strong>

                                {{ $install_schedule->installRequest->device_model }}

                            </div>

                            <div>

                                <strong>
                                    سریال:
                                </strong>

                                {{ $install_schedule->installRequest->serial_number ?? '-' }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Address --}}

                <div class="alert alert-light border">

                    <strong>
                        <i class="bi bi-geo-alt"></i>
                        آدرس:
                    </strong>

                    <div class="mt-2">
                        {{ $install_schedule->installRequest->address }}
                    </div>

                </div>


                {{-- Schedule --}}

                <div class="mb-4">

                    <strong>
                        تاریخ انجام:
                    </strong>

                    {{ $install_schedule->scheduled_date?->format('Y/m/d') }}

                </div>


                {{-- Report Form --}}

                <form
                        method="POST"
                        action="{{ route(
                        'installer.install_schedules.report.store',
                        $install_schedule
                    ) }}"
                >

                    @csrf


                    {{-- Completed --}}

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            وضعیت انجام کار
                        </label>


                        <div class="row g-2">

                            <div class="col-md-6">

                                <div class="form-check border rounded p-3">

                                    <input
                                            class="form-check-input"
                                            type="radio"
                                            name="completed"
                                            id="completed_yes"
                                            value="1"
                                            @checked(
                                                old(
                                                    'completed',
                                                    optional($install_schedule->report)->completed
                                                ) == 1
                                            )
                                    >

                                    <label
                                            class="form-check-label"
                                            for="completed_yes"
                                    >
                                        <i class="bi bi-check-circle text-success"></i>

                                        کار انجام شد

                                    </label>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-check border rounded p-3">

                                    <input
                                            class="form-check-input"
                                            type="radio"
                                            name="completed"
                                            id="completed_no"
                                            value="0"
                                            @checked(
                                                old(
                                                    'completed',
                                                    optional($install_schedule->report)->completed
                                                ) === 0
                                                && $install_schedule->report
                                            )
                                    >

                                    <label
                                            class="form-check-label"
                                            for="completed_no"
                                    >
                                        <i class="bi bi-x-circle text-danger"></i>

                                        کار انجام نشد

                                    </label>

                                </div>

                            </div>

                        </div>


                        @error('completed')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>


                    {{-- Description --}}

                    <div class="mb-4">

                        <label
                                for="description"
                                class="form-label fw-bold"
                        >
                            توضیحات
                        </label>


                        <textarea
                                name="description"
                                id="description"
                                rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="توضیحات مربوط به انجام کار، مشکل دستگاه، قطعات مصرفی یا علت انجام نشدن کار را وارد کنید..."
                        >{{ old(
                            'description',
                            optional($install_schedule->report)->description
                        ) }}</textarea>


                        @error('description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>


                    {{-- Actions --}}

                    <div class="d-flex gap-2">

                        <button
                                type="submit"
                                class="btn btn-primary"
                        >
                            <i class="bi bi-check-lg"></i>

                            ثبت گزارش

                        </button>


                        <a
                                href="{{ route('installer.orders.index') }}"
                                class="btn btn-secondary"
                        >
                            بازگشت
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-admin-layout>