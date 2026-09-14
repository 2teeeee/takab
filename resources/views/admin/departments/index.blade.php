<x-admin-layout title="دپارتمان‌ها">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">دپارتمان‌ها</h4>
                <p class="text-muted mb-0">
                    مدیریت کاربران مسئول هر دپارتمان
                </p>
            </div>
        </div>


        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1"></i>
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
                            <th>دپارتمان</th>
                            <th>کد</th>
                            <th>تعداد مسئول</th>
                            <th>وضعیت</th>
                            <th class="text-end">عملیات</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($departments as $department)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $department->name }}
                                    </div>
                                </td>

                                <td>
                                    <code>
                                        {{ $department->code }}
                                    </code>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $department->users_count }}
                                        نفر
                                    </span>
                                </td>

                                <td>

                                    @if($department->is_active)

                                        <span class="badge bg-success-subtle text-success">
                                            فعال
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">
                                            غیرفعال
                                        </span>

                                    @endif

                                </td>

                                <td class="text-end">

                                    <a
                                            href="{{ route(
                                            'admin.departments.users',
                                            $department
                                        ) }}"
                                            class="btn btn-sm btn-primary"
                                    >
                                        <i class="bi bi-people me-1"></i>
                                        مدیریت کاربران
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                        colspan="6"
                                        class="text-center text-muted py-5"
                                >
                                    دپارتمانی ثبت نشده است.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-admin-layout>
