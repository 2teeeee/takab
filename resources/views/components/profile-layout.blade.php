@props(['title' => 'پروفایل کاربر'])

<x-main-layout>
    <div class="container py-4">
        <div class="row">
            <!-- دکمه باز کردن منو در موبایل -->
            <div class="col-12 mb-3 d-md-none text-center">
                <button class="btn btn-outline-primary" type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#profileSidebar"
                        aria-controls="profileSidebar">
                    <i class="bi bi-list"></i> منوی پروفایل
                </button>
            </div>

            <!-- Sidebar (Offcanvas در موبایل) -->
            <div class="col-md-3 mb-3">
                <div class="offcanvas-md offcanvas-end show" tabindex="-1" id="profileSidebar"
                     aria-labelledby="profileSidebarLabel">
                    <div class="offcanvas-header d-md-none">
                        <h5 class="offcanvas-title" id="profileSidebarLabel">منوی پروفایل</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-0">
                        <div class="card shadow-sm border-0 w-100">
                            <div class="card-header bg-primary text-white text-center d-none d-md-block">
                                <h6 class="mb-0">پروفایل کاربر</h6>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="{{ route('profile.index') }}"
                                   class="list-group-item list-group-item-action {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                                    <i class="bi bi-person-circle me-1"></i> اطلاعات کاربر
                                </a>

                                <a href="{{ route('profile.edit') }}"
                                   class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                    <i class="bi bi-pencil-square me-1"></i> ویرایش اطلاعات
                                </a>

                                <a href="{{ route('profile.password.edit') }}"
                                   class="list-group-item list-group-item-action {{ request()->routeIs('profile.password.edit') ? 'active' : '' }}">
                                    <i class="bi bi-key me-1"></i> تغییر رمز عبور
                                </a>

                                <a href="{{ route('profile.orders') }}"
                                   class="list-group-item list-group-item-action {{ request()->routeIs('profile.orders') ? 'active' : '' }}">
                                    <i class="bi bi-bag-check me-1"></i> سفارش‌های من
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3 border-bottom pb-2">{{ $title }}</h5>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            @media (min-width: 768px) {
                .offcanvas-md {
                    position: static !important;
                    transform: none !important;
                    visibility: visible !important;
                }
            }
        </style>
    @endpush
</x-main-layout>
