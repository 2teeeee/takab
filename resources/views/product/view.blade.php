<x-main-layout>
    @section('title', 'تک آب صنعت ارم | '.$product->title)
    @section('description', $product->description)
    @section('keywords', $product->keywords)

    <div class="bg-product-detail py-2">
        <div class="container rounded-1 px-0 shadow bg-white">
            <div class="text-small rounded-top-1 mb-2 p-1 bg-product-gray">
                <a href="{{route('main.index')}}" class="text-secondary text-decoration-none">خانه</a> |
                <a href="{{route('main.index')}}" class="text-secondary text-decoration-none">{{$product->category?->title}}</a> |
                <span class="text-dark">{{$product->title}}</span>
            </div>
            <div class="row border-bottom">
                <div class="col-md-3 py-2">
                    <img src="{{asset("img/product/".$product->mainImage())}}" class="card-img-top" alt="{{$product->title}}">
                </div>
                <div class="col-md-9">
                    <h5 class="border-bottom pb-2">{{$product->title}}</h5>
                    <div class="row">
                        <div class="col-md-7 text-small">
                            {{$product->small_text}}
                        </div>
                        <div class="col-md-5 text-center">
                            <div class="py-2 @if($product->main_price != $product->sell_price) border-bottom @endif">
                                @if($product->main_price != $product->sell_price)
                                    <span class="text-danger text-decoration-line-through">
                                        {{ number_format($product->main_price) }}
                                    </span>
                                    <span class="text-danger text-small">تومان</span>
                                @endif
                            </div>
                            <div class="py-2">
                                {{ number_format($product->sell_price) }} <span class="text-small">تومان</span>
                            </div>
                            <div class="btn btn-outline-success mt-3">
                                برای ثبت سفارش تماس بگیرید.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3">
                <h5>توضیحات محصول</h5>
                {!! $product->large_text !!}
            </div>
        </div>
    </div>
</x-main-layout>
