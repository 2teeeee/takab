<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'تک آب صنعت آریا')</title>
        <meta name="description" content="@yield('description', 'شرکت تک آب صنعت آریا تولید کننده دستگاه های تصویه آب و تانکرهای ذخیره سازی آب')">
        <meta name="keywords" content="@yield('keywords', 'تک آب, صنعت آریا, تصویه آب, تانکر, مخزن آب')">

        <link rel="stylesheet" href="{{asset("fonts/fontstyle.css")}}">
        <link rel="stylesheet" href="{{asset("bootstrap/dist/css/bootstrap.rtl.css")}}">
        <link rel="stylesheet" href="{{asset("bootstrap/icons/bootstrap-icons.css")}}">
        <link rel="stylesheet" href="{{asset("css/main.css")}}">

        <script src="{{asset("bootstrap/dist/js/bootstrap.esm.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/popper.min.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/bootstrap.js")}}"></script>

        @vite(['resources/js/app.js'])
    </head>
    <body>
    <div class="header">
        <nav class="navbar navbar-expand-lg pb-0 bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="{{asset("img/logo.png")}}" alt="takab logo" width="100">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 ms-3 mb-lg-0">
                        <li class="nav-item px-2">
                            <a class="nav-link active" aria-current="page" href="#home">خانه</a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="#product">محصولات</a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="#about">درباره ما</a>
                        </li>
                        <li class="nav-item px-2 border-start">
                            <a class="nav-link" href="#contact">تماس با ما</a>
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
                <p>                تک آب صنعت آریا، با سال‌ها تجربه در زمینه طراحی و تولید دستگاه‌های تصفیه آب و تانکرهای ذخیره آب، همراه مطمئن شما در تأمین آب سالم و باکیفیت است.
                </p>
                <p>                ما با بهره‌گیری از دانش فنی روز و استفاده از بهترین متریال، محصولاتی بادوام و کارآمد ارائه می‌دهیم تا نیاز خانواده‌ها، صنایع و سازمان‌ها را به بهترین شکل برآورده کنیم.
                </p>
            </div>
            <div class="col-md-4" id="contact">
                <div class="fw-bold pb-2 mb-2 border-bottom">
                    ارتباط با ما
                </div>
                <div class="text-start">
                    <div class="my-1"><i class="bi bi-geo-alt-fill"></i> آدرس: [اینجا آدرس شرکت شما درج می‌شود]</div>
                    <div class="my-1"><i class="bi bi-telephone-fill"></i> تلفن تماس: [شماره تماس]</div>
                    <div class="my-1"><i class="bi bi-envelope-fill"></i> ایمیل: [ایمیل شرکت]</div>
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
            CopyRight 2025 <i class="bi bi-c-circle"></i> TakAb Sanat Arya
        </div>
    </div>
    <div class="min-h-screen bg-gray-100">
            <main>

            </main>
        </div>
    </body>
</html>
