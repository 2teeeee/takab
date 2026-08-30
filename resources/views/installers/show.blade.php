<x-admin-layout title="مشاهده نصاب" header="مشاهده اطلاعات نصاب">

    <div class="container py-4">

        {{-- دکمه‌های بالای صفحه --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <a href="{{ route('admin.installers.index') }}"
               class="btn btn-sm btn-secondary">

                <i class="bi bi-chevron-double-right"></i>
                بازگشت به لیست نصاب‌ها

            </a>

            <a href="{{ route('admin.installers.edit', $user) }}"
               class="btn btn-sm btn-primary">

                <i class="bi bi-pencil"></i>
                ویرایش نصاب

            </a>

        </div>


        {{-- اطلاعات کاربر --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-person"></i>
                    اطلاعات شخصی
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- نام --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            نام و نام خانوادگی
                        </label>

                        <div class="fw-bold">
                            {{ $user->name ?? '-' }}
                        </div>

                    </div>


                    {{-- موبایل --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            شماره موبایل
                        </label>

                        <div class="fw-bold">
                            {{ $user->mobile ?? '-' }}
                        </div>

                    </div>


                    {{-- معرف --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            معرف
                        </label>

                        <div class="fw-bold">
                            @if($user->registeredBy)
                                <div>
                                    {{ $user->registeredBy->name }}
                                </div>

                                @if($user->registeredBy->mobile)
                                    <small class="text-muted">
                                        {{ $user->registeredBy->mobile }}
                                    </small>
                                @endif
                            @else
                                <span class="text-muted">سیستم</span>
                            @endif
                        </div>

                    </div>


                    {{-- کد ملی --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            کد ملی
                        </label>

                        <div>
                            {{ $user->national_code ?? '-' }}
                        </div>

                    </div>


                    {{-- نقش‌ها --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            نقش‌های کاربر
                        </label>

                        <div>

                            @forelse($user->roles as $role)

                                <span class="badge bg-primary me-1">
                                    {{ $role->label }}
                                </span>

                            @empty

                                <span class="text-muted">
                                    بدون نقش
                                </span>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- اطلاعات نصاب --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-tools"></i>
                    اطلاعات تخصصی نصاب
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- سابقه کار --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            سابقه کار
                        </label>

                        <div class="fw-bold">

                            @if($user->installer->experience !== null)

                                {{ $user->installer->experience }}
                                سال

                            @else

                                <span class="text-muted">
                                    ثبت نشده
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- وضعیت --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-muted">
                            وضعیت
                        </label>

                        <div>

                            @switch($user->installer->status)

                                @case('pending')

                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i>
                                        در انتظار تأیید
                                    </span>

                                    @break

                                @case('approved')

                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i>
                                        تأیید شده
                                    </span>

                                    @break

                                @case('rejected')

                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i>
                                        رد شده
                                    </span>

                                    @break

                                @case('inactive')

                                    <span class="badge bg-secondary">
                                        <i class="bi bi-pause-circle"></i>
                                        غیرفعال
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-secondary">
                                        {{ $user->installer->status }}
                                    </span>

                            @endswitch

                        </div>

                    </div>

                    @if($user->installer?->status === 'rejected')

                        <div class="alert alert-danger">

                            <h6 class="fw-bold">
                                <i class="bi bi-x-circle"></i>
                                دلیل رد نصاب
                            </h6>

                            <hr>

                            <p class="mb-2">
                                {{ $user->installer->status_note }}
                            </p>

                            @if($user->installer->approved_at)

                                <small class="text-muted">
                                    بررسی شده در:
                                    {{ jdate($user->installer->approved_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i') }}
                                </small>

                            @endif

                        </div>

                    @endif

                    {{-- آدرس --}}
                    <div class="col-12 mb-3">

                        <label class="form-label text-muted">
                            آدرس
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e($user->installer->address ?? 'آدرس ثبت نشده است')) !!}

                        </div>

                    </div>


                    {{-- توضیحات --}}
                    <div class="col-12 mb-3">

                        <label class="form-label text-muted">
                            توضیحات
                        </label>

                        <div class="border rounded p-3 bg-light">

                            @if($user->installer->description)

                                {!! nl2br(e($user->installer->description)) !!}

                            @else

                                <span class="text-muted">
                                    توضیحاتی ثبت نشده است.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- اطلاعات سیستمی --}}
        <div class="card shadow-sm">

            <div class="card-header bg-light">

                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i>
                    اطلاعات سیستمی
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- شناسه --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            شناسه نصاب
                        </label>

                        <div>
                            #{{ $user->installer->id }}
                        </div>

                    </div>


                    {{-- تاریخ ثبت --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            تاریخ ثبت
                        </label>

                        <div>
                            {{ jdate($user->installer->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i') ?? '-' }}
                        </div>

                    </div>


                    {{-- آخرین بروزرسانی --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label text-muted">
                            آخرین بروزرسانی
                        </label>

                        <div>
                            {{ jdate($user->installer->updated_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i') ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- عملیات --}}
        <div class="d-flex justify-content-between mt-4">

            <a href="{{ route('admin.installers.index') }}"
               class="btn btn-sm btn-secondary">

                <i class="bi bi-arrow-right"></i>
                بازگشت

            </a>


            <div class="d-flex gap-2">

                <a href="{{ route('admin.installers.edit', $user) }}"
                   class="btn btn-sm btn-primary">

                    <i class="bi bi-pencil"></i>
                    ویرایش

                </a>

                @if($user->installer->status === 'pending')

                    <form action="{{ route('admin.installers.approve', $user) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="btn btn-sm btn-success">

                            <i class="bi bi-check-circle"></i>
                            تأیید نصاب

                        </button>

                    </form>

                    <button
                            type="button"
                            class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectInstallerModal"
                    >
                        <i class="bi bi-x-circle"></i>
                        رد نصاب
                    </button>

                @endif

            </div>

        </div>

    </div>

    @if($user->installer->status === 'pending')

        <div
                class="modal fade"
                id="rejectInstallerModal"
                tabindex="-1"
                aria-labelledby="rejectInstallerModalLabel"
                aria-hidden="true"
        >
            <div class="modal-dialog">

                <div class="modal-content">

                    <form
                            method="POST"
                            action="{{ route(
                    'admin.installers.reject',
                    $user
                ) }}"
                    >

                        @csrf
                        @method('PATCH')

                        <div class="modal-header">

                            <h5
                                    class="modal-title"
                                    id="rejectInstallerModalLabel"
                            >
                                رد نصاب
                            </h5>

                            <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                            ></button>

                        </div>


                        <div class="modal-body">

                            <div class="alert alert-warning">

                                <i class="bi bi-exclamation-triangle"></i>

                                آیا مطمئن هستید که می‌خواهید این نصاب را رد کنید؟

                            </div>


                            <div class="mb-3">

                                <label
                                        for="status_note"
                                        class="form-label"
                                >
                                    دلیل رد نصاب
                                </label>

                                <textarea
                                        name="status_note"
                                        id="status_note"
                                        rows="5"
                                        class="form-control @error('status_note') is-invalid @enderror"
                                        placeholder="دلیل رد نصاب را وارد کنید..."
                                        required
                                >{{ old('status_note') }}</textarea>


                                @error('status_note')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button
                                    type="button"
                                    class="btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal"
                            >
                                انصراف
                            </button>

                            <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                            >
                                <i class="bi bi-x-circle"></i>
                                ثبت رد نصاب
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    @endif

</x-admin-layout>
