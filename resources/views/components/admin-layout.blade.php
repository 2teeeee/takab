<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'مدیریت سایت' }}</title>

    {{-- Bootstrap --}}
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
            rel="stylesheet"
    >

    {{-- Select2 --}}
    <link
            href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
            rel="stylesheet"
    >

    <link
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet"
    >

    {{-- Project CSS --}}
    <link
            rel="stylesheet"
            href="{{ asset('bootstrap/icons/bootstrap-icons.css') }}"
    >

    <link
            rel="stylesheet"
            href="{{ asset('fonts/fontstyle.css') }}"
    >

    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #212529;
            --sidebar-hover: #343a40;
            --sidebar-active: #495057;
            --sidebar-text: #adb5bd;
        }

        body {
            background: #f5f6f8;
            min-height: 100vh;
        }

        /* =========================================================
           Sidebar
        ========================================================= */

        .admin-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;

            width: var(--sidebar-width);

            background: var(--sidebar-bg);

            display: flex;
            flex-direction: column;

            z-index: 1040;

            box-shadow: -2px 0 10px rgba(0, 0, 0, .08);
        }

        .sidebar-header {
            flex-shrink: 0;

            padding: 18px 15px;

            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-title {
            color: #fff;
            font-size: 17px;
            font-weight: 600;

            margin: 0;
        }

        .sidebar-menu {
            flex: 1;

            overflow-y: auto;

            padding: 10px 8px 20px;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #495057;
            border-radius: 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 10px;

            color: var(--sidebar-text);

            padding: 11px 13px;
            margin-bottom: 3px;

            border-radius: 7px;

            text-decoration: none;

            font-size: 14px;

            transition:
                    background .2s ease,
                    color .2s ease,
                    transform .2s ease;
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-menu a.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-section-title {
            color: #6c757d;
            font-size: 11px;

            padding: 14px 13px 6px;

            font-weight: 600;
        }

        .sidebar-divider {
            border-color: rgba(255,255,255,.08);
            margin: 8px 5px;
        }

        /* =========================================================
           Main content
        ========================================================= */

        .admin-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;

            padding: 20px;
        }

        /* =========================================================
           Header
        ========================================================= */

        .admin-header {
            position: sticky;
            top: 0;

            z-index: 1000;

            background: rgba(255,255,255,.96);

            border: 1px solid #e9ecef;

            border-radius: 10px;

            box-shadow: 0 2px 8px rgba(0,0,0,.04);

            padding: 12px 16px;

            margin-bottom: 20px;
        }

        .admin-header-title {
            font-size: 17px;
            font-weight: 600;

            margin: 0;
        }

        .admin-user {
            color: #6c757d;
            font-size: 13px;
        }

        /* =========================================================
           Mobile sidebar
        ========================================================= */

        .mobile-menu-link {
            display: flex;

            align-items: center;

            gap: 10px;

            color: #dee2e6;

            padding: 11px 12px;

            margin-bottom: 4px;

            border-radius: 7px;

            text-decoration: none;

            font-size: 14px;

            transition: .2s;
        }

        .mobile-menu-link:hover,
        .mobile-menu-link.active {
            background: #343a40;
            color: #fff;
        }

        .mobile-menu-link i {
            width: 20px;
            text-align: center;
        }

        /* =========================================================
           Mobile
        ========================================================= */

        @media (max-width: 767.98px) {

            .admin-sidebar {
                display: none;
            }

            .admin-content {
                margin-right: 0;
                padding: 10px;
            }

            .admin-header {
                padding: 10px 12px;

                border-radius: 8px;
            }

            .admin-header-title {
                font-size: 15px;
            }

            .desktop-user-info {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

@php
    $user = auth()->user();

    $isAdmin = $user?->hasRole(['admin', 'manager']);
    $isStaff = $user?->hasRole(['admin', 'manager', 'personel']);

    $isWholesaler = $user?->hasRole('wholesaler');
    $isSeller = $user?->hasRole('seller');
    $isMarketer = $user?->hasRole('marketer');
@endphp


{{-- =========================================================
     Desktop Sidebar
========================================================= --}}

<aside class="admin-sidebar d-none d-md-flex">

    {{-- Header --}}
    <div class="sidebar-header">
        <h5 class="sidebar-title text-center">
            مدیریت سایت
        </h5>
    </div>


    {{-- Menu --}}
    <nav class="sidebar-menu">

        {{-- =====================================================
             General
        ====================================================== --}}

        @if($user->hasRole([
            'admin',
            'manager',
            'personel',
            'wholesaler',
            'seller',
            'marketer'
        ]))

            <a
                    href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
            >
                <i class="bi bi-people"></i>
                <span>کاربران</span>
            </a>

        @endif


        {{-- =====================================================
             Website Management
        ====================================================== --}}

        @if($isStaff)

            <div class="sidebar-section-title">
                مدیریت محتوا
            </div>

            <a
                    href="{{ route('admin.categories.index') }}"
                    class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
            >
                <i class="bi bi-folder"></i>
                <span>دسته‌ها</span>
            </a>

            <a
                    href="{{ route('admin.products.index') }}"
                    class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
            >
                <i class="bi bi-box-seam"></i>
                <span>محصولات</span>
            </a>

            <a
                    href="{{ route('admin.sliders.index') }}"
                    class="{{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-images"></i>
                <span>اسلایدر</span>
            </a>

            <a
                    href="{{ route('admin.pages.index') }}"
                    class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"
            >
                <i class="bi bi-file-text"></i>
                <span>صفحات توضیحی</span>
            </a>

        @endif


        <hr class="sidebar-divider">


        {{-- =====================================================
             Letters
        ====================================================== --}}

        @if($user->hasRole([
            'admin',
            'manager',
            'personel',
            'wholesaler',
            'seller',
            'marketer',
            'nasab'
        ]))

            <a
                    href="{{ route('admin.letters.index') }}"
                    class="{{ request()->routeIs('admin.letters.*') ? 'active' : '' }}"
            >
                <i class="bi bi-envelope"></i>
                <span>اتوماسیون نامه‌ها</span>
            </a>

        @endif


        {{-- =====================================================
             Orders
        ====================================================== --}}

        @if($user->hasRole([
            'admin',
            'manager',
            'personel',
            'seller'
        ]))

            <a
                    href="{{ route('admin.orders.index') }}"
                    class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-cart-check"></i>
                <span>سفارش‌ها</span>
            </a>

        @endif


        {{-- =====================================================
             Commissions
        ====================================================== --}}

        @if($isAdmin)

            <a
                    href="{{ route('admin.commissions.index') }}"
                    class="{{ request()->routeIs('admin.commissions.*') ? 'active' : '' }}"
            >
                <i class="bi bi-cash-stack"></i>
                <span>کمیسیون‌ها</span>
            </a>

        @endif


        {{-- =====================================================
             Wholesaler
        ====================================================== --}}

        @if($isWholesaler)

            <div class="sidebar-section-title">
                پنل عمده‌فروش
            </div>

            <a
                    href="{{ route('wholesaler.products') }}"
                    class="{{ request()->routeIs('wholesaler.products') || request()->routeIs('wholesaler.products.*') ? 'active' : '' }}"
            >
                <i class="bi bi-box-arrow-in-down"></i>
                <span>درخواست خرید محصول</span>
            </a>

            <a
                    href="{{ route('wholesaler.orders.purchases') }}"
                    class="{{ request()->routeIs('wholesaler.orders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-bag-check"></i>
                <span>خریدهای عمده‌فروش</span>
            </a>

            <a
                    href="{{ route('wholesaler.stores.index') }}"
                    class="{{ request()->routeIs('wholesaler.stores.*') ? 'active' : '' }}"
            >
                <i class="bi bi-shop"></i>
                <span>فروش به فروشگاه</span>
            </a>

            <a
                    href="{{ route('wholesaler.sales.index') }}"
                    class="{{ request()->routeIs('wholesaler.sales.*') ? 'active' : '' }}"
            >
                <i class="bi bi-graph-up"></i>
                <span>فروش‌های عمده‌فروش</span>
            </a>

        @endif


        {{-- =====================================================
             Marketer / Wholesaler
        ====================================================== --}}

        @if($isMarketer && !$isWholesaler)

            <div class="sidebar-section-title">
                فروش
            </div>

            <a
                    href="{{ route('wholesaler.stores.index') }}"
                    class="{{ request()->routeIs('wholesaler.stores.*') ? 'active' : '' }}"
            >
                <i class="bi bi-shop"></i>
                <span>فروش به فروشگاه</span>
            </a>

        @endif


        {{-- =====================================================
             Store
        ====================================================== --}}

        @if($isSeller)

            <div class="sidebar-section-title">
                پنل فروشگاه
            </div>

            <a
                    href="{{ route('store.products') }}"
                    class="{{ request()->routeIs('store.products*') }}"
            >
                <i class="bi bi-box-seam"></i>
                <span>خرید محصول</span>
            </a>

            <a
                    href="{{ route('store.orders.purchases') }}"
                    class="{{ request()->routeIs('store.orders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-bag-check"></i>
                <span>خریدهای فروشگاه</span>
            </a>

            <a
                    href="{{ route('store.sales.index') }}"
                    class="{{ request()->routeIs('store.sales.*') ? 'active' : '' }}"
            >
                <i class="bi bi-graph-up"></i>
                <span>فروش‌های فروشگاه</span>
            </a>

        @endif


        {{-- =====================================================
             Services
        ====================================================== --}}

        @if($isStaff)

            <div class="sidebar-section-title">
                خدمات
            </div>

            <a
                    href="{{ route('admin.install_requests.index') }}"
                    class="{{ request()->routeIs('admin.install_requests.*') ? 'active' : '' }}"
            >
                <i class="bi bi-tools"></i>
                <span>درخواست سرویس</span>
            </a>

            <a
                    href="{{ route('admin.install_schedules.index') }}"
                    class="{{ request()->routeIs('admin.install_schedules.*') ? 'active' : '' }}"
            >
                <i class="bi bi-calendar3"></i>
                <span>زمان‌بندی سرویس</span>
            </a>

            <a
                    href="{{ route('admin.periodic_services.index') }}"
                    class="{{ request()->routeIs('admin.periodic_services.*') ? 'active' : '' }}"
            >
                <i class="bi bi-arrow-repeat"></i>
                <span>دوره سرویس</span>
            </a>

        @endif

    </nav>

</aside>


{{-- =========================================================
     Mobile Sidebar
========================================================= --}}

<div
        class="offcanvas offcanvas-start text-bg-dark d-md-none"
        tabindex="-1"
        id="mobileSidebar"
        aria-labelledby="mobileSidebarLabel"
>

    <div class="offcanvas-header border-bottom border-secondary">

        <h5
                class="offcanvas-title"
                id="mobileSidebarLabel"
        >
            مدیریت سایت
        </h5>

        <button
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="بستن"
        ></button>

    </div>


    <div class="offcanvas-body p-2">

        {{-- Dashboard --}}

        <a
                href="{{ route('main.index') }}"
                class="mobile-menu-link"
        >
            <i class="bi bi-house"></i>
            <span>صفحه اصلی</span>
        </a>


        {{-- Users --}}

        @if($user->hasRole([
            'admin',
            'manager',
            'personel',
            'wholesaler',
            'seller',
            'marketer'
        ]))

            <a
                    href="{{ route('admin.users.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
            >
                <i class="bi bi-people"></i>
                <span>کاربران</span>
            </a>

        @endif


        {{-- Website --}}

        @if($isStaff)

            <hr class="border-secondary">

            <div class="text-secondary small px-2 mb-2">
                مدیریت محتوا
            </div>

            <a
                    href="{{ route('admin.categories.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
            >
                <i class="bi bi-folder"></i>
                <span>دسته‌ها</span>
            </a>

            <a
                    href="{{ route('admin.products.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
            >
                <i class="bi bi-box-seam"></i>
                <span>محصولات</span>
            </a>

            <a
                    href="{{ route('admin.sliders.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-images"></i>
                <span>اسلایدر</span>
            </a>

            <a
                    href="{{ route('admin.pages.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"
            >
                <i class="bi bi-file-text"></i>
                <span>صفحات توضیحی</span>
            </a>

        @endif


        {{-- Letters --}}

        @if($user->hasRole([
            'admin',
            'manager',
            'personel',
            'wholesaler',
            'seller',
            'marketer',
            'nasab'
        ]))

            <hr class="border-secondary">

            <a
                    href="{{ route('admin.letters.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.letters.*') ? 'active' : '' }}"
            >
                <i class="bi bi-envelope"></i>
                <span>اتوماسیون نامه‌ها</span>
            </a>

        @endif


        {{-- Orders --}}

        @if($user->hasRole([
            'admin',
            'manager',
            'personel',
            'seller'
        ]))

            <a
                    href="{{ route('admin.orders.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-cart-check"></i>
                <span>سفارش‌ها</span>
            </a>

        @endif


        {{-- Commissions --}}

        @if($isAdmin)

            <a
                    href="{{ route('admin.commissions.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.commissions.*') ? 'active' : '' }}"
            >
                <i class="bi bi-cash-stack"></i>
                <span>کمیسیون‌ها</span>
            </a>

        @endif


        {{-- Wholesaler --}}

        @if($isWholesaler)

            <hr class="border-secondary">

            <div class="text-secondary small px-2 mb-2">
                پنل عمده‌فروش
            </div>

            <a
                    href="{{ route('wholesaler.products') }}"
                    class="mobile-menu-link {{ request()->routeIs('wholesaler.products*') ? 'active' : '' }}"
            >
                <i class="bi bi-box-arrow-in-down"></i>
                <span>درخواست خرید محصول</span>
            </a>

            <a
                    href="{{ route('wholesaler.orders.purchases') }}"
                    class="mobile-menu-link {{ request()->routeIs('wholesaler.orders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-bag-check"></i>
                <span>خریدهای عمده‌فروش</span>
            </a>

            <a
                    href="{{ route('wholesaler.stores.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('wholesaler.stores.*') ? 'active' : '' }}"
            >
                <i class="bi bi-shop"></i>
                <span>فروش به فروشگاه</span>
            </a>

            <a
                    href="{{ route('wholesaler.sales.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('wholesaler.sales.*') ? 'active' : '' }}"
            >
                <i class="bi bi-graph-up"></i>
                <span>فروش‌های عمده‌فروش</span>
            </a>

        @endif


        {{-- Store --}}

        @if($isSeller)

            <hr class="border-secondary">

            <div class="text-secondary small px-2 mb-2">
                پنل فروشگاه
            </div>

            <a
                    href="{{ route('store.products') }}"
                    class="mobile-menu-link {{ request()->routeIs('store.products*') ? 'active' : '' }}"
            >
                <i class="bi bi-box-seam"></i>
                <span>خرید محصول</span>
            </a>

            <a
                    href="{{ route('store.orders.purchases') }}"
                    class="mobile-menu-link {{ request()->routeIs('store.orders.*') ? 'active' : '' }}"
            >
                <i class="bi bi-bag-check"></i>
                <span>خریدهای فروشگاه</span>
            </a>

            <a
                    href="{{ route('store.sales.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('store.sales.*') ? 'active' : '' }}"
            >
                <i class="bi bi-graph-up"></i>
                <span>فروش‌های فروشگاه</span>
            </a>

        @endif


        {{-- Services --}}

        @if($isStaff)

            <hr class="border-secondary">

            <div class="text-secondary small px-2 mb-2">
                خدمات
            </div>

            <a
                    href="{{ route('admin.install_requests.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.install_requests.*') ? 'active' : '' }}"
            >
                <i class="bi bi-tools"></i>
                <span>درخواست سرویس</span>
            </a>

            <a
                    href="{{ route('admin.install_schedules.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.install_schedules.*') ? 'active' : '' }}"
            >
                <i class="bi bi-calendar3"></i>
                <span>زمان‌بندی سرویس</span>
            </a>

            <a
                    href="{{ route('admin.periodic_services.index') }}"
                    class="mobile-menu-link {{ request()->routeIs('admin.periodic_services.*') ? 'active' : '' }}"
            >
                <i class="bi bi-arrow-repeat"></i>
                <span>دوره سرویس</span>
            </a>

        @endif


        <hr class="border-secondary">


        {{-- Logout --}}

        <form
                action="{{ route('logout') }}"
                method="POST"
                class="mt-2"
        >
            @csrf

            <button
                    type="submit"
                    class="mobile-menu-link border-0 bg-transparent w-100 text-danger"
            >
                <i class="bi bi-box-arrow-right"></i>
                <span>خروج</span>
            </button>
        </form>

    </div>

</div>


{{-- =========================================================
     Main Content
========================================================= --}}

<main class="admin-content">


    {{-- Header --}}

    <header class="admin-header">

        <div class="d-flex align-items-center gap-3">

            {{-- Mobile Menu Button --}}

            <button
                    type="button"
                    class="btn btn-dark d-md-none"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileSidebar"
                    aria-controls="mobileSidebar"
                    aria-label="باز کردن منو"
            >
                <i class="bi bi-list"></i>
            </button>


            {{-- Page Title --}}

            <h5 class="admin-header-title">
                {{ $header ?? 'مدیریت سایت' }}
            </h5>

        </div>


        {{-- Desktop Actions --}}

        <div class="d-none d-md-flex align-items-center gap-2">

            <a
                    href="{{ route('main.index') }}"
                    class="btn btn-sm btn-light"
            >
                <i class="bi bi-house"></i>
                صفحه اصلی
            </a>

            <span class="admin-user">
                {{ auth()->user()->name ?? 'مدیر' }}
            </span>

            <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="d-inline"
            >
                @csrf

                <button
                        type="submit"
                        class="btn btn-sm btn-outline-danger"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    خروج
                </button>
            </form>

        </div>

    </header>


    {{-- Page Content --}}

    <section>
        {{ $slot }}
    </section>

</main>


{{-- =========================================================
     JavaScript
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')

</body>
</html>