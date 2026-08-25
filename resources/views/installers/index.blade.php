<x-admin-layout
        :title="__('app.installers_list')"
        :header="__('app.installers_list')"
>
    <div class="container py-4">

        {{-- Add installer --}}
        <a href="{{ route('admin.installers.create') }}"
           class="btn btn-sm btn-primary mb-3">
            <i class="bi bi-person-plus"></i>
            {{ __('app.add_installer') }}
        </a>


        {{-- Error message --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif


        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- Search --}}
        <form method="GET"
              action="{{ route('admin.installers.index') }}"
              class="row g-2 mb-3">

            <div class="col-md-5">
                <input
                        type="text"
                        name="search"
                        class="form-control form-control-sm"
                        placeholder="{{ __('app.search_installer_placeholder') }}"
                        value="{{ request('search') }}"
                >
            </div>

            <div class="col-auto">
                <button type="submit"
                        class="btn btn-dark btn-sm">
                    <i class="bi bi-search"></i>
                    {{ __('app.search') }}
                </button>
            </div>

            @if(request()->filled('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.installers.index') }}"
                       class="btn btn-danger btn-sm">
                        <i class="bi bi-x-circle"></i>
                        {{ __('app.clear_search') }}
                    </a>
                </div>
            @endif

        </form>


        {{-- Installers table --}}
        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.name') }}</th>
                    <th>{{ __('app.mobile') }}</th>
                    <th>{{ __('app.national_code') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.experience') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>
                            {{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}
                        </td>

                        <td>
                            {{ $user->name }}
                        </td>

                        <td dir="ltr">
                            {{ $user->mobile }}
                        </td>

                        <td dir="ltr">
                            {{ $user->national_code }}
                        </td>

                        <td>

                            @php
                                $status = optional($user->installer)->status;
                            @endphp

                            @if($status === 'approved')

                                <span class="badge bg-success">
                                    {{ __('app.approved') }}
                                </span>

                            @elseif($status === 'rejected')

                                <span class="badge bg-danger">
                                    {{ __('app.rejected') }}
                                </span>

                            @elseif($status === 'pending')

                                <span class="badge bg-warning text-dark">
                                    {{ __('app.pending') }}
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ __('app.unknown') }}
                                </span>

                            @endif

                        </td>

                        <td>
                            @if(optional($user->installer)->experience !== null)
                                {{ $user->installer->experience }}
                                {{ __('app.years') }}
                            @else
                                <span class="text-muted">
                                    {{ __('app.not_set') }}
                                </span>
                            @endif
                        </td>

                        <td>

                            <div class="d-flex flex-wrap gap-1">

                                {{-- Details --}}
                                <a href="{{ route('admin.installers.show', $user) }}"
                                   class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i>
                                    {{ __('app.view') }}
                                </a>

                                @if($status === 'approved')
                                <a
                                        href="{{ route('admin.installers.wholesalers', $user) }}"
                                        class="btn btn-sm btn-info"
                                >
                                    <i class="bi bi-link-45deg"></i>
                                    {{ __('installers.actions.wholesalers') }}
                                </a>
                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="text-center text-muted py-4">

                            <i class="bi bi-person-x fs-4 d-block mb-2"></i>

                            {{ __('app.no_installers_found') }}

                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="mt-3">
            {{ $users->links() }}
        </div>

    </div>
</x-admin-layout>