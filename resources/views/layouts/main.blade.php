<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'تک آب صنعت ارم')</title>
        <meta name="description" content="@yield('description', 'شرکت تک آب صنعت ارم تولید کننده دستگاه های تصویه آب و مخزنهای ذخیره سازی آب')">
        <meta name="keywords" content="@yield('keywords', 'تک آب, صنعت ارم, تصویه آب, مخزن آب')">

        <link rel="stylesheet" href="{{asset("fonts/fontstyle.css")}}">
        <link rel="stylesheet" href="{{asset("bootstrap/dist/css/bootstrap.rtl.css")}}">
        <link rel="stylesheet" href="{{asset("bootstrap/icons/bootstrap-icons.css")}}">
        <link rel="stylesheet" href="{{asset("css/main.css")}}">

        <script src="{{asset("bootstrap/dist/js/bootstrap.esm.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/popper.min.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/bootstrap.js")}}"></script>

        @vite(['resources/js/app.js'])

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
        <nav class="navbar navbar-expand-lg pb-0 bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{route('main.index')}}">
                    <img src="{{asset("img/logo.png")}}" alt="takab logo" width="100">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 ms-3 mb-lg-0">
                        <li class="nav-item px-2">
                            <a class="nav-link active" aria-current="page" href="{{route('main.index')}}#home">خانه</a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="{{route('main.index')}}#product">محصولات</a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="{{route('main.index')}}#about">درباره ما</a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="#contact">تماس با ما</a>
                        </li>


                    </ul>

                    <form class="d-flex w-50 me-auto" role="search" >
                        <input class="form-control rounded-end-0 shadow-none" type="text" placeholder="جستجوی محصول"  id="title">
                        <button type="submit" class="btn btn-outline-dark rounded-start-0">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <ul class="navbar-nav nav-left py-1">
                        @if(!Auth::check())
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="{{route('register')}}">
                                <i class="icon-user icon-size-2x mb-1"></i>
                                ثبت نام
                            </a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="{{route('login')}}">
                                <i class="icon-user icon-size-2x mb-1"></i>
                                ورود
                            </a>
                        </li>

                        @else
                        <li class="nav-item px-3 border-left-light-gray dropdown">
                            <button type="button"
                                    class="border-0 d-grid bg-transparent shadow-none text-sm text-darkgray nav-link py-0"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-user icon-size-2x mb-1"></i>
                                <span class="dropdown-toggle">
                  {{ Auth::name() }}
                </span>
                            </button>
                            <ul class="dropdown-menu text-sm pb-1">
                                <li>
                                    <a class="text-dark px-2 pb-1 align-self-center d-flex" href="/">
                                        <i class="bi bi-house icon-size-1x me-2"></i>
                                        <span>پروفایل</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="text-dark px-2 pb-1 align-self-center d-flex" href="/">
                                        <i class="bi bi-bag-check icon-size-1x me-2"></i>
                                        <span>سفارش ها</span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </li>
                            </ul>
                        </li>

                        @endif
                        <li class="nav-item px-3 position-relative">
                            <a href="{{route('cart.show')}}" class="position-relative">
                                <i class="icon-shopping-bag icon-size-2x mb-1"></i>
                                {{ __('basket') }}
                                <span id="cart-badge"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="body">
        {{ $slot }}
    </div>

    <div class="footer bg-footer text-light pt-3">
        <div class="row mx-0">
            <div class="col-md-4 pt-2">
                <p>                تک آب صنعت ارم، با سال‌ها تجربه در زمینه طراحی و تولید دستگاه‌های تصفیه آب و مخزن های ذخیره آب، همراه مطمئن شما در تأمین آب سالم و باکیفیت است.
                </p>
                <p>                ما با بهره‌گیری از دانش فنی روز و استفاده از بهترین متریال، محصولاتی بادوام و کارآمد ارائه می‌دهیم تا نیاز خانواده‌ها، صنایع و سازمان‌ها را به بهترین شکل برآورده کنیم.
                </p>
            </div>
            <div class="col-md-4" id="contact">
                <div class="fw-bold pb-2 mb-2 border-bottom">
                    ارتباط با ما
                </div>
                <div class="text-start">
                    <div class="my-1"><i class="bi bi-geo-alt-fill"></i> آدرس: شهرک صنعتی بزرگ شیراز, میدان سوم, کوشش شمالی, میدان ساعی, خیابان ساعی, خیابان پردازش,  خیابان ۸۰۷/۲, سوله پنجم سمت راست</div>
                    <div class="my-1"><i class="bi bi-telephone-fill"></i> تلفن تماس: ۳۶-۰۷۱۳۷۷۳۴۸۳۴</div>
                    <div class="my-1"><i class="bi bi-envelope-fill"></i> ایمیل: info@takab-sanat.ir</div>
                </div>
            </div>
            <div class="col-md-4 py-2 text-center d-flex justify-content-center">
                <div class="bg-light rounded-4 me-2">
                    <img src="{{asset("img/enamad-logo.png")}}" alt="enamad" width="100"/>
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
    </body>
</html>
