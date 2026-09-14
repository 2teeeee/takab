<x-admin-layout title="کاربران دپارتمان">

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    کاربران دپارتمان
                </h4>

                <p class="text-muted mb-0">
                    {{ $department->name }}
                </p>

            </div>

            <a
                    href="{{ route('admin.departments.index') }}"
                    class="btn btn-sm btn-outline-secondary"
            >
                <i class="bi bi-arrow-right me-1"></i>
                بازگشت
            </a>

        </div>


        {{-- Success --}}
        @if(session('success'))

            <div class="alert alert-success">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- Errors --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
                method="POST"
                action="{{ route(
                'admin.departments.users.update',
                $department
            ) }}"
        >

            @csrf

            @method('PUT')


            <div class="row g-4">


                {{-- Search Users --}}
                <div class="col-12 col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white py-3">

                            <h6 class="mb-1">
                                <i class="bi bi-search me-1"></i>
                                جستجوی کاربر
                            </h6>

                            <small class="text-muted">
                                کاربر موردنظر را جستجو کرده و با زدن تیک به دپارتمان اضافه کنید.
                            </small>

                        </div>


                        <div class="card-body">

                            <div class="input-group mb-3">

                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                        type="text"
                                        id="userSearch"
                                        class="form-control"
                                        placeholder="نام، موبایل یا ایمیل کاربر..."
                                        autocomplete="off"
                                >

                            </div>


                            <div
                                    id="searchResults"
                                    class="user-search-results"
                            >

                                <div class="text-center text-muted py-4">

                                    <i class="bi bi-person-search fs-2 d-block mb-2"></i>

                                    برای جستجوی کاربر، نام یا شماره موبایل را وارد کنید.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Selected Users --}}
                <div class="col-12 col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white py-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h6 class="mb-1">
                                        <i class="bi bi-people me-1"></i>
                                        مسئولین دپارتمان
                                    </h6>

                                    <small class="text-muted">
                                        کاربران انتخاب‌شده
                                    </small>

                                </div>


                                <span
                                        class="badge bg-primary"
                                        id="selectedCount"
                                >
                                    {{ $selectedUsers->count() }} نفر
                                </span>

                            </div>

                        </div>


                        <div class="card-body">

                            <div
                                    id="selectedUsers"
                                    class="selected-users"
                            >

                                @forelse($selectedUsers as $user)

                                    <div
                                            class="selected-user-item"
                                            data-user-id="{{ $user->id }}"
                                    >

                                        <input
                                                type="hidden"
                                                name="users[]"
                                                value="{{ $user->id }}"
                                        >


                                        <div class="d-flex align-items-center">

                                            <div class="user-avatar">

                                                {{ mb_substr($user->name, 0, 1) }}

                                            </div>


                                            <div class="flex-grow-1">

                                                <div class="fw-semibold">

                                                    {{ $user->name }}

                                                </div>


                                                @if($user->mobile)

                                                    <small class="text-muted">

                                                        {{ $user->mobile }}

                                                    </small>

                                                @elseif($user->email)

                                                    <small class="text-muted">

                                                        {{ $user->email }}

                                                    </small>

                                                @endif

                                            </div>


                                            <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger remove-user"
                                                    data-user-id="{{ $user->id }}"
                                            >

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </div>

                                    </div>

                                @empty

                                    <div
                                            id="emptySelected"
                                            class="text-center text-muted py-5"
                                    >

                                        <i class="bi bi-people fs-1 d-block mb-3"></i>

                                        هنوز کاربری انتخاب نشده است.

                                    </div>

                                @endforelse

                            </div>

                        </div>


                        <div class="card-footer bg-white">

                            <div class="d-flex justify-content-end">

                                <button
                                        type="submit"
                                        class="btn btn-sm btn-primary"
                                >

                                    <i class="bi bi-check-lg me-1"></i>

                                    ذخیره تغییرات

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>


    <style>

        .user-search-results {
            min-height: 200px;
        }

        .user-result-item {
            border: 1px solid var(--bs-border-color);
            border-radius: .75rem;
            padding: .75rem;
            margin-bottom: .75rem;
            transition: .2s ease;
        }

        .user-result-item:hover {
            background-color: var(--bs-light);
        }

        .selected-user-item {
            border: 1px solid var(--bs-border-color);
            border-radius: .75rem;
            padding: .75rem;
            margin-bottom: .75rem;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--bs-primary-bg-subtle);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-left: .75rem;
            flex-shrink: 0;
        }

        .add-user-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-user {
            width: 36px;
            height: 36px;
        }

    </style>


    @push('scripts')

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                const searchInput =
                    document.getElementById('userSearch');

                const searchResults =
                    document.getElementById('searchResults');

                const selectedUsers =
                    document.getElementById('selectedUsers');

                const selectedCount =
                    document.getElementById('selectedCount');


                /*
                 * کاربران انتخاب‌شده
                 */
                const selected = new Map();


                document
                    .querySelectorAll('.selected-user-item')
                    .forEach(function (item) {

                        const id =
                            String(item.dataset.userId);

                        const input =
                            item.querySelector('input[name="users[]"]');

                        selected.set(id, {
                            id: id,
                            name: item.querySelector('.fw-semibold')?.textContent.trim() ?? '',
                            mobile: item.querySelector('.text-muted')?.textContent.trim() ?? ''
                        });

                    });


                /*
                 * بروزرسانی تعداد
                 */
                function updateCount() {

                    selectedCount.textContent =
                        `${selected.size} نفر`;

                }


                /*
                 * حذف کاربر
                 */
                function removeUser(id) {

                    id = String(id);

                    selected.delete(id);

                    const item =
                        selectedUsers.querySelector(
                            `[data-user-id="${id}"]`
                        );

                    if (item) {
                        item.remove();
                    }


                    if (selected.size === 0) {

                        selectedUsers.innerHTML = `
                            <div
                                id="emptySelected"
                                class="text-center text-muted py-5"
                            >
                                <i class="bi bi-people fs-1 d-block mb-3"></i>
                                هنوز کاربری انتخاب نشده است.
                            </div>
                        `;

                    }


                    updateCount();

                    renderSearchResults();
                }


                /*
                 * اضافه کردن کاربر
                 */
                function addUser(user) {

                    const id =
                        String(user.id);

                    if (selected.has(id)) {
                        return;
                    }


                    selected.set(id, user);


                    const empty =
                        document.getElementById('emptySelected');

                    if (empty) {
                        empty.remove();
                    }


                    const item =
                        document.createElement('div');

                    item.className =
                        'selected-user-item';

                    item.dataset.userId =
                        id;


                    item.innerHTML = `

                        <input
                            type="hidden"
                            name="users[]"
                            value="${escapeHtml(id)}"
                        >

                        <div class="d-flex align-items-center">

                            <div class="user-avatar">

                                ${escapeHtml(
                        user.name.substring(0, 1)
                    )}

                            </div>

                            <div class="flex-grow-1">

                                <div class="fw-semibold">

                                    ${escapeHtml(user.name)}

                                </div>

                                ${
                        user.mobile
                            ? `
                                        <small class="text-muted">
                                            ${escapeHtml(user.mobile)}
                                        </small>
                                    `
                            : user.email
                                ? `
                                        <small class="text-muted">
                                            ${escapeHtml(user.email)}
                                        </small>
                                    `
                                : ''
                    }

                            </div>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger remove-user"
                                data-user-id="${escapeHtml(id)}"
                            >

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>
                    `;


                    selectedUsers.appendChild(item);


                    updateCount();

                    renderSearchResults();

                }


                /*
                 * جلوگیری از XSS
                 */
                function escapeHtml(value) {

                    const div =
                        document.createElement('div');

                    div.textContent =
                        value ?? '';

                    return div.innerHTML;

                }


                /*
                 * نمایش نتایج جستجو
                 */
                function renderSearchResults() {

                    const keyword =
                        searchInput.value.trim().toLowerCase();


                    if (!keyword) {

                        searchResults.innerHTML = `

                            <div class="text-center text-muted py-4">

                                <i class="bi bi-person-search fs-2 d-block mb-2"></i>

                                برای جستجوی کاربر، نام یا شماره موبایل را وارد کنید.

                            </div>

                        `;

                        return;
                    }


                    searchResults.innerHTML = `

                        <div class="text-center text-muted py-4">

                            <div class="spinner-border spinner-border-sm"></div>

                            <div class="mt-2">
                                در حال جستجو...
                            </div>

                        </div>

                    `;


                    fetch(
                        `{{ route('admin.users.search') }}?q=${encodeURIComponent(keyword)}`,
                        {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    )
                        .then(response => {

                            if (!response.ok) {
                                throw new Error('خطا در دریافت کاربران');
                            }

                            return response.json();

                        })
                        .then(users => {

                            if (!users.length) {

                                searchResults.innerHTML = `

                                <div class="text-center text-muted py-4">

                                    <i class="bi bi-person-x fs-2 d-block mb-2"></i>

                                    کاربری پیدا نشد.

                                </div>

                            `;

                                return;
                            }


                            searchResults.innerHTML = '';


                            users.forEach(function (user) {

                                const id =
                                    String(user.id);


                                /*
                                 * اگر قبلاً انتخاب شده،
                                 * نمایش نده.
                                 */
                                if (selected.has(id)) {
                                    return;
                                }


                                const item =
                                    document.createElement('div');

                                item.className =
                                    'user-result-item';


                                item.innerHTML = `

                                <div class="d-flex align-items-center">

                                    <div class="user-avatar">

                                        ${escapeHtml(
                                    user.name.substring(0, 1)
                                )}

                                    </div>

                                    <div class="flex-grow-1">

                                        <div class="fw-semibold">

                                            ${escapeHtml(user.name)}

                                        </div>

                                        ${
                                    user.mobile
                                        ? `
                                                <small class="text-muted">
                                                    ${escapeHtml(user.mobile)}
                                                </small>
                                            `
                                        : user.email
                                            ? `
                                                <small class="text-muted">
                                                    ${escapeHtml(user.email)}
                                                </small>
                                            `
                                            : ''
                                }

                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary add-user-btn"
                                        data-user-id="${escapeHtml(id)}"
                                    >

                                        <i class="bi bi-check-lg"></i>

                                    </button>

                                </div>
                            `;


                                item
                                    .querySelector('.add-user-btn')
                                    .addEventListener(
                                        'click',
                                        function () {

                                            addUser(user);

                                        }
                                    );


                                searchResults.appendChild(item);

                            });


                            if (!searchResults.children.length) {

                                searchResults.innerHTML = `

                                <div class="text-center text-muted py-4">

                                    همه کاربران پیدا شده انتخاب شده‌اند.

                                </div>

                            `;

                            }

                        })
                        .catch(function () {

                            searchResults.innerHTML = `

                            <div class="alert alert-danger">

                                خطا در جستجوی کاربران.

                            </div>

                        `;

                        });

                }


                /*
                 * جستجو با کمی تأخیر
                 */
                let searchTimer;

                searchInput.addEventListener(
                    'input',
                    function () {

                        clearTimeout(searchTimer);

                        searchTimer =
                            setTimeout(
                                renderSearchResults,
                                300
                            );

                    }
                );


                /*
                 * حذف کاربران موجود
                 */
                selectedUsers.addEventListener(
                    'click',
                    function (event) {

                        const button =
                            event.target.closest('.remove-user');

                        if (!button) {
                            return;
                        }

                        removeUser(
                            button.dataset.userId
                        );

                    }
                );


                updateCount();

            });

        </script>

    @endpush

</x-admin-layout>