<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

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
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div class="admin-sidebar">
    <h5 class="text-center text-light mb-3">مدیریت سایت</h5>
    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">👤 کاربران</a>
    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">📂 دسته‌ها</a>
    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">🛍 محصولات</a>
    <hr class="border-secondary">
    <a href="{{ route('dashboard') }}">🏠 داشبورد</a>
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        🚪 خروج
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

{{-- Main Content --}}
<div class="admin-content">

    {{-- Header --}}
    <div class="admin-header mb-3">
        <div>
            <h5 class="m-0">{{ $header }}</h5>
        </div>
        <div>
            <span class="text-muted small">{{ auth()->user()->name ?? 'مدیر' }}</span>
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
