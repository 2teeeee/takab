<x-admin-layout title="ویرایش موجودی محصول" header="ویرایش موجودی محصول">

    <div class="container py-4">

        <form action="{{ route('admin.users.product-user.update', [$user, $productUser]) }}"
              method="POST"
              class="card shadow-sm p-4">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">محصول</label>

                <input type="text"
                       class="form-control"
                       value="{{ $productUser->product->title }}"
                       readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">تعداد موجودی</label>

                <input type="number"
                       name="quantity"
                       value="{{ old('quantity', $productUser->quantity) }}"
                       min="0"
                       class="form-control"
                       required>

                @error('quantity')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-between">

                <a href="{{ route('admin.users.product-user.index', $user) }}"
                   class="btn btn-secondary">

                    بازگشت

                </a>

                <button type="submit" class="btn btn-success">

                    <i class="bi bi-check-circle"></i>

                    ذخیره تغییرات

                </button>

            </div>

        </form>

    </div>

</x-admin-layout>