<x-admin-layout title="خرید محصولات" header="خرید محصولات">

    <div class="container py-4">
        <div class="text-end">
            <a href="{{ route('store.cart') }}" class="btn btn-sm btn-primary mb-3">
                <i class="bi bi-basket"></i>
                سبد خرید
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($product->small_image_name)
                            <img src="{{ asset('storage/' . $product->small_image_name) }}" class="card-img-top" alt="{{$product->title}}">
                        @else
                            <img src="{{ asset('img/no-image.png') }}" class="card-img-top" alt="{{$product->title}}">
                        @endif

                        <div class="card-body">
                            <h6>
                                {{ $product->title }}
                            </h6>

                            <div class="text-success fw-bold">
                                {{ number_format($product->sell_price) }}
                                تومان
                            </div>
                            <div class="text-muted">
                                <small>
                                    موجودی:
                                    {{ $product->stock }}
                                </small>
                            </div>
                        </div>

                        <div class="card-footer">
                            @php
                                $quantity = $cartItems[$product->id] ?? 0;
                            @endphp
                            @if($quantity == 0)
                                <form action="{{ route('store.products.cart',$product) }}"
                                      method="POST"
                                      class="d-flex align-items-center justify-content-center gap-1">

                                    @csrf

                                    <input type="number"
                                           name="quantity"
                                           value="1"
                                           min="1"
                                           max="{{ $product->stock }}"
                                           class="form-control form-control-sm text-center"
                                           style="width:70px;">

                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>

                                </form>
                            @else
                                <div class="d-flex align-items-center justify-content-center gap-1">

                                    <form action="{{ route('store.products.quantity',$product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ max(0, $quantity - 1) }}">
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </form>

                                    <input type="text"
                                           class="form-control form-control-sm text-center"
                                           value="{{ $quantity }}"
                                           readonly
                                           style="width:70px;">

                                    <form action="{{ route('store.products.quantity',$product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $quantity + 1 }}">
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('store.products.quantity',$product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="0">

                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
        {{ $products->links() }}
    </div>
</x-admin-layout>