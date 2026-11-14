@extends('layouts.admin', ['title' => __('admin.products.title')])

@section('content')
    <x-page-header :title="__('admin.products.title')" :subtitle="__('admin.products.index_subtitle')">
        <x-slot:actions>
            <a href="{{ route('admin.products.create') }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('admin.products.add') }}
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
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 align-items-end mb-4 p-3 rounded bg-light">
                <div class="col-md-3">
                    <label class="form-label" for="search">{{ __('admin.common.search') }}</label>
                    <input class="au-input" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.common.search_name_slug_sku') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="brand_id">{{ __('admin.common.brand') }}</label>
                    <select class="au-input" id="brand_id" name="brand_id">
                        <option value="">{{ __('admin.products.all_brands') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected((string) request('brand_id') === (string) $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="category_id">{{ __('admin.common.category') }}</label>
                    <select class="au-input" id="category_id" name="category_id">
                        <option value="">{{ __('admin.products.all_categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="status">{{ __('admin.common.status') }}</label>
                    <select class="au-input" id="status" name="status">
                        <option value="">{{ __('admin.common.all_statuses') }}</option>
                        <option value="1" @selected(request('status') === '1')>{{ __('admin.common.active') }}</option>
                        <option value="0" @selected(request('status') === '0')>{{ __('admin.common.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 px-3" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i> {{ __('admin.common.filter') }}
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm" aria-label="{{ __('admin.common.reset_filters') }}">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin.common.product') }}</th>
                            <th>{{ __('admin.common.brand') }}</th>
                            <th>{{ __('admin.common.category') }}</th>
                            <th>{{ __('admin.common.variants') }}</th>
                            <th>{{ __('admin.common.status') }}</th>
                            <th class="text-end">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if ($product->sku)<div><code>{{ $product->sku }}</code></div>@endif
                                </td>
                                <td>{{ $product->brand->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    <a href="{{ route('admin.products.variants.index', $product) }}" class="badge bg-secondary text-decoration-none">
                                        {{ trans_choice('admin.products.variant_count', $product->variants_count, ['count' => $product->variants_count]) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="{{ $product->is_active ? 'status--process' : 'status--denied' }}">
                                        {{ $product->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="table-data-feature">
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.common.view_named', ['name' => $product->name]) }}">
                                            <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.common.edit_named', ['name' => $product->name]) }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-product-trigger d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" type="button" data-product-name="{{ $product->name }}" data-confirm-text="{{ __('admin.common.confirm_delete_named', ['name' => $product->name]) }}" data-delete-url="{{ route('admin.products.destroy', $product) }}" aria-label="{{ __('admin.common.delete_named', ['name' => $product->name]) }}">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">{{ __('admin.products.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $products->links() }}</div>
        </div>
    </div>

    <form id="delete-product-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-product-trigger').forEach(button => {
                button.addEventListener('click', async () => {
                    const { isConfirmed } = await Swal.fire({
                        title: @json(__('admin.products.confirm_delete_title')),
                        text: button.dataset.confirmText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: @json(__('admin.common.confirm_delete_button')),
                        confirmButtonColor: '#dc3545'
                    });

                    if (isConfirmed) {
                        document.getElementById('delete-product-form').action = button.dataset.deleteUrl;
                        document.getElementById('delete-product-form').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection