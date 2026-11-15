@extends('layouts.admin', ['title' => __('admin.categories.title')])

@section('content')
    <x-page-header :title="__('admin.categories.title')" :subtitle="__('admin.categories.index_subtitle')">
        <x-slot:actions>
            <a href="{{ route('admin.categories.create') }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('admin.categories.add') }}
            </a>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('admin.common.close') }}"></button>
        </div>
    @endif

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-3 align-items-end mb-4 p-3 rounded bg-light">
                <div class="col-md-6">
                    <label class="form-label" for="search">{{ __('admin.common.search') }}</label>
                    <input class="au-input" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.common.search_name_or_slug') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="status">{{ __('admin.common.status') }}</label>
                    <select class="au-input" id="status" name="status">
                        <option value="">{{ __('admin.common.all_statuses') }}</option>
                        <option value="1" @selected(request('status') === '1')>{{ __('admin.common.active') }}</option>
                        <option value="0" @selected(request('status') === '0')>{{ __('admin.common.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 px-3" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i>
                        {{ __('admin.common.filter') }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" aria-label="{{ __('admin.common.reset_filters') }}">
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
                            <th>{{ __('admin.common.slug') }}</th>
                            <th>{{ __('admin.common.parent') }}</th>
                            <th>{{ __('admin.common.status') }}</th>
                            <th class="text-end">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ $category->parent?->name ?? __('admin.common.top_level') }}</td>
                                <td>
                                    <span class="{{ $category->is_active ? 'status--process' : 'status--denied' }}">
                                        {{ $category->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="table-data-feature">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.common.edit_named', ['name' => $category->name]) }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-category-trigger d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" type="button" data-category-name="{{ $category->name }}" data-confirm-text="{{ __('admin.common.confirm_delete_named', ['name' => $category->name]) }}" data-delete-url="{{ route('admin.categories.destroy', $category) }}" aria-label="{{ __('admin.common.delete_named', ['name' => $category->name]) }}">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">{{ __('admin.categories.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <form id="delete-category-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-category-trigger').forEach(button => {
                button.addEventListener('click', async () => {
                    const { isConfirmed } = await Swal.fire({
                        title: @json(__('admin.categories.confirm_delete_title')),
                        text: button.dataset.confirmText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: @json(__('admin.common.confirm_delete_button')),
                        confirmButtonColor: '#dc3545'
                    });

                    if (isConfirmed) {
                        document.getElementById('delete-category-form').action = button.dataset.deleteUrl;
                        document.getElementById('delete-category-form').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection