<x-admin-layout
        title="درخواست‌های سرویس"
        header="درخواست‌های سرویس من"
>

    <div class="container py-4">

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


        <div class="card shadow-sm">

            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>
                    درخواست‌های سرویس من
                </strong>

                <span class="badge bg-primary">
                    {{ $schedules->total() }} درخواست
                </span>
            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>تاریخ سرویس</th>
                            <th>مشتری</th>
                            <th>موبایل</th>
                            <th>مدل دستگاه</th>
                            <th>شماره سریال</th>
                            <th>آدرس</th>
                            <th>توضیحات</th>
                            <th>وضعیت</th>
                            <th></th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($schedules as $schedule)

                            @php
                                $request = $schedule->installRequest;
                                $date = $schedule->scheduled_date;
                                $isToday = $date?->isToday();
                                $isPast = $date?->isPast() && !$isToday;
                            @endphp

                            <tr
                                    @class([
                                        'table-warning' => $isToday,
                                        'table-secondary' => $isPast,
                                    ])
                            >

                                <td>
                                    {{ $loop->iteration + (($schedules->currentPage() - 1) * $schedules->perPage()) }}
                                </td>

                                <td>

                                    @if($isToday)
                                        <span class="badge bg-danger mb-1">
                                            امروز
                                        </span>
                                        <br>
                                    @endif

                                    {{ jdate($date)->format('Y/m/d') }}

                                </td>

                                <td>
                                    {{ $request?->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $request?->user?->mobile ?? '-' }}
                                </td>

                                <td>
                                    {{ $request?->device_model ?? '-' }}
                                </td>

                                <td>
                                    {{ $request?->serial_number ?? '-' }}
                                </td>

                                <td style="min-width: 250px;">
                                    {{ $request?->address ?? '-' }}
                                </td>

                                <td style="min-width: 250px;">
                                    {{ $request?->description ?? '-' }}
                                </td>

                                <td>

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

                                </td>

                                <td>

                                    <a
                                            href="{{ route('installer.install_schedules.report', $schedule) }}"
                                            class="btn btn-sm btn-info"
                                    >
                                        <i class="bi bi-pen"></i>
                                        ثبت گزارش
                                    </a>

                                    @if($schedule->report)

                                        <a href="{{ route('installer.install-reports.show', $schedule->report) }}"
                                           class="btn btn-sm btn-info">

                                            <i class="bi bi-file-text"></i>
                                            مشاهده گزارش

                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                        colspan="8"
                                        class="text-center py-5 text-muted"
                                >
                                    <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>

                                    هیچ درخواست سرویسی برای شما ثبت نشده است.
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-3">
            {{ $schedules->links() }}
        </div>

    </div>

</x-admin-layout>