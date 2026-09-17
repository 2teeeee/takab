<x-admin-layout title="برنامه‌های تولید">

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    <i class="bi bi-gear-wide-connected"></i>
                    برنامه‌های تولید
                </h4>

                <div class="text-muted">
                    مدیریت و پیگیری برنامه‌های تولید دستگاه‌ها
                </div>
            </div>

            <a href="{{ route('admin.production-plans.create') }}"
               class="btn btn-sm btn-primary">

                <i class="bi bi-plus-lg"></i>

                برنامه تولید جدید

            </a>

        </div>


        {{-- Success --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Statistics --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    کل برنامه‌ها
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    {{ $statistics['total'] }}

                                </div>

                            </div>

                            <div class="text-primary fs-3">

                                <i class="bi bi-list-check"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    در انتظار برنامه‌ریزی
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    {{ $statistics['draft'] }}

                                </div>

                            </div>

                            <div class="text-secondary fs-3">

                                <i class="bi bi-file-earmark"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    برنامه‌ریزی شده
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    {{ $statistics['planned'] }}

                                </div>

                            </div>

                            <div class="text-info fs-3">

                                <i class="bi bi-calendar-check"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    در حال تولید
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    {{ $statistics['in_progress'] }}

                                </div>

                            </div>

                            <div class="text-warning fs-3">

                                <i class="bi bi-gear-wide-connected"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Plans Table --}}
        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <strong>
                    <i class="bi bi-table"></i>
                    فهرست برنامه‌های تولید
                </strong>

            </div>


            <div class="card-body p-0">

                @if($plans->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    دستگاه
                                </th>

                                <th class="text-center">
                                    میزان تولید
                                </th>

                                <th>
                                    بازه تولید
                                </th>

                                <th class="text-center">
                                    مواد اولیه
                                </th>

                                <th class="text-center">
                                    وضعیت
                                </th>

                                <th>
                                    ایجادکننده
                                </th>

                                <th>
                                    تاریخ ثبت
                                </th>

                                <th class="text-center">
                                    عملیات
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @foreach($plans as $index => $plan)

                                <tr>

                                    {{-- Number --}}
                                    <td>

                                        {{ $plans->firstItem() + $index }}

                                    </td>


                                    {{-- Product --}}
                                    <td>

                                        <div class="fw-bold">

                                            {{ $plan->product?->translation?->title ?? '---' }}

                                        </div>

                                        <small class="text-muted">

                                            #{{ $plan->product_id }}

                                        </small>

                                    </td>


                                    {{-- Quantity --}}
                                    <td class="text-center">

                                            <span class="fw-bold">

                                                {{ number_format($plan->quantity) }}

                                            </span>

                                        <small class="text-muted d-block">
                                            دستگاه
                                        </small>

                                    </td>


                                    {{-- Dates --}}
                                    <td>

                                        <div>
                                            <i class="bi bi-calendar-event"></i>

                                            {{ jdate($plan->start_date)->format('Y/m/d') }}

                                        </div>

                                        <div class="text-muted small mt-1">

                                            تا

                                            {{ jdate($plan->end_date)->format('Y/m/d') }}

                                        </div>

                                    </td>


                                    {{-- Requirements --}}
                                    <td class="text-center">

                                        @php
                                            $requirementsCount =
                                                $plan->requirements_count ?? 0;
                                        @endphp

                                        @if($requirementsCount > 0)

                                            <span class="badge bg-primary">

                                                    {{ number_format($requirementsCount) }}

                                                    قلم

                                                </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                    بدون مواد
                                                </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center">

                                        @switch($plan->status)

                                            @case('draft')

                                                <span class="badge bg-secondary">
                                                        پیش‌نویس
                                                    </span>

                                                @break


                                            @case('planned')

                                                <span class="badge bg-info text-dark">
                                                        برنامه‌ریزی شده
                                                    </span>

                                                @break


                                            @case('in_progress')

                                                <span class="badge bg-warning text-dark">
                                                        در حال تولید
                                                    </span>

                                                @break


                                            @case('completed')

                                                <span class="badge bg-success">
                                                        تکمیل شده
                                                    </span>

                                                @break


                                            @case('cancelled')

                                                <span class="badge bg-danger">
                                                        لغو شده
                                                    </span>

                                                @break


                                            @default

                                                <span class="badge bg-secondary">
                                                        {{ $plan->status }}
                                                    </span>

                                        @endswitch

                                    </td>


                                    {{-- Creator --}}
                                    <td>

                                        {{ $plan->creator?->name ?? '---' }}

                                    </td>


                                    {{-- Created At --}}
                                    <td>

                                        <div>
                                            {{ jdate($plan->created_at)->format('Y/m/d') }}
                                        </div>

                                        <small class="text-muted">

                                            {{ jdate($plan->created_at)->format('H:i') }}

                                        </small>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-center">

                                        <div class="btn-group"
                                             role="group">

                                            <a href="{{ route(
                                                    'admin.production-plans.show',
                                                    $plan
                                                ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="مشاهده">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            @if($plan->status === 'draft')

                                                <a href="#"
                                                   class="btn btn-sm btn-outline-secondary"
                                                   title="ویرایش">

                                                    <i class="bi bi-pencil"></i>

                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($plans->hasPages())

                        <div class="p-3">

                            {{ $plans->links() }}

                        </div>

                    @endif

                @else

                    <div class="text-center py-5">

                        <div class="text-muted mb-3">

                            <i class="bi bi-gear-wide-connected fs-1"></i>

                        </div>

                        <h5>
                            هنوز برنامه تولیدی ثبت نشده است.
                        </h5>

                        <p class="text-muted">
                            برای شروع، اولین برنامه تولید را ایجاد کنید.
                        </p>

                        <a href="{{ route('admin.production-plans.create') }}"
                           class="btn btn-sm btn-primary">

                            <i class="bi bi-plus-lg"></i>

                            ایجاد برنامه تولید

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-admin-layout>
