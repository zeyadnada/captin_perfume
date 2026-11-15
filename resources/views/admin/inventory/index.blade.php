@extends('layouts.admin', ['title' => __('admin.inventory.title')])

@section('content')
    <x-page-header :title="__('admin.inventory.title')" :subtitle="__('admin.inventory.index_subtitle')">
        <x-slot:actions>
            <a href="{{ route('admin.stock-movements.create') }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('admin.inventory.record_movement') }}
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
                            <th>{{ __('admin.common.product') }}</th>
                            <th>{{ __('admin.common.variant') }}</th>
                            <th>{{ __('admin.inventory.physical') }}</th>
                            <th>{{ __('admin.inventory.reserved') }}</th>
                            <th>{{ __('admin.inventory.available') }}</th>
                            <th>{{ __('admin.common.status') }}</th>
                            <th class="text-end">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($variants as $variant)
                            @php($stock = $variant->stock)
                            <tr>
                                <td>{{ $variant->product->name }}</td>
                                <td><strong>{{ $variant->display_name }}</strong></td>
                                <td>{{ $stock?->quantity ?? 0 }}</td>
                                <td>{{ $stock?->reserved_quantity ?? 0 }}</td>
                                <td>{{ $stock ? $stock->availableQuantity() : 0 }}</td>
                                <td>
                                    @if (! $stock || ($stock->availableQuantity() <= $stock->low_stock_threshold))
                                        <span class="status--denied">{{ __('admin.inventory.low_stock') }}</span>
                                    @else
                                        <span class="status--process">{{ __('admin.inventory.in_stock') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.stock-movements.create', ['variant_id' => $variant->id]) }}" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.inventory.aria_record_movement', ['product' => $variant->product->name, 'variant' => $variant->display_name]) }}">
                                        <i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('admin.inventory.edit', [$variant->product, $variant]) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="{{ __('admin.inventory.aria_edit_stock', ['product' => $variant->product->name, 'variant' => $variant->display_name]) }}">
                                        <i class="fa-solid fa-boxes-packing" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">{{ __('admin.inventory.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $variants->links() }}</div>
        </div>
    </div>
@endsection