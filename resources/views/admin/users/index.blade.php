@extends('layouts.admin', ['title' => __('admin.users.title')])

@section('content')
    <x-page-header :title="__('admin.users.title')" :subtitle="__('admin.users.index_subtitle')">
        <x-slot:actions>
            @can('create', App\Models\User::class)
                <a href="{{ route('admin.users.create') }}" class="au-btn au-btn--green au-btn--small">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('admin.users.add') }}
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
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end mb-4 p-3 rounded bg-light">
                <div class="col-md-8">
                    <label class="form-label" for="search">{{ __('admin.common.search') }}</label>
                    <input class="au-input" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.users.search_placeholder') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 px-3" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i>
                        {{ __('admin.common.filter') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" aria-label="{{ __('admin.common.reset_filters') }}">
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
                            <th>{{ __('admin.auth.email') }}</th>
                            <th>{{ __('admin.users.phone') }}</th>
                            <th>{{ __('admin.users.role') }}</th>
                            <th class="text-end">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?: __('admin.common.not_set') }}</td>
                                <td>
                                    @if ($user->roles->first())
                                        <span class="badge bg-secondary">{{ $user->roles->first()->name }}</span>
                                    @else
                                        <span class="text-muted">{{ __('admin.common.not_assigned') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="table-data-feature">
                                        @can('update', $user)
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.common.edit_named', ['name' => $user->name]) }}">
                                                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $user)
                                            <button class="btn btn-danger btn-sm delete-user-trigger d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" type="button" data-confirm-text="{{ __('admin.common.confirm_delete_named', ['name' => $user->name]) }}" data-delete-url="{{ route('admin.users.destroy', $user) }}" aria-label="{{ __('admin.common.delete_named', ['name' => $user->name]) }}">
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">{{ __('admin.users.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <form id="delete-user-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-user-trigger').forEach(button => {
                button.addEventListener('click', async () => {
                    const { isConfirmed } = await Swal.fire({
                        title: @json(__('admin.users.confirm_delete_title')),
                        text: button.dataset.confirmText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: @json(__('admin.common.confirm_delete_button')),
                        confirmButtonColor: '#dc3545'
                    });

                    if (isConfirmed) {
                        document.getElementById('delete-user-form').action = button.dataset.deleteUrl;
                        document.getElementById('delete-user-form').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
