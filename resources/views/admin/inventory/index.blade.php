@extends('layouts.admin', ['title' => 'Inventory'])

@section('content')
    <x-page-header title="Inventory" subtitle="Monitor stock levels for each product variant.">
        <x-slot:actions>
            <a href="{{ route('admin.stock-movements.create') }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Record movement
            </a>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Physical</th>
                            <th>Reserved</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
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
                                        <span class="status--denied">Low stock</span>
                                    @else
                                        <span class="status--process">In stock</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.stock-movements.create', ['variant_id' => $variant->id]) }}" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="Record stock movement for {{ $variant->product->name }} {{ $variant->display_name }}">
                                        <i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('admin.inventory.edit', [$variant->product, $variant]) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="Edit stock for {{ $variant->product->name }} {{ $variant->display_name }}">
                                        <i class="fa-solid fa-boxes-packing" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No active product variants found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $variants->links() }}</div>
        </div>
    </div>
@endsection
