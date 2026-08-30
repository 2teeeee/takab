<x-admin-layout
        title="درخواست‌های سرویس"
        header="درخواست‌های سرویس"
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


        {{-- Search --}}

        <form
                method="GET"
                action="{{ route('admin.service_requests.index') }}"
                class="row g-2 mb-4"
        >

            <div class="col-md-5">

                <input
                        type="text"
                        name="search"
                        class="form-control form-control-sm"
                        placeholder="جستجو بر اساس مشتری، موبایل، مدل یا سریال..."
                        value="{{ request('search') }}"
                >

            </div>


            <div class="col-md-3">

                <select
                        name="status"
                        class="form-select form-select-sm"
                >

                    <option value="">
                        همه وضعیت‌ها
                    </option>

                    <option
                            value="pending"
                            @selected(request('status') === 'pending')
                    >
                        در انتظار
                    </option>

                    <option
                            value="scheduled"
                            @selected(request('status') === 'scheduled')
                    >
                        زمان‌بندی شده
                    </option>

                    <option
                            value="installed"
                            @selected(request('status') === 'installed')
                    >
                        نصب شده
                    </option>

                    <option
                            value="serviced"
                            @selected(request('status') === 'serviced')
                    >
                        سرویس شده
                    </option>

                    <option
                            value="cancelled"
                            @selected(request('status') === 'cancelled')
                    >
                        لغو شده
                    </option>

                </select>

            </div>


            <div class="col-auto">

                <button class="btn btn-dark btn-sm">

                    <i class="bi bi-search"></i>

                    جستجو

                </button>

            </div>


            @if(request()->filled('search') || request()->filled('status'))

                <div class="col-auto">

                    <a
                            href="{{ route('admin.service_requests.index') }}"
                            class="btn btn-danger btn-sm"
                    >
                        حذف فیلتر
                    </a>

                </div>

            @endif

        </form>


        {{-- Requests --}}

        <div class="card shadow-sm">

            <div class="card-header">

                <strong>
                    لیست درخواست‌های سرویس
                </strong>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                مشتری
                            </th>

                            <th>
                                دستگاه
                            </th>

                            <th>
                                سریال
                            </th>

                            <th>
                                آدرس
                            </th>

                            <th>
                                تاریخ سرویس
                            </th>

                            <th>
                                نصاب
                            </th>

                            <th>
                                وضعیت
                            </th>

                            <th>
                                عملیات
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($requests as $serviceRequest)

                            @php

                                $schedule = $serviceRequest->schedules
                                    ->where('status', '!=', 'cancelled')
                                    ->sortBy('scheduled_date')
                                    ->first();

                                $isToday = $schedule?->scheduled_date?->isToday();

                            @endphp


                            <tr
                                    @class([
                                        'table-warning' => $isToday,
                                    ])
                            >

                                <td>

                                    {{
                                        $loop->iteration +
                                        (($requests->currentPage() - 1)
                                        * $requests->perPage())
                                    }}

                                </td>


                                {{-- Customer --}}

                                <td>

                                    <strong>
                                        {{ $serviceRequest->user?->name ?? '-' }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $serviceRequest->user?->mobile ?? '-' }}
                                    </small>

                                </td>


                                {{-- Device --}}

                                <td>
                                    {{ $serviceRequest->device_model }}
                                </td>


                                {{-- Serial --}}

                                <td>
                                    {{ $serviceRequest->serial_number ?? '-' }}
                                </td>


                                {{-- Address --}}

                                <td style="min-width: 220px;">

                                    {{ $serviceRequest->address }}

                                </td>


                                {{-- Schedule --}}

                                <td>

                                    @if($schedule)

                                        @if($isToday)

                                            <span class="badge bg-danger">
                                                امروز
                                            </span>

                                            <br>

                                        @endif

                                        {{ jdate($schedule->scheduled_date)->format('Y/m/d') ?? '-' }}

                                    @else

                                        <span class="text-muted">
                                            زمان‌بندی نشده
                                        </span>

                                    @endif

                                </td>


                                {{-- Installer --}}

                                <td>

                                    @if($schedule?->installer)

                                        {{ $schedule->installer->name }}

                                        <br>

                                        <small class="text-muted">
                                            {{ $schedule->installer->mobile }}
                                        </small>

                                    @else

                                        <span class="text-danger">
                                            بدون نصاب
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}

                                <td>

                                    @switch($serviceRequest->status)

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
                                                {{ $serviceRequest->status }}
                                            </span>

                                    @endswitch

                                </td>


                                {{-- Actions --}}

                                <td>

                                    <a
                                            href="{{ route('admin.install_requests.show', $serviceRequest) }}"
                                            class="btn btn-sm btn-info"
                                    >
                                        <i class="bi bi-eye"></i>
                                        مشاهده
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                        colspan="9"
                                        class="text-center py-5 text-muted"
                                >

                                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>

                                    هیچ درخواست سرویسی یافت نشد.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Pagination --}}

        <div class="mt-3">

            {{ $requests->links() }}

        </div>

    </div>

</x-admin-layout>