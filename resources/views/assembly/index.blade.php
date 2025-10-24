<x-main-layout>
    @section('title', 'اسمبل دستگاه تصفیه آب')

    <div class="container my-2 py-4 border rounded shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">🧩 اسمبل دستگاه تصفیه آب</h4>
            <a href="{{ route('main.index') }}" class="btn btn-outline-secondary btn-sm">بازگشت</a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @elseif(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('assembly.store') }}" method="POST" id="assemblyForm">
            @csrf

            @foreach($categories as $category)
                @if($category->products->count())
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 mb-3">{{ $category->title }}</h5>

                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                            @foreach($category->products as $product)
                                <div class="col">
                                    <input type="radio"
                                           class="btn-check part-radio"
                                           name="selected_parts[{{ $category->id }}]"
                                           id="product{{ $product->id }}"
                                           value="{{ $product->id }}"
                                           data-price="{{ $product->sell_price }}"
                                           required>

                                    <label class="card h-100 card-product border rounded-3 overflow-hidden position-relative product-option"
                                           for="product{{ $product->id }}"
                                           style="cursor: pointer;">
                                        @if($product->main_price != $product->sell_price)
                                            <div class="bg-danger p-1 rounded-2 position-absolute text-white text-small ltr" style="top:5px; left:5px;">
                                                {{ 100 - round($product->sell_price * 100 / $product->main_price) }}%
                                            </div>
                                        @endif

                                        <img src="{{ asset('storage/' . $product->small_image_name) }}"
                                             class="card-img-top"
                                             alt="{{ $product->title }}"
                                             style="object-fit: cover; height: 150px;">

                                        <div class="card-body pb-1 text-center">
                                            <div class="fw-bold card-title">{{ $product->title }}</div>
                                        </div>

                                        <div class="card-footer bg-light">
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
                                                    {{ number_format($product->sell_price) }}
                                                    <span class="text-xsmall">تومان</span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- نمایش جمع کل --}}
            <div class="text-center mt-5 pt-3 border-top">
                <h5>💰 جمع کل دستگاه: <span id="totalPrice" class="text-success">0</span> تومان</h5>
                <button type="submit" class="btn btn-success btn-lg px-5 mt-3">
                    افزودن دستگاه به سبد خرید
                </button>
            </div>
        </form>
    </div>

    <style>
        .btn-check:checked + label {
            border: 2px solid #198754 !important;
            box-shadow: 0 0 10px rgba(25, 135, 84, 0.4);
            transform: scale(1.02);
            transition: all 0.2s ease-in-out;
        }

        .btn-check + label {
            transition: all 0.2s ease-in-out;
        }

        .btn-check + label:hover {
            transform: scale(1.02);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.part-radio');
            const totalPriceElement = document.getElementById('totalPrice');

            function updateTotal() {
                let total = 0;
                document.querySelectorAll('.part-radio:checked').forEach(radio => {
                    total += parseFloat(radio.dataset.price);
                });
                totalPriceElement.textContent = total.toLocaleString('fa-IR');
            }

            radios.forEach(radio => {
                radio.addEventListener('change', updateTotal);
            });

            // مقدار اولیه (اگر قبلاً انتخابی وجود دارد)
            updateTotal();
        });
    </script>
</x-main-layout>
