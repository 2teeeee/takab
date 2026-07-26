<x-admin-layout title="لیست کاربران" header="لیست کاربران">
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET"
              action="{{ route('wholesaler.stores.index') }}"
              class="row g-2 mb-3">
            <div class="col-md-5">
                <input
                        type="text"
                        name="search"
                        class="form-control form-control-sm"
                        placeholder="جستجو بر اساس نام، موبایل یا کد ملی..."
                        value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-dark btn-sm">
                    <i class="bi bi-search"></i>
                    جستجو
                </button>
            </div>

            @if(request()->filled('search'))
                <div class="col-auto">
                    <a href="{{ route('wholesaler.stores.index') }}"
                       class="btn btn-danger btn-sm">
                        حذف جستجو
                    </a>
                </div>
            @endif
        </form>

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>موبایل</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stores as $store)
                <tr>
                    <td>{{ $loop->iteration + ($stores->currentPage() - 1) * $stores->perPage() }}</td>
                    <td>{{ $store->name }}</td>
                    <td>{{ $store->mobile }}</td>
                    <td>
                        <a href="{{ route('wholesaler.stores.sale',$store) }}" class="btn btn-sm btn-success">فروش</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">هیچ فروشگاهی یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $stores->links() }}
    </div>
</x-admin-layout>
