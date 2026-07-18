<x-admin-layout
        title="موجودی محصولات"
        header="موجودی محصولات {{ $user->name }}">

    <div class="container py-4">

        <div class="d-flex justify-content-between mb-3">

            <a href="{{ route('admin.users.product-user.create',$user) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                افزودن محصول

            </a>

        </div>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-light">

            <tr>

                <th width="60">#</th>

                <th>کد</th>

                <th>نام محصول</th>

                <th width="120">تعداد</th>

                <th width="180">عملیات</th>

            </tr>

            </thead>

            <tbody>

            @forelse($products as $item)

                <tr>

                    <td>

                        {{ $loop->iteration + ($products->currentPage()-1) * $products->perPage() }}

                    </td>

                    <td>

                        {{ $item->product->code }}

                    </td>

                    <td>

                        {{ $item->product->title }}

                    </td>

                    <td>

                        {{ number_format($item->quantity) }}

                    </td>

                    <td>

                        <a href="{{ route('admin.users.product-user.edit',[$user,$item]) }}"
                           class="btn btn-warning btn-sm">

                            <i class="bi bi-pencil"></i>

                            ویرایش

                        </a>

                        <form
                                action="{{ route('admin.users.product-user.destroy',[$user,$item]) }}"
                                method="POST"
                                class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('آیا حذف شود؟')">

                                <i class="bi bi-trash"></i>

                                حذف

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="text-center">

                        هیچ محصولی ثبت نشده است.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $products->links() }}

    </div>

</x-admin-layout>