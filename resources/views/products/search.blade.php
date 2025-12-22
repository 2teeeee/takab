<x-main-layout>
    @section('title', 'تک آب صنعت ارم | جستجو: ' . $query)

    <div class="container py-4">

        <form action="{{ route('search') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="q" class="form-control"
                       value="{{ $query }}" placeholder="{{ __('app.product_search') }}...">
                <button class="btn btn-primary">{{ __('app.search') }}</button>
            </div>
        </form>

        @if($products->count() == 0)
            <div class="alert alert-warning">{{ __('message.NoProductsFound') }}</div>
        @endif

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">

            @foreach($products as $product)
                <a href="{{route('product.view', ['id' => $product->id, 'slug' => $product->slug])}}" class="col text-decoration-none">
                    <div class="card h-100 card-product">
                        @if($product->main_price != $product->sell_price)
                            <div class="bg-danger p-1 rounded-2 position-absolute text-white text-small ltr" style="top:5px; left:5px;">
                                {{ 100 - round($product->sell_price * 100 / $product->main_price) }}%
                            </div>
                        @endif
                        <img src="{{ asset('storage/' . $product->small_image_name) }}" class="card-img-top" alt="{{$product->translation->title}}">
                        <div class="card-body pb-1">
                            <div class="fw-bold card-title text-center">{{$product->translation->title}}</div>
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

</x-main-layout>
