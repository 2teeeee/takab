<x-admin-layout title="فرمول‌های ساخت">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">فرمول‌های ساخت</h4>
            <div class="text-muted small">
                مدیریت قطعات و مواد اولیه دستگاه‌ها
            </div>
        </div>

        <a
                href="{{ route('admin.product-boms.create') }}"
                class="btn btn-sm btn-primary"
        >
            <i class="bi bi-plus-lg"></i>
            فرمول ساخت جدید
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>دستگاه</th>
                        <th>قطعه</th>
                        <th>مقدار</th>
                        <th>واحد</th>
                        <th>قیمت پایه</th>
                        <th>تأمین‌کنندگان</th>
                        <th>وضعیت</th>
                        <th width="150">عملیات</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($boms as $bom)

                        <tr>
                            <td>
                                {{ $boms->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $bom->product?->title ?? '-' }}
                                </strong>
                            </td>

                            <td>
                                {{ $bom->componentProduct?->title ?? '-' }}
                            </td>

                            <td>
                                {{ $bom->quantity }}
                            </td>

                            <td>
                                {{ $bom->unit ?: '-' }}
                            </td>

                            <td>
                                {{ number_format($bom->unit_price) }}
                            </td>

                            <td>
                                <span class="badge text-bg-secondary">
                                    {{ $bom->suppliers_count }}
                                </span>
                            </td>

                            <td>
                                @if($bom->is_active)
                                    <span class="badge text-bg-success">
                                        فعال
                                    </span>
                                @else
                                    <span class="badge text-bg-secondary">
                                        غیرفعال
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-1">

                                    <a
                                            href="{{ route('admin.product-boms.show', $bom) }}"
                                            class="btn btn-sm btn-outline-info"
                                            title="مشاهده"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                            href="{{ route('admin.product-boms.edit', $bom) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="ویرایش"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                            method="POST"
                                            action="{{ route('admin.product-boms.destroy', $bom) }}"
                                            onsubmit="return confirm('آیا از حذف این فرمول ساخت مطمئن هستید؟')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="حذف"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td
                                    colspan="9"
                                    class="text-center py-5 text-muted"
                            >
                                هنوز فرمول ساختی ثبت نشده است.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        @if($boms->hasPages())
            <div class="card-footer bg-white">
                {{ $boms->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>
