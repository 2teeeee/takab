<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset("bootstrap/icons/bootstrap-icons.css")}}">
    <link rel="stylesheet" href="{{asset("fonts/fontstyle.css")}}">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .admin-sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            background-color: #343a40;
            color: #fff;
            padding-top: 1rem;
        }

        .admin-sidebar a {
            color: #adb5bd;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            transition: 0.2s;
        }

        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background-color: #495057;
            color: #fff;
        }

        .admin-content {
            margin-right: 250px;
            padding: 1.5rem;
        }

        .admin-header {
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                display: none;
            }
            .admin-content {
                margin-right: 0 !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div class="admin-sidebar">
    <h5 class="text-center text-light mb-3">مدیریت سایت</h5>

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel', 'wholesaler', 'seller', 'marketer']))
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        👤 کاربران
    </a>
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        📂 دسته‌ها
    </a>
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        🛍 محصولات
    </a>
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
    <a href="{{ route('admin.sliders.index') }}" class="{{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
        🛍 اسلایدر
    </a>
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
    <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
        🛍 صفحات توضیحی
    </a>
    @endif

    <hr class="border-secondary">

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel', 'wholesaler', 'seller', 'marketer', 'nasab']))
    <a href="{{ route('admin.letters.index') }}" class="{{ request()->routeIs('letters.*') ? 'active' : '' }}">
        📬 اتوماسیون نامه‌ها
    </a>

    <hr class="border-secondary">
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
            سفارش ها
        </a>
    @endif

    @if(auth()->user()->hasRole('wholesaler'))
        <a href="{{ route('wholesaler.products') }}" class="{{ request()->routeIs('wholeseler.*') ? 'active' : '' }}">
            درخواست خرید محصول
        </a>
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel', 'wholeseler', 'seller']))
    <a href="{{ route('admin.install_requests.index') }}" class="{{ request()->routeIs('install_requests.*') ? 'active' : '' }}">
        ثبت درخواست سرویس
    </a>
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel', 'wholeseler', 'seller']))
    <a href="{{ route('admin.install_schedules.index') }}" class="{{ request()->routeIs('install_schedules.*') ? 'active' : '' }}">
        زمانبندی سرویس
    </a>
    @endif

    @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
    <a href="{{ route('admin.periodic_services.index') }}" class="{{ request()->routeIs('periodic_services.*') ? 'active' : '' }}">
        دوره سرویس
    </a>
    @endif
</div>

<!-- Mobile Sidebar -->
<div class="offcanvas offcanvas-start text-bg-dark d-md-none" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">مدیریت سایت</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        @if(auth()->user()->hasRole(['admin', 'manager', 'personel', 'wholeseler', 'seller', 'marketer']))
            <a href="{{ route('admin.users.index') }}" class="text-light d-block mb-2 text-decoration-none">👤 کاربران</a>
        @endif
        @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
            <a href="{{ route('admin.categories.index') }}" class="text-light d-block mb-2 text-decoration-none">📂 دسته‌ها</a>
            <a href="{{ route('admin.products.index') }}" class="text-light d-block mb-2 text-decoration-none">🛍 محصولات</a>
            <a href="{{ route('admin.sliders.index') }}" class="text-light d-block mb-2 text-decoration-none">🛍 اسلایدر</a>
            <a href="{{ route('admin.pages.index') }}" class="text-light d-block mb-2 text-decoration-none">🛍 صفحات توضیحی</a>
        @endif
            <hr class="border-secondary">

        <a href="{{ route('admin.letters.index') }}" class="text-light d-block mb-2 text-decoration-none">📬 اتوماسیون نامه‌ها</a>

        <hr class="border-secondary">

        @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
            <a href="{{ route('admin.orders.index') }}" class="text-light d-block mb-2 text-decoration-none">سفارش‌ها</a>
        @endif
        @if(auth()->user()->hasRole(['admin', 'manager', 'personel', 'wholeseler', 'seller']))
            <a href="{{ route('admin.install_requests.index') }}" class="text-light d-block mb-2 text-decoration-none">درخواست سرویس</a>
            <a href="{{ route('admin.install_schedules.index') }}" class="text-light d-block mb-2 text-decoration-none">زمان‌بندی سرویس</a>
        @endif
        @if(auth()->user()->hasRole(['admin', 'manager', 'personel']))
            <a href="{{ route('admin.periodic_services.index') }}" class="text-light d-block mb-2 text-decoration-none">دوره سرویس</a>
        @endif
        @if(auth()->user()->hasRole(['wholeseler']))
            <a href="{{ route('wholesaler.products') }}" class="text-light d-block mb-2 text-decoration-none">درخواست خرید محصول</a>
        @endif
        <hr class="border-secondary">

        <a href="{{ route('main.index') }}" class="text-light d-block mb-2 text-decoration-none">🏠 داشبورد</a>
        <a href="{{ route('logout') }}" class="text-light d-block text-decoration-none"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            🚪 خروج
        </a>
    </div>
</div>


{{-- Main Content --}}
<div class="admin-content">

    {{-- Header --}}
    <div class="admin-header mb-3">
        <button class="btn btn-dark d-md-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            ☰
        </button>
        <div class="row w-100">
            <div class="col-md-3">
                <h5 class="m-0">{{ $header }}</h5>
            </div>
            <div class="col-md-9 text-end text-decoration-none">
                <a class="btn btn-link text-decoration-none" href="{{ route('main.index') }}">🏠 صفحه اصلی</a>

                <span class="text-muted small">{{ auth()->user()->name ?? 'مدیر' }}</span>

                <a class="btn btn-link ms-2  text-danger text-decoration-none" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    🚪 خروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div>
        {{ $slot }}
    </div>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
