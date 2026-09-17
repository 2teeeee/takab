<x-admin-layout title="فرمول‌های ساخت">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="bi bi-diagram-3"></i>
                فرمول‌های ساخت
            </h4>

            <div class="text-muted small">
                مدیریت فرمول ساخت دستگاه‌های تولیدی
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

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th width="60">
                            #
                        </th>

                        <th>
                            دستگاه تولیدی
                        </th>

                        <th class="text-center">
                            تعداد قطعات
                        </th>

                        <th class="text-center">
                            تأمین‌کنندگان
                        </th>

                        <th class="text-center">
                            وضعیت
                        </th>

                        <th width="150">
                            عملیات
                        </th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse($products as $product)

                        @php

                            $activeBoms =
                                $product->boms
                                    ->where('is_active', true);

                            $suppliersCount =
                                $activeBoms
                                    ->sum('suppliers_count');

                        @endphp


                        <tr>

                            {{-- Number --}}
                            <td>

                                {{ $products->firstItem() + $loop->index }}

                            </td>


                            {{-- Product --}}
                            <td>

                                <div class="fw-bold">

                                    {{ $product->translation?->title }}

                                </div>

                                <small class="text-muted">

                                    #{{ $product->id }}

                                </small>

                            </td>


                            {{-- Components --}}
                            <td class="text-center">

                                <span class="badge text-bg-primary">

                                    {{ number_format($product->active_boms_count) }}

                                </span>

                                <div class="small text-muted mt-1">

                                    قطعه / ماده اولیه

                                </div>

                            </td>


                            {{-- Suppliers --}}
                            <td class="text-center">

                                @if($suppliersCount > 0)

                                    <span class="badge text-bg-secondary">

                                        {{ number_format($suppliersCount) }}

                                    </span>

                                @else

                                    <span class="badge text-bg-warning">

                                        بدون تأمین‌کننده

                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="text-center">

                                @if($product->active_boms_count > 0)

                                    <span class="badge text-bg-success">

                                        دارای فرمول

                                    </span>

                                @else

                                    <span class="badge text-bg-secondary">

                                        بدون فرمول فعال

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

                                    @php
                                        $firstBom =
                                            $product->boms->first();
                                    @endphp


                                    @if($firstBom)

{{--                                        --}}{{-- Show --}}
{{--                                        <a--}}
{{--                                                href="{{ route(--}}
{{--                                                'admin.product-boms.show',--}}
{{--                                                $firstBom--}}
{{--                                            ) }}"--}}
{{--                                                class="btn btn-sm btn-outline-info"--}}
{{--                                                title="مشاهده فرمول"--}}
{{--                                        >--}}

{{--                                            <i class="bi bi-eye"></i>--}}

{{--                                        </a>--}}


                                        {{-- Edit --}}
                                        <a
                                                href="{{ route(
                                                'admin.product-boms.edit',
                                                $firstBom
                                            ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="ویرایش فرمول کامل"
                                        >

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                    colspan="6"
                                    class="text-center py-5 text-muted"
                            >

                                <i class="bi bi-diagram-3 fs-1 d-block mb-3"></i>

                                هنوز فرمول ساختی برای دستگاه‌ها ثبت نشده است.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($products->hasPages())

            <div class="card-footer bg-white">

                {{ $products->links() }}

            </div>

        @endif

    </div>

</x-admin-layout>