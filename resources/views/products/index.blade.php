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
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('آیا از حذف اطمینان دارید؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">هیچ محصولی یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
</x-admin-layout>
