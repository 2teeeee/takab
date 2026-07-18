<x-admin-layout title="خرید محصولات" header="خرید محصولات">

    <div class="container py-4">
        <div class="text-end">
            <a href="{{ route('wholesaler.cart') }}" class="btn btn-sm btn-primary mb-3">
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
                        </div>

                        <div class="card-footer">
                            <form action="{{ route('wholesaler.products.cart',$product) }}"
                                  method="POST">
                                @csrf
                                <div class="mb-2">
                                    <input
                                            type="number"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            class="form-control">
                                </div>
                                <button
                                        class="btn btn-success w-100">
                                    <i class="bi bi-cart-plus"></i>
                                    افزودن به سبد خرید
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
        {{ $products->links() }}
    </div>
</x-admin-layout>