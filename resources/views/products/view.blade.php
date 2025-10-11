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
                    <img src="{{ asset('storage/' . $product->mainImage->large_image_name) }}" class="card-img-top" alt="{{$product->title}}">
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
                            <div class="mt-3">
                                <div id="cart-actions">
                                    @if($quantity > 0)
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button class="btn btn-sm btn-outline-danger me-2" onclick="updateCart({{ $product->id }}, -1)">-</button>
                                            <span id="quantity">{{ $quantity }}</span>
                                            <button class="btn btn-sm btn-outline-success ms-2" onclick="updateCart({{ $product->id }}, 1)">+</button>
                                        </div>
                                    @else
                                        <button class="btn btn-outline-success" onclick="updateCart({{ $product->id }}, 1)">
                                            افزودن به سبد
                                        </button>
                                    @endif
                                </div>
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

    <script>
        function updateCart(productId, change) {
            fetch(`/cart/add/${productId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ quantity: change })
            })
                .then(res => res.json())
                .then(data => {
                    let item = data.items.find(i => i.product_id === productId);
                    let cartActions = document.getElementById('cart-actions');

                    if (item) {
                        cartActions.innerHTML = `
                        <div class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-sm btn-outline-danger me-2" onclick="updateCart(${productId}, -1)">-</button>
                            <span id="quantity">${item.quantity}</span>
                            <button class="btn btn-sm btn-outline-success ms-2" onclick="updateCart(${productId}, 1)">+</button>
                        </div>`;
                    } else {
                        cartActions.innerHTML = `
                        <button class="btn btn-outline-success" onclick="updateCart(${productId}, 1)">
                            افزودن به سبد
                        </button>`;
                    }

                    updateCartBadge(data.totalQuantity);
                })
                .catch(err => console.error(err));
        }
    </script>

</x-main-layout>

