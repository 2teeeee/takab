<x-admin-layout title="لیست کاربران" header="لیست کاربران">
    <div class="container py-4">

        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary mb-3">افزودن کاربر جدید</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET"
              action="{{ route('admin.users.index') }}"
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
                    <a href="{{ route('admin.users.index') }}"
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
                <th>کد شناسایی</th>
                <th>نام</th>
                <th>موبایل</th>
                <th>نقش‌ها</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>{{ $user->moaref_code }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->roles->pluck('label')->join(', ') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        @if(auth()->user()->hasRole(['admin','manager','personel','seller']))
                        <a href="{{ route('store.customers.sale',$user) }}" class="btn btn-sm btn-success">فروش کالا</a>
                        @endif
                        @if(auth()->user()->hasRole(['admin','manager','personel']))
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('آیا از حذف کاربر اطمینان دارید؟')">حذف</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">هیچ کاربری یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</x-admin-layout>
