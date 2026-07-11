<x-profile-layout title="ثبت فروش جدید">
    <div class="text-end mb-2">
        <a href="{{route('profile.store.index')}}" class="btn btn-sm btn-dark">بازگشت به فروش های من</a>
    </div>

    {{-- نمایش تمام خطاها --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>لطفاً خطاهای زیر را برطرف کنید:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.store.create') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3 col-md-4">
                <label class="form-label">موبایل خریدار</label>
                <input type="text" name="mobile" value="{{ old('mobile') }}"
                       class="form-control @error('mobile') is-invalid @enderror">
                @error('mobile')
                <div class="invalid-feedback"> {{ $message }} </div>
                @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label">نام خریدار</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                <div class="invalid-feedback"> {{ $message }} </div>
                @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label">کد معرف</label>
                <input type="text" name="moaref" value="{{ old('moaref') }}"
                       class="form-control @error('moaref') is-invalid @enderror">
                @error('moaref')
                <div class="invalid-feedback"> {{ $message }} </div>
                @enderror
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">آدرس</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       class="form-control @error('address') is-invalid @enderror">
                @error('address')
                <div class="invalid-feedback"> {{ $message }} </div>
                @enderror
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">محصول</label>
                <div class="row">
                    <div class="col-md-8">
                        <select name="product_id" id="product_id" class="form-control
                        @error('product_id') is-invalid @enderror">
                            <option value="">انتخاب محصول</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->sell_price }}"
                                        @selected(old('product_id') == $product->id)> {{ $product->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-control bg-light">
                            قیمت: <span id="product_price">0</span> تومان
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success">ثبت سفارش</button>
    </form>
    @push('scripts')
        <script>
            document.getElementById('product_id').addEventListener('change', function () {

                let price = this.options[this.selectedIndex].dataset.price;

                if(price) {
                    document.getElementById('product_price').innerText =
                        new Intl.NumberFormat('fa-IR').format(price);
                } else {
                    document.getElementById('product_price').innerText = 0;
                }

            });
        </script>
    @endpush
</x-profile-layout>
