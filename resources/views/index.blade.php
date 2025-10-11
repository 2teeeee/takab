<x-main-layout>
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

    <div class="py-3 bg-product-gray" id="product">
        <h3 class="text-center">محصولات تک آب</h3>
        <div class="row justify-content-center mx-0">
            <div class="col-md-10">
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                    @foreach($products as $product)
                        <a href="{{route('product.view', ['id' => $product->id, 'slug' => $product->slug])}}" class="col text-decoration-none">
                            <div class="card h-100 card-product">
                                @if($product->main_price != $product->sell_price)
                                    <div class="bg-danger p-1 rounded-2 position-absolute text-white text-small ltr" style="top:5px; left:5px;">
                                        {{ 100 - round($product->sell_price * 100 / $product->main_price) }}%
                                    </div>
                                @endif
                                <img src="{{ asset('storage/' . $product->small_image_name) }}" class="card-img-top" alt="{{$product->title}}">
                                <div class="card-body pb-1">
                                    <div class="fw-bold card-title text-center">{{$product->title}}</div>
                                </div>
                                <div class="card-footer">
                                    <div class="row text-center">
                                        <div class="col px-1 text-small @if($product->main_price != $product->sell_price) border-end @endif">
                                            @if($product->main_price != $product->sell_price)
                                                <span class="text-danger text-decoration-line-through">
                                                    {{ number_format($product->main_price) }}
                                                </span>
                                                <span class="text-danger text-xsmall">تومان</span>
                                            @endif
                                        </div>
                                        <div class="col px-1 text-small">
                                            {{ number_format($product->sell_price) }} <span class="text-xsmall">تومان</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
