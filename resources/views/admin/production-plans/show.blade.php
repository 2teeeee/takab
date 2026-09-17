<x-admin-layout title="جزئیات برنامه تولید">

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    جزئیات برنامه تولید
                </h4>

                <div class="text-muted small">
                    برنامه شماره #{{ $productionPlan->id }}
                </div>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('admin.production-plans.index') }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-right"></i>
                    بازگشت
                </a>

                @if($productionPlan->status === 'draft')
                    <a href="#"
                       class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i>
                        ویرایش
                    </a>
                @endif

            </div>

        </div>


        {{-- Production Plan Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-data me-1"></i>
                    اطلاعات برنامه تولید
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Product --}}
                    <div class="col-md-6 col-lg-3">

                        <div class="text-muted small mb-1">
                            دستگاه تولیدی
                        </div>

                        <div class="fw-bold">
                            {{ $productionPlan->product?->translation?->title ?? '-' }}
                        </div>

                    </div>


                    {{-- Quantity --}}
                    <div class="col-md-6 col-lg-2">

                        <div class="text-muted small mb-1">
                            تعداد تولید
                        </div>

                        <div class="fw-bold">
                            {{ number_format($productionPlan->quantity) }}
                            دستگاه
                        </div>

                    </div>


                    {{-- Start Date --}}
                    <div class="col-md-6 col-lg-2">

                        <div class="text-muted small mb-1">
                            تاریخ شروع
                        </div>

                        <div class="fw-bold">
                            {{ $productionPlan->start_date?->format('Y/m/d') ?? '-' }}
                        </div>

                    </div>


                    {{-- End Date --}}
                    <div class="col-md-6 col-lg-2">

                        <div class="text-muted small mb-1">
                            تاریخ پایان
                        </div>

                        <div class="fw-bold">
                            {{ $productionPlan->end_date?->format('Y/m/d') ?? '-' }}
                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 col-lg-3">

                        <div class="text-muted small mb-1">
                            وضعیت
                        </div>

                        @php
                            $statusMap = [
                                'draft' => [
                                    'title' => 'پیش‌نویس',
                                    'class' => 'bg-secondary',
                                    'icon' => 'bi-pencil-square',
                                ],
                                'planned' => [
                                    'title' => 'برنامه‌ریزی شده',
                                    'class' => 'bg-primary',
                                    'icon' => 'bi-calendar-check',
                                ],
                                'in_progress' => [
                                    'title' => 'در حال تولید',
                                    'class' => 'bg-warning text-dark',
                                    'icon' => 'bi-gear',
                                ],
                                'completed' => [
                                    'title' => 'تکمیل شده',
                                    'class' => 'bg-success',
                                    'icon' => 'bi-check-circle',
                                ],
                                'cancelled' => [
                                    'title' => 'لغو شده',
                                    'class' => 'bg-danger',
                                    'icon' => 'bi-x-circle',
                                ],
                            ];

                            $status = $statusMap[$productionPlan->status]
                                ?? [
                                    'title' => $productionPlan->status,
                                    'class' => 'bg-secondary',
                                    'icon' => 'bi-question-circle',
                                ];
                        @endphp

                        <span class="badge {{ $status['class'] }} px-3 py-2">
                            <i class="bi {{ $status['icon'] }}"></i>
                            {{ $status['title'] }}
                        </span>

                    </div>


                    {{-- Creator --}}
                    <div class="col-md-6 col-lg-3">

                        <div class="text-muted small mb-1">
                            ایجاد کننده
                        </div>

                        <div>
                            {{ $productionPlan->creator?->name ?? '-' }}
                        </div>

                    </div>


                    {{-- Created At --}}
                    <div class="col-md-6 col-lg-3">

                        <div class="text-muted small mb-1">
                            تاریخ ایجاد
                        </div>

                        <div>
                            {{ $productionPlan->created_at?->format('Y/m/d H:i') ?? '-' }}
                        </div>

                    </div>


                    {{-- Note --}}
                    @if($productionPlan->note)
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                توضیحات
                            </div>

                            <div class="bg-light rounded p-3">
                                {{ $productionPlan->note }}
                            </div>

                        </div>
                    @endif

                </div>

            </div>

        </div>


        {{-- Material Requirements --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            <i class="bi bi-box-seam me-1"></i>
                            نیازمندی مواد و قطعات
                        </h5>

                        <div class="text-muted small">
                            مواد مورد نیاز برای تولید
                            {{ number_format($productionPlan->quantity) }}
                            دستگاه
                        </div>
                    </div>

                    <span class="badge bg-light text-dark border">
                        {{ $productionPlan->requirements->count() }}
                        قلم
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($productionPlan->requirements->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th class="text-center" style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    قطعه / ماده
                                </th>

                                <th class="text-center">
                                    مقدار در هر دستگاه
                                </th>

                                <th class="text-center">
                                    مقدار مورد نیاز
                                </th>

                                <th class="text-center">
                                    موجودی
                                </th>

                                <th class="text-center">
                                    نیاز به خرید
                                </th>

                                <th class="text-center">
                                    واحد
                                </th>

                                <th class="text-center">
                                    قیمت واحد
                                </th>

                                <th class="text-center">
                                    وضعیت
                                </th>

                                <th class="text-center">
                                    عملیات
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @foreach($productionPlan->requirements as $index => $requirement)

                                @php

                                    $required = (float) $requirement->required_quantity;
                                    $available = (float) $requirement->available_quantity;
                                    $purchase = (float) $requirement->purchase_quantity;

                                    $requirementStatusMap = [
                                        'pending' => [
                                            'title' => 'در انتظار بررسی',
                                            'class' => 'bg-secondary',
                                        ],
                                        'coordinating' => [
                                            'title' => 'در حال هماهنگی',
                                            'class' => 'bg-warning text-dark',
                                        ],
                                        'quoted' => [
                                            'title' => 'قیمت دریافت شده',
                                            'class' => 'bg-info text-dark',
                                        ],
                                        'approved' => [
                                            'title' => 'تأیید شده',
                                            'class' => 'bg-primary',
                                        ],
                                        'ordered' => [
                                            'title' => 'سفارش داده شده',
                                            'class' => 'bg-primary',
                                        ],
                                        'partially_received' => [
                                            'title' => 'دریافت ناقص',
                                            'class' => 'bg-warning text-dark',
                                        ],
                                        'received' => [
                                            'title' => 'دریافت شده',
                                            'class' => 'bg-success',
                                        ],
                                        'cancelled' => [
                                            'title' => 'لغو شده',
                                            'class' => 'bg-danger',
                                        ],
                                    ];

                                    $requirementStatus =
                                        $requirementStatusMap[$requirement->status]
                                        ?? [
                                            'title' => $requirement->status,
                                            'class' => 'bg-secondary',
                                        ];

                                @endphp

                                <tr>

                                    {{-- Number --}}
                                    <td class="text-center text-muted">
                                        {{ $index + 1 }}
                                    </td>


                                    {{-- Component --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $requirement->componentProduct?->translation?->title ?? '-' }}

                                        </div>

                                        @if($requirement->component_product_id)
                                            <div class="text-muted small">
                                                کد: {{ $requirement->component_product_id }}
                                            </div>
                                        @endif

                                    </td>


                                    {{-- Required per unit --}}
                                    <td class="text-center">

                                        {{ rtrim(rtrim(number_format($requirement->required_per_unit, 4, '.', ''), '0'), '.') }}

                                    </td>


                                    {{-- Required quantity --}}
                                    <td class="text-center fw-bold">

                                        {{ rtrim(rtrim(number_format($required, 4, '.', ''), '0'), '.') }}

                                    </td>


                                    {{-- Available --}}
                                    <td class="text-center">

                                        @if($available > 0)

                                            <span class="text-success fw-semibold">
                                                {{ rtrim(rtrim(number_format($available, 4, '.', ''), '0'), '.') }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                0
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Purchase --}}
                                    <td class="text-center">

                                        @if($purchase > 0)

                                            <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                                {{ rtrim(rtrim(number_format($purchase, 4, '.', ''), '0'), '.') }}
                                            </span>

                                        @else

                                            <span class="badge bg-success-subtle text-success px-2 py-1">
                                                تأمین است
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Unit --}}
                                    <td class="text-center text-muted">

                                        {{ $requirement->unit ?? '-' }}

                                    </td>


                                    {{-- Unit Price --}}
                                    <td class="text-center">

                                        @if($requirement->unit_price)

                                            {{ number_format($requirement->unit_price) }}

                                            <span class="text-muted small">
                                                تومان
                                            </span>

                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center">

                                        <span class="badge {{ $requirementStatus['class'] }}">
                                            {{ $requirementStatus['title'] }}
                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route(
                                                    'admin.purchase-requests.create',
                                                    $requirement
                                                ) }}"
                                           class="btn btn-sm btn-outline-dark">

                                            <i class="bi bi-shop"></i>
                                            ثبت درخواست تامین
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>


                            <tfoot class="table-light">

                            <tr>

                                <th colspan="3" class="text-end">
                                    جمع
                                </th>

                                <th class="text-center">
                                    {{ number_format($productionPlan->requirements->sum('required_quantity'), 2) }}
                                </th>

                                <th class="text-center">
                                    {{ number_format($productionPlan->requirements->sum('available_quantity'), 2) }}
                                </th>

                                <th class="text-center text-danger">
                                    {{ number_format($productionPlan->requirements->sum('purchase_quantity'), 2) }}
                                </th>

                                <th colspan="3"></th>

                            </tr>

                            </tfoot>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <div class="text-muted mb-3">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>

                        <h6>
                            هیچ ماده یا قطعه‌ای ثبت نشده است.
                        </h6>

                        <div class="text-muted small">
                            برای این برنامه تولید، نیازمندی مواد ایجاد نشده است.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-admin-layout>
