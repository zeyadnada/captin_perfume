@extends('layouts.admin', ['title' => __('admin.roles.title')])

@section('content')
    <x-page-header :title="__('admin.roles.title')" :subtitle="__('admin.roles.index_subtitle')">
        <x-slot:actions>
            @can('create', Spatie\Permission\Models\Role::class)
                <a href="{{ route('admin.roles.create') }}" class="au-btn au-btn--green au-btn--small">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('admin.roles.add') }}
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('admin.common.close') }}"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('admin.common.close') }}"></button>
        </div>
    @endif

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <form method="GET" action="{{ route('admin.roles.index') }}" class="row g-3 align-items-end mb-4 p-3 rounded bg-light">
                <div class="col-md-8">
                    <label class="form-label" for="search">{{ __('admin.common.search') }}</label>
                    <input class="au-input" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.roles.search_placeholder') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 px-3" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i>
                        {{ __('admin.common.filter') }}
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" aria-label="{{ __('admin.common.reset_filters') }}">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin.common.name') }}</th>
                            <th>{{ __('admin.roles.permissions_count') }}</th>
                            <th>{{ __('admin.roles.users_count') }}</th>
                            <th class="text-end">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $roles->firstItem() + $loop->index }}</td>
                                <td>
                                    <code>{{ $role->name }}</code>
                                    @if ($role->name === \App\Enums\Role::SuperAdmin->value)
                                        <span class="badge bg-primary ms-1">{{ __('admin.roles.system') }}</span>
                                    @endif
                                </td>
                                <td>{{ $role->permissions_count }}</td>
                                <td>{{ $role->users_count }}</td>
                                <td class="text-end">
                                    <div class="table-data-feature">
                                        @can('update', $role)
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.common.edit_named', ['name' => $role->name]) }}">
                                                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $role)
                                            @if ($role->name !== \App\Enums\Role::SuperAdmin->value)
                                                <button class="btn btn-danger btn-sm delete-role-trigger d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" type="button" data-confirm-text="{{ __('admin.common.confirm_delete_named', ['name' => $role->name]) }}" data-delete-url="{{ route('admin.roles.destroy', $role) }}" aria-label="{{ __('admin.common.delete_named', ['name' => $role->name]) }}">
                                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">{{ __('admin.roles.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    <form id="delete-role-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-role-trigger').forEach(button => {
                button.addEventListener('click', async () => {
                    const { isConfirmed } = await Swal.fire({
                        title: @json(__('admin.roles.confirm_delete_title')),
                        text: button.dataset.confirmText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: @json(__('admin.common.confirm_delete_button')),
                        confirmButtonColor: '#dc3545'
                    });

                    if (isConfirmed) {
                        document.getElementById('delete-role-form').action = button.dataset.deleteUrl;
                        document.getElementById('delete-role-form').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
