<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>تک آب صنعت آریا</title>

        <link rel="stylesheet" href="{{asset("fonts/fontstyle.css")}}">
        <link rel="stylesheet" href="{{asset("bootstrap/dist/css/bootstrap.rtl.css")}}">
        <link rel="stylesheet" href="{{asset("bootstrap/icons/bootstrap-icons.css")}}">
        <link rel="stylesheet" href="{{asset("css/main.css")}}">

        <script src="{{asset("bootstrap/dist/js/bootstrap.esm.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/popper.min.js")}}"></script>
        <script src="{{asset("bootstrap/dist/js/bootstrap.js")}}"></script>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @endif
    </head>
    <body class="">
        <div class="header">
           <nav class="navbar navbar-expand-lg bg-body-tertiary">
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
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{asset("img/slide.webp")}}" class="d-block w-100" alt="دستگاه تصویه آب">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <div class="row mx-0 justify-content-center bg-main-light py-4 text-center" id="about">
                <div class="col-6 col-md-3 px-4">
                    <div class="border border-info bg-light rounded-circle wh-100 mx-auto fs-64" >
                        <i class="bi bi-patch-check align-self-center"></i>
                    </div>
                    <div>تضمین کیفیت</div>
                </div>
                <div class="col-6 col-md-3 px-4">
                    <div class="border border-info bg-light rounded-circle wh-100 mx-auto fs-64" >
                        <i class="bi bi-hand-thumbs-up align-self-center"></i>
                    </div>
                    <div>پشتیبانی مستمر</div>
                </div>
                <div class="col-6 col-md-3 px-4">
                    <div class="border border-info bg-light rounded-circle wh-100 mx-auto fs-64" >
                        <i class="bi bi-cart-check align-self-center"></i>
                    </div>
                    <div>خرید مطمئن</div>
                </div>
                <div class="col-6 col-md-3 px-4">
                    <div class="border border-info bg-light rounded-circle wh-100 mx-auto fs-64" >
                        <i class="bi bi-credit-card align-self-center"></i>
                    </div>
                    <div>تضمین قیمت</div>
                </div>
            </div>
            <div class="row mx-0 justify-content-center my-4">
                <div class="col-md-9 border rounded-4 shadow px-4 py-5">
                    <h3 class="text-center">درباره تک آب</h3>
                    <p>شرکت تک آب صنعت آریا فعالیت خود را از سال ۱۳۹۹ در شهر شیراز آغاز کرده و امروز به‌عنوان یکی از تولیدکنندگان پیشرو در زمینه دستگاه‌های تصفیه آب و تانکرهای ذخیره آب در کشور شناخته می‌شود.</p>
                    <p>محل کارخانه این شرکت در شهرک صنعتی بزرگ شیراز واقع شده است؛ جایی که با بهره‌گیری از ماشین‌آلات پیشرفته، تکنولوژی روز دنیا و نیروی انسانی متخصص، محصولاتی با کیفیت بالا و استاندارد تولید می‌گردد.</p>
                    <p>ما در تک آب صنعت آریا معتقدیم که آب سالم، شفاف و بهداشتی یکی از اساسی‌ترین نیازهای زندگی انسان است و رسالت ما تأمین این نیاز در بالاترین سطح ممکن می‌باشد. به همین دلیل، محصولات ما با دقت و حساسیت ویژه طراحی و ساخته می‌شوند تا در شرایط مختلف آب و هوایی و برای مصارف خانگی، صنعتی و کشاورزی قابل استفاده باشند.</p>
                    <p>ویژگی‌های برجسته ما:</p>
                    <ul>
                        <li>تولید دستگاه‌های تصفیه آب خانگی، نیمه‌صنعتی و صنعتی</li>
                        <li>طراحی و ساخت تانکرهای ذخیره آب در ظرفیت‌های مختلف</li>
                        <li>استفاده از بهترین مواد اولیه و قطعات استاندارد</li>
                        <li>ارائه خدمات مشاوره، نصب و پشتیبانی پس از فروش</li>
                        <li>پایبندی به اصول کیفیت، نوآوری و رضایت مشتری</li>
                    </ul>
                    <p>شرکت تک آب صنعت آریا با اتکا به تجربه و دانش متخصصان خود و همچنین اعتماد مشتریان گرامی، مسیر رشد و توسعه را طی کرده و امروز با افتخار محصولات خود را نه‌تنها در استان فارس بلکه در سراسر کشور عرضه می‌نماید.</p>
                    <p>ما چشم‌انداز خود را بر پایه‌ی توسعه فناوری‌های نوین در حوزه تصفیه و ذخیره‌سازی آب ترسیم کرده‌ایم و امیدواریم با تلاش مستمر و همراهی مشتریان عزیز، سهم کوچکی در ارتقای سطح سلامت جامعه و حفاظت از منابع ارزشمند آب داشته باشیم.</p>
                </div>
            </div>

            <div class="" id="product">
                <h3 class="text-center">محصولات تک آب</h3>
            </div>
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
    </body>
</html>
