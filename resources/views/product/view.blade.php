<x-main-layout>
    <div class="bg-product-detail py-2">
        <div class="container rounded-1 shadow bg-white">
            <div class="text-small">
                <a href="{{route('main.index')}}" class="text-secondary text-decoration-none">خانه</a> |
                <a href="{{route('main.index')}}" class="text-secondary text-decoration-none">{{$product->category?->title}}</a> |
                <span class="text-dark">{{$product->title}}</span>

            </div>
        </div>
    </div>
</x-main-layout>
