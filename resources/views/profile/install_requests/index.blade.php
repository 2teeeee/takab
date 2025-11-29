<x-profile-layout title="درخواست‌های نصب من">

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('profile.install_requests.create') }}" class="btn btn-success btn-sm">ثبت درخواست جدید</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($requests->isEmpty())
            <div class="alert alert-info">شما تاکنون درخواستی ثبت نکرده‌اید.</div>
        @else
            <div class="list-group">
                @foreach($requests as $req)
                    <div class="list-group-item mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">{{ $req->device_model }} @if($req->serial_number) — {{ $req->serial_number }} @endif</div>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($req->address, 120) }}</div>

                                @if($req->periodicService)
                                    <div class="mt-2 small">
                                        <span class="badge bg-info me-1">سرویس دوره‌ای</span>
                                        <span class="text-muted">آخرین سرویس: {{ $req->periodicService->last_service_date ? jdate($req->periodicService->last_service_date)->format('Y/m/d') : '-' }}</span>
                                    </div>
                                @endif

                                {{-- نمایش سِوی‌های زمان‌بندی‌شده --}}
                                @if($req->schedules->count())
                                    <div class="mt-2">
                                        <div class="small fw-bold text-success mb-1">نوبت‌های زمان‌بندی‌شده:</div>
                                        <ul class="small mb-0">
                                            @foreach($req->schedules as $s)
                                                <li>
                                                    {{ jdate($s->scheduled_date)->format('Y/m/d') }}
                                                    — وضعیت:
                                                    <span class="badge bg-{{ $s->status == 'waiting' ? 'secondary' : ($s->status == 'done' ? 'success' : 'danger') }}">
                                                        {{ $s->status }}
                                                    </span>
                                                    @if($s->installer)
                                                        — نصاب: {{ $s->installer->name }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                            </div>

                            <div class="text-end">
                                <div class="mb-2">
                                    @include('profile.install_requests._status_badge', ['status' => $req->status])
                                </div>
                                <div class="small text-muted">ثبت‌شده: {{ jdate($req->created_at)->format('Y/m/d') }}</div>

                                @if($req->installation_date)
                                    <div class="small mt-1 text-success">تاریخ پیشنهادی: {{ jdate($req->installation_date)->format('Y/m/d H:i') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-profile-layout>
