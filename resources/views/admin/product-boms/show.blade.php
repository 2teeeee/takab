<x-admin-layout title="جزئیات فرمول ساخت">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                جزئیات فرمول ساخت
            </h4>

            <div class="text-muted">
                {{ $productBom->product?->title }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                    href="{{ route('admin.product-boms.edit', $productBom) }}"
                    class="btn btn-sm btn-primary"
            >
                <i class="bi bi-pencil"></i>
                ویرایش
            </a>

            <a
                    href="{{ route('admin.product-boms.index') }}"
                    class="btn btn-sm btn-outline-secondary"
            >
                بازگشت
            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        دستگاه
                    </div>

                    <strong>
                        {{ $productBom->product?->title ?? '-' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        قطعه
                    </div>

                    <strong>
                        {{ $productBom->componentProduct?->title ?? '-' }}
                    </strong>

                </div>

                <div class="col-md-2">

                    <div class="text-muted small">
                        مقدار
                    </div>

                    <strong>
                        {{ $productBom->quantity }}
                    </strong>

                </div>

                <div class="col-md-2">

                    <div class="text-muted small">
                        واحد
                    </div>

                    <strong>
                        {{ $productBom->unit ?: '-' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        قیمت پایه
                    </div>

                    <strong>
                        {{ number_format($productBom->unit_price) }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        وضعیت
                    </div>

                    @if($productBom->is_active)
                        <span class="badge text-bg-success">
                            فعال
                        </span>
                    @else
                        <span class="badge text-bg-secondary">
                            غیرفعال
                        </span>
                    @endif

                </div>

                @if($productBom->note)

                    <div class="col-12">

                        <div class="text-muted small">
                            توضیحات
                        </div>

                        <div>
                            {{ $productBom->note }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <strong>
                تأمین‌کنندگان
            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>
                        <th>تأمین‌کننده</th>
                        <th>موبایل</th>
                        <th>قیمت</th>
                        <th>وضعیت</th>
                        <th>پیش‌فرض</th>
                        <th>توضیحات</th>
                    </tr>

                    </thead>

                    <tbody>

                    @forelse($productBom->suppliers as $item)

                        <tr>

                            <td>
                                {{ $item->supplier?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $item->supplier?->mobile ?? '-' }}
                            </td>

                            <td>
                                {{ number_format($item->unit_price) }}
                            </td>

                            <td>

                                @if($item->is_active)
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

                                @if($item->is_default)
                                    <span class="badge text-bg-primary">
                                        پیش‌فرض
                                    </span>
                                @else
                                    -
                                @endif

                            </td>

                            <td>
                                {{ $item->note ?: '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                            >
                                تأمین‌کننده‌ای ثبت نشده است.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-admin-layout>
