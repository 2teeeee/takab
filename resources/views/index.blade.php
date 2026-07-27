<x-main-layout>
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($sliders as $key => $slider)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/slider/' . $slider->image_path) }}" class="d-block w-100" alt="{{ $slider->title }}">
                </div>
            @endforeach
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

    <div class="row mx-0 bg-main-light py-3 text-center" id="about">

        <div class="col-3">
            <div class="feature-icon mx-auto mb-2">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="small fw-bold">{{ __('app.QA') }}</div>
        </div>

        <div class="col-3">
            <div class="feature-icon mx-auto mb-2">
                <i class="bi bi-hand-thumbs-up"></i>
            </div>
            <div class="small fw-bold">{{ __('app.CS') }}</div>
        </div>

        <div class="col-3">
            <div class="feature-icon mx-auto mb-2">
                <i class="bi bi-cart-check"></i>
            </div>
            <div class="small fw-bold">{{ __('app.SP') }}</div>
        </div>

        <div class="col-3">
            <div class="feature-icon mx-auto mb-2">
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="small fw-bold">{{ __('app.PG') }}</div>
        </div>

    </div>

    <div class="py-3 bg-product-gray" id="product">
        <h3 class="text-center">{{ __('app.products') }}</h3>
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
                                @if($product->small_image_name)
                                    <img src="{{ asset('storage/' . $product->small_image_name) }}" class="card-img-top" alt="{{$product->title}}">
                                @else
                                    <img src="{{ asset('img/no-image.png') }}" class="card-img-top" alt="{{$product->title}}">
                                @endif
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
                                                <span class="text-danger text-xsmall">{{ __('app.toman') }}</span>
                                            @endif
                                        </div>
                                        <div class="col px-1 text-small">
                                            {{ number_format($product->sell_price) }} <span class="text-xsmall">{{ __('app.toman') }}</span>
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
