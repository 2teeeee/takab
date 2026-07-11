<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['fa','ar']) ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'تک آب صنعت ارم')</title>
        <meta name="description" content="@yield('description', 'شرکت تک آب صنعت ارم تولید کننده دستگاه های تصویه آب و مخزنهای ذخیره سازی آب')">
        <meta name="keywords" content="@yield('keywords', 'تک آب, صنعت ارم, تصویه آب, مخزن آب')">

        <link rel="stylesheet" href="{{asset("fonts/fontstyle.css")}}">
        @if(app()->getLocale() === 'fa' || app()->getLocale() === 'ar')
            <link rel="stylesheet" href="{{asset("bootstrap/dist/css/bootstrap.rtl.css")}}">
        @else
            <link rel="stylesheet" href="{{asset("bootstrap/dist/css/bootstrap.css")}}">
        @endif

        <link rel="stylesheet" href="{{asset("bootstrap/icons/bootstrap-icons.css")}}">
        <link rel="stylesheet" href="{{asset("css/main.css")}}">

        <script src="{{asset("bootstrap/dist/js/bootstrap.esm.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/popper.min.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/bootstrap.js")}}"></script>


        <script>
            document.addEventListener("DOMContentLoaded", () => {
                fetch(`/cart`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('cart-badge').innerText = data.totalQuantity;
                    });
            });

            function updateCartBadge(totalQuantity) {
                document.getElementById('cart-badge').innerText = totalQuantity;
            }
        </script>
    </head>
    <body>
    <div class="header">
        <nav class="navbar navbar-expand-lg pb-0 bg-body-tertiary d-none d-md-block">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{route('main.index')}}">
                    <img src="{{asset("img/logo.png")}}" alt="takab logo" width="100">
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 ms-3 mb-lg-0">
                        <li class="nav-item px-2">
                            <a class="nav-link active" aria-current="page" href="{{route('main.index')}}#home">
                                {{ __('app.home') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown px-2 border-start">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('app.products') }}
                            </a>

                            <ul class="dropdown-menu">
                                @foreach($menuCategories as $cat)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('search', ['category'=>$cat->id]) }}">
                                            {{ $cat->translation->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="{{route('page.show',['slug'=>'about'])}}">
                                {{ __('app.about') }}
                            </a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="{{route('page.contact')}}">
                                {{ __('app.contact') }}
                            </a>
                        </li>
                    </ul>

                    <form action="{{ route('search') }}" method="GET" class="d-flex w-50 me-auto" role="search" >
                        <input class="form-control rounded-end-0 shadow-none" type="text" placeholder="{{ __('app.product_search') }}"  id="title" name="q">
                        <button type="submit" class="btn btn-outline-dark rounded-start-0">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    <ul class="navbar-nav nav-left py-1">
                        <li class="nav-item px-2 text-sm text-center d-flex align-items-center th-1 dropdown">
                            <button type="button"
                                    class="border-0 bg-transparent shadow-none text-sm text-darkgray nav-link p-0"
                                    data-bs-toggle="dropdown">
                                {{ strtoupper(app()->getLocale()) }}
                            </button>

                            <ul class="dropdown-menu text-sm text-decoration-none pb-1">
                                <li><a class="dropdown-item" href="{{ route('lang.switch', 'fa') }}">فارسی</a></li>
                                <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                                <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربی</a></li>
                            </ul>
                        </li>

                    @if(!Auth::check())
                        <li class="nav-item px-2 border-start text-sm text-center d-block th-1">
                            <a class="nav-link" href="{{route('register')}}">
                                <i class="bi bi-person-add icon-size-2x"></i>
                                <div>{{ __('app.signup') }}</div>
                            </a>
                        </li>
                        <li class="nav-item px-2 border-start text-sm text-center d-block th-1">
                            <a class="nav-link" href="{{route('login')}}">
                                <i class="bi bi-box-arrow-in-left icon-size-2x"></i>
                                <div>{{ __('app.login') }}</div>
                            </a>
                        </li>

                        @else
                        <li class="nav-item px-2 border-start text-sm text-center d-block th-1 border-left-light-gray dropdown">
                            <button type="button"
                                    class="border-0 bg-transparent shadow-none text-sm text-darkgray nav-link"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person icon-size-2x"></i>
                                <div>
                                    <span class="dropdown-toggle">
                                    {{ Auth::user()->name }}
                                </div>
                            </button>
                            <ul class="dropdown-menu text-sm text-decoration-none pb-1">
                                <li>
                                    <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('profile.index')}}">
                                        <i class="bi bi-house me-2"></i>
                                        <span>{{ __('app.profile') }}</span>
                                    </a>
                                </li>
                                @if(Auth::user()->hasRole(['admin','seller']))
                                <li>
                                    <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('assembly.index')}}">
                                        <i class="bi bi-bag-check me-2"></i>
                                        <span>{{ __('app.Assembly') }}</span>
                                    </a>
                                </li>
                                @endif
                                <li>
                                    <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('profile.orders.index')}}">
                                        <i class="bi bi-bag-check me-2"></i>
                                        <span>{{ __('app.orders') }}</span>
                                    </a>
                                </li>
                                @if(Auth::user()->hasRole(['seller']))
                                    <li>
                                        <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('profile.store.index')}}">
                                            <i class="bi bi-bag-check me-2"></i>
                                            <span>فروش دستگاه</span>
                                        </a>
                                    </li>
                                @endif
                                @if(!Auth::user()->hasRole(['user']))
                                    <li>
                                        <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('admin.index')}}">
                                            <i class="bi bi-bag-check me-2"></i>
                                            <span>{{ __('app.admin_panel') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <a href="{{route('logout')}}" class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex"
                                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            <span>{{ __('app.logout') }}</span>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>

                        @endif
                        <li class="nav-item px-2 border-start text-sm text-center d-block th-1">
                            <a href="{{route('cart.show')}}" class="position-relative nav-link">
                                <span id="cart-badge"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                                <i class="bi bi-cart icon-size-2x"></i>
                                <div>{{ __('app.basket') }}</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="d-md-none row mx-0 py-2 border-bottom border-dark">
            <div class="col-2 px-1">
                <a class="navbar-brand text-center" href="{{route('main.index')}}">
                    <img src="{{asset("img/logo.png")}}" class="pt-2" alt="takab logo" style="width: 100%;">
                </a>
            </div>
            <div class="col-6 px-0">
                <form action="{{ route('search') }}" method="GET" class="d-flex w-100 me-auto" role="search" >
                    <input class="form-control rounded-end-0 shadow-none" type="text" placeholder="{{ __('app.product_search') }}"  id="title" name="q">
                    <button type="submit" class="btn btn-outline-dark rounded-start-0">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-1 px-0">
                <div class="nav-item px-2 text-sm text-center d-flex align-items-center th-1 dropdown">
                    <button type="button"
                            class="border-0 pt-3 text-center bg-transparent shadow-none text-sm text-darkgray nav-link p-0"
                            data-bs-toggle="dropdown">
                        {{ strtoupper(app()->getLocale()) }}
                    </button>

                    <ul class="dropdown-menu text-sm text-decoration-none pb-1">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'fa') }}">فارسی</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربی</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-3 px-0">
                @if(!Auth::check())
                    <li class="nav-item px-2 border-start text-sm text-center d-block th-1">
                        <a class="nav-link" href="{{route('login')}}">
                            <i class="bi bi-person icon-size-2x"></i>
                            <div>{{ __('app.profile') }}</div>
                        </a>
                    </li>

                @else
                    <li class="nav-item px-2 border-start text-sm text-center d-block th-1 border-left-light-gray dropdown">
                        <button type="button"
                                class="border-0 bg-transparent shadow-none text-sm text-darkgray nav-link"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person icon-size-2x"></i>
                            <div>
                                    <span class="dropdown-toggle">
                                {{ Auth::user()->name }}
                            </div>
                        </button>
                        <ul class="dropdown-menu text-sm text-decoration-none pb-1">
                            <li>
                                <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('profile.index')}}">
                                    <i class="bi bi-house me-2"></i>
                                    <span>{{ __('app.profile') }}</span>
                                </a>
                            </li>
                            @if(Auth::user()->hasRole(['admin','seller']))
                                <li>
                                    <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('assembly.index')}}">
                                        <i class="bi bi-bag-check me-2"></i>
                                        <span>{{ __('app.Assembly') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('profile.orders.index')}}">
                                    <i class="bi bi-bag-check me-2"></i>
                                    <span>{{ __('app.orders') }}</span>
                                </a>
                            </li>
                            @if(!Auth::user()->hasRole(['user']))
                                <li>
                                    <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('admin.index')}}">
                                        <i class="bi bi-bag-check me-2"></i>
                                        <span>{{ __('app.admin_panel') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <a href="{{route('logout')}}" class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex"
                                       onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        <span>{{ __('app.logout') }}</span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>

                @endif
            </div>
        </div>
    </div>

    <div class="body">
        {{ $slot }}
    </div>

    <div class="footer bg-footer text-light pt-3 d-none d-md-block">
        <div class="row mx-0">
            <div class="col-md-4 pt-2">
                {!! $footerAbout->translation?->content !!}
            </div>
            <div class="col-md-4" id="contact">
                <div class="fw-bold pb-2 mb-2 border-bottom">
                    {{ __('app.contact') }}
                </div>
                <div class="text-start">
                    <div class="my-1"><i class="bi bi-geo-alt-fill"></i> {{ __('app.address') }}: {{ __('messages.address') }}</div>
                    <div class="my-1"><i class="bi bi-telephone-fill"></i> {{ __('app.phone') }}: ۳۶-۰۷۱۳۷۷۳۴۸۳۴</div>
                    <div class="my-1"><i class="bi bi-envelope-fill"></i> {{ __('app.email') }}: info@takab-sanat.ir</div>
                </div>
            </div>
            <div class="col-md-4 py-2 text-center d-flex justify-content-center">
                <div class="bg-light rounded-4 me-2">
                    <a referrerpolicy='origin' target='_blank' href='https://trustseal.enamad.ir/?id=675755&Code=L6eJNXqWcqBksxFxbopXAjVnurHzkWsw'>
                        <img referrerpolicy='origin' src='https://trustseal.enamad.ir/logo.aspx?id=675755&Code=L6eJNXqWcqBksxFxbopXAjVnurHzkWsw' alt="enamad" width="100" style='cursor:pointer' code='L6eJNXqWcqBksxFxbopXAjVnurHzkWsw'>
                    </a>
                </div>
                <div class="bg-light rounded-4">
                    <img src="{{asset("img/samandehi-logo.png")}}" alt="samandehi" width="100"/>
                </div>
            </div>
        </div>
        <div class="bg-dark py-2 mt-3" dir="ltr">
            CopyRight 2025 <i class="bi bi-c-circle"></i> TakAb Sanat Eram
        </div>
    </div>
        <div class="min-h-screen bg-gray-100">
            <main>

            </main>
        </div>


<div class="d-md-none" style="width: 100%; height: 60px;"></div>
    <div class="d-block d-md-none mobile-footer fixed-bottom bg-dark border-top border-light">
        <div class="row my-1 mx-0 text-center">
            <div class="col-3 p-1 border-end border-light" :class="{'active': activeMenu === 'Home' }">
                <a href="/" class="text-light text-decoration-none">
                    <i class="bi bi-house icon-size-1x d-block"></i>
                    {{ __('app.home') }}
                </a>
            </div>
            <div class="col-3 p-1 border-end border-light" :class="{ 'active': activeMenu === ($user.auth ? 'ProfileDashboard' : 'Login') }">
                <a class="text-light text-decoration-none" data-bs-toggle="offcanvas" href="#productMenu" role="button" aria-controls="productMenu">
                    <i class="bi bi-wrench icon-size-1x d-block"></i>
                    {{ __('app.products') }}
                </a>
            </div>
            <div class="col-3 p-1 border-end border-light" :class="{ 'active': activeMenu === 'Basket' }">
                <a href="{{route('cart.show')}}" class="position-relative text-light  text-decoration-none">
                    <i class="bi bi-basket icon-size-1x d-block"></i>
                    {{ __('app.basket') }}
                    <span class="position-absolute top-0 badge text-danger rounded-pill bg-light" style="left:-10px;">0</span>
                </a>
            </div>
            <div class="col-3 p-1">
                <a class="text-light text-decoration-none" data-bs-toggle="offcanvas" href="#moreMenu" role="button" aria-controls="moreMenu">
                    <i class="bi bi-list icon-size-1x d-block"></i>
                    منو
                </a>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="moreMenu" aria-labelledby="moreMenuLabel" data-bs-scroll="true" data-bs-backdrop="true">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="moreMenuLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 px-2 position-relative">
            <div class="list-group">
                <a href="{{route('page.show',['slug'=>'about'])}}" class="list-group-item list-group-item-action border-0 my-1 px-2 py-1">
                    <i class="bi bi-info-circle icon-size-0x me-1 text-main"></i>
                    <span>{{ __('app.about') }}</span>
                </a>
            </div>
            <div class="list-group">
                <a href="{{route('page.contact')}}" class="list-group-item list-group-item-action border-0 my-1 px-2 py-1">
                    <i class="bi bi-info-circle icon-size-0x me-1 text-main"></i>
                    <span>{{ __('app.contact') }}</span>
                </a>
            </div>
        </div>
        <div class="offcanvas-footer">
            <div class="row mx-0 mt-3 border-top pt-3 justify-content-center certs w-100 mb-3">
                <div class="col-3 px-1">
                    <a referrerpolicy='origin' target='_blank' href='https://trustseal.enamad.ir/?id=675755&Code=L6eJNXqWcqBksxFxbopXAjVnurHzkWsw'>
                        <img referrerpolicy='origin' src='https://trustseal.enamad.ir/logo.aspx?id=675755&Code=L6eJNXqWcqBksxFxbopXAjVnurHzkWsw' alt="enamad" width="100" style='cursor:pointer' code='L6eJNXqWcqBksxFxbopXAjVnurHzkWsw'>
                    </a>
                </div>
            </div>
            <div class="copyright py-1 font-en-us text-center">
                Copyright <i class="bi bi-c-circle"></i> 2025
                <a href="/" class="text-decoration-none">TakAb Sanat Eram</a>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="productMenu" aria-labelledby="productMenuLabel" data-bs-scroll="true" data-bs-backdrop="true">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="productMenuLabel">لیست محصولات</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 px-2 position-relative">
            <div class="list-group">

                @foreach($menuCategories as $cat)
                    <a href="{{ route('search', ['category'=>$cat->id]) }}" class="list-group-item list-group-item-action border-0 my-1 px-2 py-1">
                        <i class="bi bi-circle-fill me-1 text-main"></i>
                        {{ $cat->translation->title }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    </body>
</html>
