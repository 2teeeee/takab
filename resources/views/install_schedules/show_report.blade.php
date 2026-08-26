<x-admin-layout title="مشاهده گزارش نصاب" header="مشاهده گزارش نصاب">

    <div class="container py-4">

        {{-- دکمه بازگشت --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <a href="{{ route('installer.orders.index') }}"
               class="btn btn-sm btn-secondary">

                <i class="bi bi-chevron-double-right"></i>
                بازگشت به لیست گزارش‌ها

            </a>

        </div>


        {{-- وضعیت گزارش --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check"></i>
                    وضعیت انجام کار
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- وضعیت انجام --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            وضعیت انجام کار
                        </label>

                        <div>

                            @if($report->completed)

                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle"></i>
                                    انجام شده
                                </span>

                            @else

                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-x-circle"></i>
                                    انجام نشده
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- شماره گزارش --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            شماره گزارش
                        </label>

                        <div class="fw-bold">
                            #{{ $report->id }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- اطلاعات نصاب --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-person-gear"></i>
                    اطلاعات نصاب
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- نام --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            نام نصاب
                        </label>

                        <div class="fw-bold">
                            {{ $report->installer?->user->name ?? '-' }}
                        </div>

                    </div>


                    {{-- موبایل --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            شماره موبایل
                        </label>

                        <div>
                            {{ $report->installer?->user->mobile ?? '-' }}
                        </div>

                    </div>


                    {{-- کد ملی --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            کد ملی
                        </label>

                        <div>
                            {{ $report->installer?->user->national_code ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- اطلاعات برنامه نصب --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-calendar-check"></i>
                    اطلاعات برنامه نصب
                </h5>

            </div>

            <div class="card-body">

                @if($report->schedule)

                    <div class="row">

                        {{-- شناسه برنامه --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                شماره برنامه
                            </label>

                            <div class="fw-bold">
                                #{{ $report->schedule->id }}
                            </div>

                        </div>


                        {{-- تاریخ --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                تاریخ برنامه
                            </label>

                            <div>
                                {{ jdate($report->schedule->scheduled_date)->format('Y/m/d') ?? '-' }}
                            </div>

                        </div>


                        {{-- وضعیت برنامه --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                وضعیت برنامه
                            </label>

                            <div>
                                {{ $report->schedule->status ?? '-' }}
                            </div>

                        </div>

                    </div>

                @else

                    <div class="alert alert-warning mb-0">

                        <i class="bi bi-exclamation-triangle"></i>

                        اطلاعات برنامه نصب یافت نشد.

                    </div>

                @endif

            </div>

        </div>


        {{-- توضیحات گزارش --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-chat-left-text"></i>
                    توضیحات نصاب
                </h5>

            </div>

            <div class="card-body">

                @if($report->description)

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e($report->description)) !!}

                    </div>

                @else

                    <div class="text-muted">
                        توضیحی توسط نصاب ثبت نشده است.
                    </div>

                @endif

            </div>

        </div>


        {{-- اطلاعات زمانی --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-clock-history"></i>
                    اطلاعات ثبت گزارش
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- تاریخ ایجاد --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            تاریخ ثبت گزارش
                        </label>

                        <div>
                            {{ jdate($report->created_at)->format('Y/m/d') ?? '-' }}
                        </div>

                    </div>


                    {{-- آخرین بروزرسانی --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            آخرین بروزرسانی
                        </label>

                        <div>
                            {{ jdate($report->updated_at)->format('Y/m/d') ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- عملیات --}}
        <div class="d-flex justify-content-between">



        </div>

    </div>

</x-admin-layout>