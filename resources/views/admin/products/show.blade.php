@extends('layouts.admin', ['title' => $product->name])

@section('content')
    <x-page-header :title="$product->name" :subtitle="__('admin.products.show_subtitle')">
        <x-slot:actions>
            <a href="{{ route('admin.products.edit', $product) }}" class="au-btn btn btn-warning au-btn--small">
                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> {{ __('admin.products.edit') }}
            </a>
            <a href="{{ route('admin.products.index') }}" class="au-btn btn btn-secondary au-btn--small">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> {{ __('admin.common.back_to_products') }}
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <div class="au-card m-b-30">
                <div class="au-card-inner">
                    <h3 class="title-2 m-b-30">{{ __('admin.products.details') }}</h3>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">{{ __('admin.common.name') }}</dt>
                        <dd class="col-sm-8">{{ $product->name }}</dd>

                        <dt class="col-sm-4">{{ __('admin.common.slug') }}</dt>
                        <dd class="col-sm-8"><code>{{ $product->slug }}</code></dd>

                        <dt class="col-sm-4">{{ __('admin.common.sku') }}</dt>
                        <dd class="col-sm-8">{{ $product->sku ?: __('admin.common.not_assigned') }}</dd>

                        <dt class="col-sm-4">{{ __('admin.common.brand') }}</dt>
                        <dd class="col-sm-8">{{ $product->brand->name }}</dd>

                        <dt class="col-sm-4">{{ __('admin.common.category') }}</dt>
                        <dd class="col-sm-8">{{ $product->category->name }}</dd>

                        <dt class="col-sm-4">{{ __('admin.common.status') }}</dt>
                        <dd class="col-sm-8">
                            <span class="{{ $product->is_active ? 'status--process' : 'status--denied' }}">
                                {{ $product->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <div class="au-card m-b-30">
                <div class="au-card-inner">
                    <h3 class="title-2 m-b-30">{{ __('admin.common.record') }}</h3>
                    <dl class="row mb-0">
                        <dt class="col-sm-6">{{ __('admin.common.created') }}</dt>
                        <dd class="col-sm-6">{{ $product->created_at?->format('M j, Y g:i A') }}</dd>

                        <dt class="col-sm-6">{{ __('admin.common.last_updated') }}</dt>
                        <dd class="col-sm-6">{{ $product->updated_at?->format('M j, Y g:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <h3 class="title-2 m-b-30">{{ __('admin.products.descriptions') }}</h3>
            <dl class="row mb-0">
                <dt class="col-sm-2">{{ __('admin.products.short_description') }}</dt>
                <dd class="col-sm-10">{{ $product->short_description ?: __('admin.common.not_provided') }}</dd>

                <dt class="col-sm-2">{{ __('admin.common.description') }}</dt>
                <dd class="col-sm-10 text-break">{{ $product->description ?: __('admin.common.not_provided') }}</dd>
            </dl>
        </div>
    </div>

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 m-b-20">
                <h3 class="title-2 mb-0">{{ __('admin.images.title', ['product' => $product->name]) }}</h3>
                <a href="{{ route('admin.products.images.index', $product) }}" class="au-btn au-btn--green au-btn--small">
                    <i class="fa-solid fa-images" aria-hidden="true"></i>
                    {{ __('admin.products.manage_images') }}
                </a>
            </div>

            @if ($product->images->isEmpty())
                <p class="text-muted mb-0">{{ __('admin.products.no_images') }}</p>
            @else
                <div class="row">
                    @foreach ($product->images as $image)
                        <div class="col-6 col-md-3 col-lg-2 mb-3">
                            <div class="position-relative border rounded bg-light" style="height: 150px;">
                                <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}" class="w-100 h-100 rounded" style="object-fit: contain;">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 m-b-20">
                <h3 class="title-2 mb-0">{{ __('admin.common.variants') }}</h3>
                <a href="{{ route('admin.products.variants.index', $product) }}" class="au-btn au-btn--green au-btn--small">
                    <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                    {{ __('admin.products.manage_variants') }}
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>{{ __('admin.common.variant') }}</th>
                            <th>{{ __('admin.common.sku') }}</th>
                            <th>{{ __('admin.common.price') }}</th>
                            <th>{{ __('admin.common.compare_at_price') }}</th>
                            <th>{{ __('admin.common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product->variants->take(5) as $variant)
                            <tr>
                                <td><strong>{{ $variant->display_name }}</strong></td>
                                <td>{{ $variant->sku ?: __('admin.common.not_assigned') }}</td>
                                <td>{{ number_format((float) $variant->price, 2) }}</td>
                                <td>{{ is_null($variant->compare_at_price) ? __('admin.common.not_set') : number_format((float) $variant->compare_at_price, 2) }}</td>
                                <td>
                                    <span class="{{ $variant->is_active ? 'status--process' : 'status--denied' }}">
                                        {{ $variant->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    {{ __('admin.products.no_variants') }}
                                    <a href="{{ route('admin.products.variants.create', $product) }}">{{ __('admin.products.add_first_variant') }}</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($product->variants_count > 5)
                <div class="text-end mt-3">
                    <a href="{{ route('admin.products.variants.index', $product) }}">{{ __('admin.products.view_all_variants', ['count' => $product->variants_count]) }}</a>
                </div>
            @endif
        </div>
    </div>
@endsection