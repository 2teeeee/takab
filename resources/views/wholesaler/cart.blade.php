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
                            <form action="{{ route('wholesaler.products.decrease',$item->product) }}"
                                  method="POST">
                                @csrf
                                <button class="btn btn-warning">
                                    -
                                </button>
                            </form>
                            <span class="btn btn-light">
                                {{ $item->quantity }}
                            </span>
                            <form action="{{ route('wholesaler.products.increase',$item->product) }}"
                                  method="POST">
                                @csrf
                                <button class="btn btn-success">
                                    +
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
                        <form
                                action="{{ route('wholesaler.products.remove',$item->product) }}"
                                method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                    class="btn btn-danger">
                                حذف
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
                        action="{{ route('wholesaler.checkout') }}"
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