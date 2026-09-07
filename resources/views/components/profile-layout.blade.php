@props(['title' => 'پروفایل کاربر'])

<x-main-layout>

    <div class="container py-4">

        {{-- =====================================================
             Mobile Menu Button
        ====================================================== --}}

        <div class="d-md-none mb-3">

            <button
                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-between"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#profileSidebarMobile"
                    aria-controls="profileSidebarMobile"
            >

                <span>
                    <i class="bi bi-list me-1"></i>
                    منوی پروفایل
                </span>

                <i class="bi bi-chevron-left"></i>

            </button>

        </div>


        <div class="row g-4">


            {{-- =====================================================
                 Desktop Sidebar
            ====================================================== --}}

            <aside class="col-md-3 d-none d-md-block">

                <div class="sticky-top" style="top: 1.5rem;">

                    <div class="card shadow-sm border-0">

                        {{-- Sidebar Header --}}
                        <div class="card-body border-bottom">

                            <div class="d-flex align-items-center">

                                <div class="bg-light rounded-circle
                                            d-flex align-items-center
                                            justify-content-center
                                            flex-shrink-0"
                                     style="width: 42px; height: 42px;">

                                    <i class="bi bi-person fs-5 text-secondary"></i>

                                </div>

                                <div class="me-2">

                                    <div class="fw-semibold">
                                        پروفایل کاربر
                                    </div>

                                    <small class="text-muted">
                                        مدیریت حساب کاربری
                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- Menu --}}
                        <div class="list-group list-group-flush">


                            <a
                                    href="{{ route('profile.index') }}"
                                    class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('profile.index') ? 'active' : '' }}"
                            >

                                <i class="bi bi-person"></i>

                                <span>
                                    اطلاعات کاربر
                                </span>

                            </a>

                            <a
                                    href="{{ route('wallet.index') }}"
                                    class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('wallet.*') ? 'active' : '' }}"
                            >

                                <i class="bi bi-wallet2"></i>

                                <span>
                                کیف پول
                                </span>

                            </a>


                            <a
                                    href="{{ route('profile.orders.index') }}"
                                    class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('profile.orders.*') ? 'active' : '' }}"
                            >

                                <i class="bi bi-bag"></i>

                                <span>
                                    سفارش‌های من
                                </span>

                            </a>


                            <a
                                    href="{{ route('profile.install_requests.index') }}"
                                    class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('profile.install_requests.*') ? 'active' : '' }}"
                            >

                                <i class="bi bi-tools"></i>

                                <span>
                                    درخواست نصب / سرویس
                                </span>

                            </a>


                            <a
                                    href="{{ route('profile.customer.sale.create') }}"
                                    class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('profile.customer.sale.*') ? 'active' : '' }}"
                            >

                                <i class="bi bi-cart3"></i>

                                <span>
                                    فروش دستگاه
                                </span>

                            </a>

                            <a
                                href="{{ route('profile.marketing-orders.index') }}"
                                class="list-group-item list-group-item-action
                                    {{ request()->routeIs('profile.marketing-orders.*') ? 'active' : '' }}"
                            >
                                <i class="bi bi-megaphone me-2"></i>
                                فروش‌های من
                            </a>


                            <a
                                    href="{{ route('profile.edit') }}"
                                    class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                            >

                                <i class="bi bi-pencil-square"></i>

                                <span>
                                    ویرایش اطلاعات
                                </span>

                            </a>


                            <a
                                    href="{{ route('profile.password.edit') }}"
                                    class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('profile.password.edit') ? 'active' : '' }}"
                            >

                                <i class="bi bi-key"></i>

                                <span>
                                    تغییر رمز عبور
                                </span>

                            </a>


                        </div>

                    </div>

                </div>

            </aside>


            {{-- =====================================================
                 Mobile Sidebar
            ====================================================== --}}

            <div
                    class="offcanvas offcanvas-end d-md-none"
                    tabindex="-1"
                    id="profileSidebarMobile"
                    aria-labelledby="profileSidebarMobileLabel"
            >

                <div class="offcanvas-header border-bottom">

                    <div>

                        <h5
                                class="offcanvas-title mb-1"
                                id="profileSidebarMobileLabel"
                        >
                            <i class="bi bi-person-circle me-1"></i>
                            پروفایل کاربر
                        </h5>

                        <small class="text-muted">
                            مدیریت حساب کاربری
                        </small>

                    </div>


                    <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            aria-label="بستن"
                    ></button>

                </div>


                <div class="offcanvas-body p-0">

                    <div class="list-group list-group-flush">


                        <a
                                href="{{ route('profile.index') }}"
                                class="list-group-item list-group-item-action
                            d-flex align-items-center gap-2
                            {{ request()->routeIs('profile.index') ? 'active' : '' }}"
                        >

                            <i class="bi bi-person"></i>

                            <span>
                                اطلاعات کاربر
                            </span>

                        </a>

                        <a
                                href="{{ route('wallet.index') }}"
                                class="list-group-item list-group-item-action
                                d-flex align-items-center gap-2
                                {{ request()->routeIs('wallet.*') ? 'active' : '' }}"
                        >

                            <i class="bi bi-wallet2"></i>

                            <span>
                                کیف پول
                                </span>

                        </a>


                        <a
                                href="{{ route('profile.orders.index') }}"
                                class="list-group-item list-group-item-action
                            d-flex align-items-center gap-2
                            {{ request()->routeIs('profile.orders.*') ? 'active' : '' }}"
                        >

                            <i class="bi bi-bag"></i>

                            <span>
                                سفارش‌های من
                            </span>

                        </a>


                        <a
                                href="{{ route('profile.install_requests.index') }}"
                                class="list-group-item list-group-item-action
                            d-flex align-items-center gap-2
                            {{ request()->routeIs('profile.install_requests.*') ? 'active' : '' }}"
                        >

                            <i class="bi bi-tools"></i>

                            <span>
                                درخواست نصب / سرویس
                            </span>

                        </a>


                        <a
                                href="{{ route('profile.customer.sale.create') }}"
                                class="list-group-item list-group-item-action
                            d-flex align-items-center gap-2
                            {{ request()->routeIs('profile.customer.sale.*') ? 'active' : '' }}"
                        >

                            <i class="bi bi-cart3"></i>

                            <span>
                                فروش دستگاه
                            </span>

                        </a>

                        <a
                            href="{{ route('profile.marketing-orders.index') }}"
                            class="list-group-item list-group-item-action
                                    {{ request()->routeIs('profile.marketing-orders.*') ? 'active' : '' }}"
                        >
                            <i class="bi bi-megaphone me-2"></i>
                            فروش‌های من
                        </a>


                        <div class="border-top my-1"></div>


                        <a
                                href="{{ route('profile.edit') }}"
                                class="list-group-item list-group-item-action
                            d-flex align-items-center gap-2
                            {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                        >

                            <i class="bi bi-pencil-square"></i>

                            <span>
                                ویرایش اطلاعات
                            </span>

                        </a>


                        <a
                                href="{{ route('profile.password.edit') }}"
                                class="list-group-item list-group-item-action
                            d-flex align-items-center gap-2
                            {{ request()->routeIs('profile.password.edit') ? 'active' : '' }}"
                        >

                            <i class="bi bi-key"></i>

                            <span>
                                تغییر رمز عبور
                            </span>

                        </a>


                    </div>

                </div>

            </div>


            {{-- =====================================================
                 Main Content
            ====================================================== --}}

            <main class="col-md-9">

                <div class="card shadow-sm border-0">

                    {{-- Content Header --}}
                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="mb-1 fw-semibold">
                            {{ $title }}
                        </h5>

                        <small class="text-muted">
                            اطلاعات و تنظیمات حساب کاربری
                        </small>

                    </div>


                    {{-- Content --}}
                    <div class="card-body">

                        {{ $slot }}

                    </div>

                </div>

            </main>

        </div>

    </div>


    @push('styles')

        <link
                href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
                rel="stylesheet"
        >

        <style>

            /*
             * فقط تنظیمات جزئی که Bootstrap به تنهایی
             * برای این Layout فراهم نمی‌کند.
             */

            .profile-sidebar {
                min-height: 200px;
            }

            @media (max-width: 767.98px) {

                #profileSidebarMobile {
                    width: 300px;
                }

            }

        </style>

    @endpush


    @stack('scripts')

</x-main-layout>