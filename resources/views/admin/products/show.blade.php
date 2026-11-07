@extends('layouts.admin', ['title' => $product->name])

@section('content')
    <x-page-header :title="$product->name" subtitle="View complete product information.">
        <x-slot:actions>
            <a href="{{ route('admin.products.edit', $product) }}" class="au-btn btn btn-warning au-btn--small">
                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit product
            </a>
            <a href="{{ route('admin.products.index') }}" class="au-btn btn btn-secondary au-btn--small">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to products
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <div class="au-card m-b-30">
                <div class="au-card-inner">
                    <h3 class="title-2 m-b-30">Product details</h3>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Name</dt>
                        <dd class="col-sm-8">{{ $product->name }}</dd>

                        <dt class="col-sm-4">Slug</dt>
                        <dd class="col-sm-8"><code>{{ $product->slug }}</code></dd>

                        <dt class="col-sm-4">SKU</dt>
                        <dd class="col-sm-8">{{ $product->sku ?: 'Not assigned' }}</dd>

                        <dt class="col-sm-4">Brand</dt>
                        <dd class="col-sm-8">{{ $product->brand->name }}</dd>

                        <dt class="col-sm-4">Category</dt>
                        <dd class="col-sm-8">{{ $product->category->name }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="{{ $product->is_active ? 'status--process' : 'status--denied' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="au-card m-b-30">
                <div class="au-card-inner">
                    <h3 class="title-2 m-b-30">Descriptions</h3>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Short description</dt>
                        <dd class="col-sm-8">{{ $product->short_description ?: 'Not provided' }}</dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8 text-break">{{ $product->description ?: 'Not provided' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="au-card m-b-30">
                <div class="au-card-inner">
                    <h3 class="title-2 m-b-30">Pricing</h3>
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Price</dt>
                        <dd class="col-sm-6">{{ number_format((float) $product->price, 2) }}</dd>

                        <dt class="col-sm-6">Compare-at price</dt>
                        <dd class="col-sm-6">{{ is_null($product->compare_at_price) ? 'Not set' : number_format((float) $product->compare_at_price, 2) }}</dd>
                    </dl>
                </div>
            </div>

            <div class="au-card m-b-30">
                <div class="au-card-inner">
                    <h3 class="title-2 m-b-30">Record</h3>
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Created</dt>
                        <dd class="col-sm-6">{{ $product->created_at?->format('M j, Y g:i A') }}</dd>

                        <dt class="col-sm-6">Last updated</dt>
                        <dd class="col-sm-6">{{ $product->updated_at?->format('M j, Y g:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 m-b-20">
                <h3 class="title-2 mb-0">Variants</h3>
                <a href="{{ route('admin.products.variants.index', $product) }}" class="au-btn au-btn--green au-btn--small">
                    <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                    Manage variants
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>Variant</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Compare-at price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product->variants->take(5) as $variant)
                            <tr>
                                <td><strong>{{ $variant->display_name }}</strong></td>
                                <td>{{ $variant->sku ?: 'Not assigned' }}</td>
                                <td>{{ number_format((float) $variant->price, 2) }}</td>
                                <td>{{ is_null($variant->compare_at_price) ? 'Not set' : number_format((float) $variant->compare_at_price, 2) }}</td>
                                <td>
                                    <span class="{{ $variant->is_active ? 'status--process' : 'status--denied' }}">
                                        {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    No variants have been added.
                                    <a href="{{ route('admin.products.variants.create', $product) }}">Add the first variant</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($product->variants_count > 5)
                <div class="text-end mt-3">
                    <a href="{{ route('admin.products.variants.index', $product) }}">View all {{ $product->variants_count }} variants</a>
                </div>
            @endif
        </div>
    </div>
@endsection
