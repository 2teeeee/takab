<x-admin-layout
        :title="__('installers.wholesalers.title')"
        :header="__('installers.wholesalers.title')"
>

    <div class="container py-4">

        {{-- Success --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">

            <div class="card-header">
                <strong>
                    {{ __('installers.wholesalers.title') }}
                </strong>

                <div class="small text-muted mt-1">
                    {{ $user->name }}
                    -
                    {{ $user->mobile }}
                </div>
            </div>

            <div class="card-body">

                <form
                        method="POST"
                        action="{{ route('admin.installers.wholesalers.sync', $user) }}"
                >
                    @csrf
                    @method('PUT')

                    <div class="row">

                        @forelse($wholesalers as $wholesaler)

                            <div class="col-md-6 col-lg-4 mb-3">

                                <div class="form-check border rounded p-3">

                                    <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="wholesalers[]"
                                            value="{{ $wholesaler->id }}"
                                            id="wholesaler-{{ $wholesaler->id }}"
                                            @checked(
                                                $user->installer->wholesalers->contains(
                                                    'id',
                                                    $wholesaler->id
                                                )
                                            )
                                    >

                                    <label
                                            class="form-check-label"
                                            for="wholesaler-{{ $wholesaler->id }}"
                                    >
                                        <strong>
                                            {{ $wholesaler->name }}
                                        </strong>

                                        <div class="small text-muted">
                                            {{ $wholesaler->mobile }}
                                        </div>
                                    </label>

                                </div>

                            </div>

                        @empty

                            <div class="col-12">
                                <div class="alert alert-warning">
                                    {{ __('installers.wholesalers.empty') }}
                                </div>
                            </div>

                        @endforelse

                    </div>

                    @if($wholesalers->count())

                        <div class="mt-3">

                            <button
                                    type="submit"
                                    class="btn btn-primary"
                            >
                                <i class="bi bi-save"></i>
                                {{ __('common.save') }}
                            </button>

                            <a
                                    href="{{ route('admin.installers.index') }}"
                                    class="btn btn-secondary"
                            >
                                {{ __('common.back') }}
                            </a>

                        </div>

                    @endif

                </form>

            </div>

        </div>

    </div>

</x-admin-layout>