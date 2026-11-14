@extends('layouts.admin', ['title' => __('admin.variants.title', ['product' => $product->name])])

@section('content')
    <x-page-header :title="__('admin.variants.title', ['product' => $product->name])" :subtitle="__('admin.variants.index_subtitle')">
        <x-slot:actions>
            <a href="{{ route('admin.products.variants.create', $product) }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('admin.variants.add') }}
            </a>
            <a href="{{ route('admin.products.show', $product) }}" class="au-btn btn btn-secondary au-btn--small">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> {{ __('admin.common.back_to_product') }}
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
            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin.common.variant') }}</th>
                            <th>{{ __('admin.common.sku') }}</th>
                            <th>{{ __('admin.common.price') }}</th>
                            <th>{{ __('admin.common.compare_at_price') }}</th>
                            <th>{{ __('admin.common.status') }}</th>
                            <th class="text-end">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($variants as $variant)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td><strong>{{ $variant->display_name }}</strong></td>
                                <td>{{ $variant->sku ?: __('admin.common.not_assigned') }}</td>
                                <td>{{ number_format((float) $variant->price, 2) }}</td>
                                <td>{{ is_null($variant->compare_at_price) ? __('admin.common.not_set') : number_format((float) $variant->compare_at_price, 2) }}</td>
                                <td>
                                    <span class="{{ $variant->is_active ? 'status--process' : 'status--denied' }}">
                                        {{ $variant->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="table-data-feature">
                                        <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.common.edit_named', ['name' => $variant->display_name]) }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-variant-trigger d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" type="button" data-variant-name="{{ $variant->display_name }}" data-confirm-text="{{ __('admin.common.confirm_delete_named', ['name' => $variant->display_name]) }}" data-delete-url="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" aria-label="{{ __('admin.common.delete_named', ['name' => $variant->display_name]) }}">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">{{ __('admin.variants.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $variants->links() }}</div>
        </div>
    </div>

    <form id="delete-variant-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-variant-trigger').forEach(button => {
                button.addEventListener('click', async () => {
                    const { isConfirmed } = await Swal.fire({
                        title: @json(__('admin.variants.confirm_delete_title')),
                        text: button.dataset.confirmText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: @json(__('admin.common.confirm_delete_button')),
                        confirmButtonColor: '#dc3545'
                    });

                    if (isConfirmed) {
                        document.getElementById('delete-variant-form').action = button.dataset.deleteUrl;
                        document.getElementById('delete-variant-form').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection