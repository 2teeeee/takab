<x-admin-layout title="افزودن محصول" header="افزودن محصول به {{ $user->name }}">

    <div class="container py-4">

        <form action="{{ route('admin.users.product-user.store', $user) }}"
              method="POST"
              class="card shadow-sm p-4">

            @csrf

            <div class="mb-3">
                <label class="form-label">محصول</label>

                <select name="product_id" class="form-select" required>

                    <option value="">انتخاب محصول...</option>

                    @foreach($products as $product)

                        <option value="{{ $product->id }}"
                                {{ old('product_id') == $product->id ? 'selected' : '' }}>

                            {{ $product->title }}

                        </option>

                    @endforeach

                </select>

                @error('product_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">تعداد</label>

                <input type="number"
                       name="quantity"
                       value="{{ old('quantity',1) }}"
                       min="1"
                       class="form-control">

                @error('quantity')
                <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <div class="d-flex justify-content-between">

                <a href="{{ route('admin.users.product-user.index',$user) }}"
                   class="btn btn-secondary">

                    بازگشت

                </a>

                <button class="btn btn-success">

                    <i class="bi bi-check-circle"></i>

                    ثبت محصول

                </button>

            </div>

        </form>

    </div>

</x-admin-layout>