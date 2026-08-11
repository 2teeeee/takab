<x-admin-layout title="لیست محصولات" header="لیست محصولات">
    <div class="container py-4">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">افزودن محصول جدید</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>دسته</th>
                <th>قیمت اصلی</th>
                <th>قیمت فروش</th>
                <th>موجودی</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                    <td>{{ $product->translation?->title }}</td>
                    <td>{{ $product->category?->translation?->title ?? '-' }}</td>
                    <td>{{ number_format($product->main_price) }} تومان</td>
                    <td>{{ number_format($product->sell_price) }} تومان</td>
                    <td>
                        @php
                            $stock = $product->productUsers->first()?->quantity ?? 0;
                        @endphp

                        <div class="d-flex align-items-center gap-1">
                            <span class="badge text-bg-secondary">
                                {{ number_format($stock) }}
                            </span>

                            <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#stockModal{{ $product->id }}"
                                    title="ویرایش موجودی"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $product->status ? 'text-bg-success' : 'text-bg-danger' }}">{{ $product->statusText() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('آیا از حذف اطمینان دارید؟')">حذف</button>
                        </form>
                    </td>
                </tr>

                <div
                        class="modal fade"
                        id="stockModal{{ $product->id }}"
                        tabindex="-1"
                        aria-hidden="true"
                >
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <form
                                    action="{{ route('admin.products.stock.update', $product) }}"
                                    method="POST"
                            >
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        ویرایش موجودی
                                    </h5>
                                    <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"
                                    ></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            محصول
                                        </label>
                                        <div class="text-muted">
                                            {{ $product->translation?->title }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            تعداد موجودی
                                        </label>
                                        <input
                                                type="number"
                                                name="quantity"
                                                min="0"
                                                class="form-control"
                                                value="{{ $stock }}"
                                                required
                                        >
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button
                                            type="button"
                                            class="btn btn-sm btn-secondary"
                                            data-bs-dismiss="modal"
                                    >
                                        انصراف
                                    </button>
                                    <button
                                            type="submit"
                                            class="btn btn-sm btn-primary"
                                    >
                                        ذخیره
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr><td colspan="6" class="text-center">هیچ محصولی یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
</x-admin-layout>
