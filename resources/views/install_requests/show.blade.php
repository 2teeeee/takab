<x-admin-layout
        title="جزئیات درخواست سرویس"
        header="جزئیات درخواست سرویس"
>

    <div class="container py-4">

        {{-- Messages --}}

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


        {{-- Back --}}

        <div class="mb-3">

            <a
                    href="{{ route('admin.service_requests.index') }}"
                    class="btn btn-sm btn-secondary"
            >
                <i class="bi bi-arrow-right"></i>
                بازگشت به لیست درخواست‌ها
            </a>

        </div>


        {{-- Request Information --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <strong>
                    اطلاعات درخواست
                </strong>

                <span class="text-muted">
                    #{{ $installRequest->id }}
                </span>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Customer --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-person"></i>
                                اطلاعات مشتری
                            </h6>

                            <div class="mb-2">
                                <strong>نام:</strong>

                                {{ $installRequest->user?->name ?? '-' }}
                            </div>

                            <div class="mb-2">
                                <strong>موبایل:</strong>

                                {{ $installRequest->user?->mobile ?? '-' }}
                            </div>

                            <div>
                                <strong>کد ملی:</strong>

                                {{ $installRequest->user?->national_code ?? '-' }}
                            </div>

                        </div>

                    </div>


                    {{-- Device --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-cpu"></i>
                                اطلاعات دستگاه
                            </h6>

                            <div class="mb-2">

                                <strong>
                                    مدل دستگاه:
                                </strong>

                                {{ $installRequest->device_model }}

                            </div>


                            <div>

                                <strong>
                                    شماره سریال:
                                </strong>

                                {{ $installRequest->serial_number ?? '-' }}

                            </div>

                        </div>

                    </div>


                    {{-- Address --}}

                    <div class="col-12">

                        <div class="border rounded p-3">

                            <h6 class="fw-bold mb-3">

                                <i class="bi bi-geo-alt"></i>

                                آدرس محل انجام کار

                            </h6>

                            <div>
                                {{ $installRequest->address }}
                            </div>

                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4">

                        <strong>
                            وضعیت درخواست:
                        </strong>

                        <div class="mt-2">

                            @switch($installRequest->status)

                                @case('pending')

                                    <span class="badge bg-warning text-dark">
                                        در انتظار
                                    </span>

                                    @break

                                @case('scheduled')

                                    <span class="badge bg-primary">
                                        زمان‌بندی شده
                                    </span>

                                    @break

                                @case('installed')

                                    <span class="badge bg-success">
                                        نصب شده
                                    </span>

                                    @break

                                @case('serviced')

                                    <span class="badge bg-success">
                                        سرویس شده
                                    </span>

                                    @break

                                @case('cancelled')

                                    <span class="badge bg-danger">
                                        لغو شده
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-secondary">
                                        {{ $installRequest->status }}
                                    </span>

                            @endswitch

                        </div>

                    </div>


                    {{-- Created --}}

                    <div class="col-md-4">

                        <strong>
                            تاریخ ثبت:
                        </strong>

                        <div class="mt-2 text-muted">

                            {{ jdate($installRequest->created_at)->format('Y/m/d') ?? '-' }}

                        </div>

                    </div>


                    {{-- Installation Date --}}

                    <div class="col-md-4">

                        <strong>
                            تاریخ انجام:
                        </strong>

                        <div class="mt-2 text-muted">

                            {{ jdate($installRequest->installation_date)->format('Y/m/d') ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Schedules --}}

        <div class="card shadow-sm">

            <div class="card-header">

                <strong>

                    <i class="bi bi-calendar-check"></i>

                    زمان‌بندی و نصاب

                </strong>

            </div>


            <div class="card-body">

                @forelse($installRequest->schedules as $schedule)

                    @php
                        $isToday = $schedule->scheduled_date?->isToday();
                    @endphp


                    <div
                            class="border rounded p-3 mb-3
                        {{ $isToday ? 'border-danger bg-light' : '' }}"
                    >

                        <div class="row g-3 align-items-center">


                            {{-- Date --}}

                            <div class="col-md-3">

                                <strong>
                                    تاریخ:
                                </strong>

                                <div class="mt-1">

                                    @if($isToday)

                                        <span class="badge bg-danger">
                                            امروز
                                        </span>

                                        <br>

                                    @endif

                                    {{ jdate($schedule->scheduled_date)->format('Y/m/d') ?? '-' }}

                                </div>

                            </div>


                            {{-- Installer --}}

                            <div class="col-md-4">

                                <strong>
                                    نصاب:
                                </strong>

                                @if($schedule->installer)

                                    <div class="mt-1">

                                        <i class="bi bi-person-gear"></i>

                                        {{ $schedule->installer->user->name }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $schedule->installer->user->mobile }}

                                    </small>

                                @else

                                    <div class="text-danger mt-1">
                                        نصاب تعیین نشده
                                    </div>

                                @endif

                            </div>


                            {{-- Schedule Status --}}

                            <div class="col-md-3">

                                <strong>
                                    وضعیت:
                                </strong>

                                <div class="mt-1">

                                    @switch($schedule->status)

                                        @case('waiting')

                                            <span class="badge bg-warning text-dark">
                                                در انتظار انجام
                                            </span>

                                            @break

                                        @case('done')

                                            <span class="badge bg-success">
                                                انجام شده
                                            </span>

                                            @break

                                        @case('cancelled')

                                            <span class="badge bg-danger">
                                                لغو شده
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">
                                                {{ $schedule->status }}
                                            </span>

                                    @endswitch

                                </div>

                            </div>


                            {{-- Created --}}

                            <div class="col-md-2">

                                <small class="text-muted">

                                    ثبت شده:

                                    <br>

                                    {{ jdate($schedule->created_at)->format('Y/m/d') }}

                                </small>

                            </div>

                            @if($schedule->report)

                                {{-- Description --}}

                                <div class="col-md-2 pt-2 border-top">

                                    <strong>
                                        توضیحات نصاب
                                    </strong>

                                    <div class="mt-1">

                                        @if($schedule->report->description)

                                            <div>

                                                {!! nl2br(e($schedule->report->description)) !!}

                                            </div>

                                        @else

                                            <div class="text-muted">
                                                توضیحی توسط نصاب ثبت نشده است.
                                            </div>

                                        @endif

                                    </div>

                                </div>

                            @endif
                            
                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-4">

                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>

                        برای این درخواست هنوز زمان‌بندی ثبت نشده است.

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-admin-layout>