<x-profile-layout title="درخواست‌های من">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">درخواست‌های من</h4>
            <p class="text-muted mb-0">
                لیست درخواست‌های سرویس، نصب و تعمیر دستگاه‌های شما
            </p>
        </div>

        <a href="{{ route('profile.service-requests.create') }}"
           class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i>
            ثبت درخواست جدید
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($requests->count())

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>نوع درخواست</th>
                            <th>دستگاه</th>
                            <th>تاریخ ثبت</th>
                            <th>وضعیت</th>
                            <th class="text-end">عملیات</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($requests as $request)

                            <tr>

                                <td>
                                    {{ $requests->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    @switch($request->request_type)

                                        @case('service')
                                            <span>سرویس</span>
                                            @break

                                        @case('installation')
                                            <span>نصب</span>
                                            @break

                                        @case('repair')
                                            <span>تعمیر</span>
                                            @break

                                        @default
                                            <span>{{ $request->type }}</span>

                                    @endswitch
                                </td>

                                <td>
                                    {{ $request->device_model ?? '-' }}
                                </td>

                                <td>
                                    {{ jdate($request->created_at)->format('Y/m/d') }}
                                </td>

                                <td>

                                    @switch($request->status)

                                        @case('pending')
                                            <span class="badge bg-warning text-dark">
                                                در انتظار بررسی
                                            </span>
                                            @break

                                        @case('approved')
                                            <span class="badge bg-info">
                                                تأیید شده
                                            </span>
                                            @break

                                        @case('scheduled')
                                            <span class="badge bg-primary">
                                                زمان‌بندی شده
                                            </span>
                                            @break

                                        @case('in_progress')
                                            <span class="badge bg-primary">
                                                در حال انجام
                                            </span>
                                            @break

                                        @case('completed')
                                            <span class="badge bg-success">
                                                انجام شده
                                            </span>
                                            @break

                                        @case('rejected')
                                            <span class="badge bg-danger">
                                                رد شده
                                            </span>
                                            @break

                                        @default
                                            <span class="badge bg-secondary">
                                                {{ $request->status }}
                                            </span>

                                    @endswitch

                                </td>

                                <td class="text-end">
{{--

                                    <a href="{{ route('profile.service-requests.show', $request) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        مشاهده
                                    </a>
--}}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>

    @else

        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">

                <div class="mb-3">
                    <i class="bi bi-tools fs-1 text-muted"></i>
                </div>

                <h5>درخواستی ثبت نشده است</h5>

                <p class="text-muted">
                    هنوز هیچ درخواست سرویس، نصب یا تعمیر برای دستگاه‌های خود ثبت نکرده‌اید.
                </p>

                <a href="{{ route('profile.service-requests.create') }}"
                   class="btn btn-sm btn-primary">
                    ثبت درخواست جدید
                </a>

            </div>
        </div>

    @endif

</x-profile-layout>