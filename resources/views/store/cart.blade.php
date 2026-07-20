<x-admin-layout title="سبد خرید" header="سبد خرید">

    <div class="container py-4">

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>محصول</th>
                <th>تعداد</th>
                <th>قیمت</th>
                <th>جمع</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($cart->items as $item)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $item->product->translation->title }}
                    </td>
                    <td>
                        <div class="btn-group">
                            <form action="{{ route('store.products.quantity',$item->product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="{{ max(0, $item->quantity - 1) }}">
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-dash"></i>
                                </button>
                            </form>
                            <span class="btn btn-light">
                                {{ $item->quantity }}
                            </span>
                            <form action="{{ route('store.products.quantity',$item->product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td>
                        {{ number_format($item->price) }}
                    </td>
                    <td>
                        {{ number_format($item->total) }}
                    </td>
                    <td>
                        <form action="{{ route('store.products.quantity',$item->product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="0">

                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

        <div class="card">
            <div class="card-body">
                <h5>
                    جمع کل :
                    {{ number_format($cart->items->sum('total')) }}
                    تومان
                </h5>
                <hr>
                <h5 class="text-danger">
                    تخفیف عمده فروش :
                    {{ number_format($discount) }} تومان
                </h5>
                <hr>
                <h4 class="text-success">
                    مبلغ نهایی :
                    {{ number_format($final) }} تومان
                </h4>
                <form
                        action="{{ route('store.checkout') }}"
                        method="POST">
                    @csrf
                    <button
                            class="btn btn-success">
                        ثبت سفارش
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>