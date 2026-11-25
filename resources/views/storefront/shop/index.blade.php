@extends('layouts.storefront')

@php
    $shopQuery = function (array $overrides = []) use ($filters): array {
        $query = [
            'search' => array_key_exists('search', $overrides) ? $overrides['search'] : $filters['search'],
            'brand' => array_key_exists('brand', $overrides) ? $overrides['brand'] : $filters['brand'],
            'category' => array_key_exists('category', $overrides) ? $overrides['category'] : $filters['category'],
            'sort' => array_key_exists('sort', $overrides) ? $overrides['sort'] : $filters['sort'],
        ];

        return array_filter($query, function ($value, $key) {
            if ($value === null || $value === '') {
                return false;
            }

            return ! ($key === 'sort' && $value === 'featured');
        }, ARRAY_FILTER_USE_BOTH);
    };

    $sortLabels = [
        'featured' => __('storefront.shop.sort.featured'),
        'best_selling' => __('storefront.shop.sort.best_selling'),
        'name_asc' => __('storefront.shop.sort.name_asc'),
        'name_desc' => __('storefront.shop.sort.name_desc'),
        'price_asc' => __('storefront.shop.sort.price_asc'),
        'price_desc' => __('storefront.shop.sort.price_desc'),
        'date_desc' => __('storefront.shop.sort.date_desc'),
        'date_asc' => __('storefront.shop.sort.date_asc'),
    ];
@endphp

@section('content')
    <div>
        @if ($categories->isNotEmpty())
            <div class="d-none d-lg-block navbar navbar-expand-lg py-1 border-top">
                <ul class="list-unstyled navbar-nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ $filters['category'] === '' ? 'text-teal' : '' }}"
                           href="{{ route('shop.index', $shopQuery(['category' => ''])) }}">
                            {{ __('storefront.shop.all_categories') }}
                        </a>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ $filters['category'] === $category->slug ? 'text-teal' : '' }}"
                               href="{{ route('shop.index', $shopQuery(['category' => $category->slug])) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style='background-image: url("{{ asset('kalles/images/shop/shop-banner.jpg') }}"); background-position: center;' class="position-relative">
            <div class="position-absolute top-0 start-0 right-0 bottom-0 bg-dark w-100 opacity-50"></div>
            <div class="px-4">
                <div class="text-white text-center py-5 position-relative">
                    <h4 class="fs-20 fw-medium">{{ __('storefront.shop.title') }}</h4>
                    <p class="fs-14 mb-0">{{ __('storefront.shop.subtitle') }}</p>
                </div>
            </div>
        </div>

        <div class="container" x-data="{ cols: 4 }">
            <div class="mt-5 d-flex justify-content-between align-items-center">
                <a href="#!" class="text-muted fs-16 align-items-center d-none d-lg-flex" id="filter-icon">
                    <i class="iccl fwb iccl-filter fwb me-2 fw-medium" id="icon-filter"></i>
                    <i class="pe-7s-close pegk d-none me-2 fw-medium fw-semibold" id="icon-close" style="font-size: 24px;"></i>
                    <p class="mb-0">{{ __('storefront.shop.filter') }}</p>
                </a>
                <div class="d-flex align-items-center d-lg-none fs-16 text-muted" data-bs-toggle="offcanvas" href="#filterOffcanvas" role="button">
                    <i class="iccl fwb iccl-filter fwb me-2 fw-medium"></i>
                    <p class="mb-0">{{ __('storefront.shop.filter') }}</p>
                </div>

                <ul class="nav tab_header tab_filter gap-2 justify-content-end justify-content-sm-center" role="tablist">
                    <li class="nav-item d-sm-none" role="presentation">
                        <button type="button" class="nav-link" :class="{ 'active': cols === 1 }" @click="cols = 1">
                            <div class="filter-option d-flex">
                                <div class="grid1"></div>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" :class="{ 'active': cols === 2 }" @click="cols = 2">
                            <div class="filter-option d-flex">
                                <div class="grid1"></div>
                                <div class="grid1"></div>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item d-none d-sm-block" role="presentation">
                        <button type="button" class="nav-link" :class="{ 'active': cols === 3 }" @click="cols = 3">
                            <div class="filter-option d-flex">
                                <div class="grid1"></div>
                                <div class="grid1"></div>
                                <div class="grid1"></div>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item d-none d-md-block" role="presentation">
                        <button type="button" class="nav-link" :class="{ 'active': cols === 4 }" @click="cols = 4">
                            <div class="filter-option d-flex">
                                <div class="grid1"></div>
                                <div class="grid1"></div>
                                <div class="grid1"></div>
                                <div class="grid1"></div>
                            </div>
                        </button>
                    </li>
                </ul>

                <div class="dropdown">
                    <button class="btn d-flex align-items-center justify-content-between featurnBtn rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $sortLabels[$filters['sort']] ?? __('storefront.shop.sort.featured') }}
                    </button>
                    <ul class="dropdown-menu filter-dropdown">
                        @foreach ($sortLabels as $sortValue => $sortLabel)
                            <li>
                                <a class="dropdown-item {{ $filters['sort'] === $sortValue ? 'active' : '' }}"
                                   href="{{ route('shop.index', $shopQuery(['sort' => $sortValue])) }}">
                                    {{ $sortLabel }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="p-4 filter-box d-none mt-4">
                <form action="{{ route('shop.index') }}" method="get" class="row g-3 align-items-end">
                    @if ($filters['brand'] !== '')
                        <input type="hidden" name="brand" value="{{ $filters['brand'] }}">
                    @endif
                    @if ($filters['category'] !== '')
                        <input type="hidden" name="category" value="{{ $filters['category'] }}">
                    @endif
                    @if ($filters['sort'] !== 'featured')
                        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                    @endif
                    <div class="col-md-8">
                        <h5 class="mb-1 fw-medium">{{ __('storefront.shop.search') }}</h5>
                        <div class="filter-title"></div>
                        <input type="text"
                               name="search"
                               class="form-control mt-3"
                               value="{{ $filters['search'] }}"
                               placeholder="{{ __('storefront.shop.search_placeholder') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-dark w-100">{{ __('storefront.shop.apply_filters') }}</button>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-lg-3 d-none d-lg-block filter-box border-0" style="box-shadow: none;">
                    <div>
                        <h5 class="mb-2 mt-3">{{ __('storefront.shop.product_categories') }}</h5>
                        <div class="filter-title mb-4"></div>
                        <ul class="list-unstyled mb-4 navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link pt-0 pe-3 d-flex {{ $filters['category'] === '' ? 'text-teal' : '' }}"
                                   href="{{ route('shop.index', $shopQuery(['category' => ''])) }}">
                                    <span><i class="close_pp pegk pe-7s-close ts__03 cd fs-20 fw-bold filter-close" style="transform: rotate(45deg);"></i></span>
                                    {{ __('storefront.shop.all_categories') }}
                                </a>
                            </li>
                            @foreach ($categories as $category)
                                <li class="nav-item">
                                    <a class="nav-link pt-0 pe-3 d-flex {{ $filters['category'] === $category->slug ? 'text-teal' : '' }}"
                                       href="{{ route('shop.index', $shopQuery(['category' => $category->slug])) }}">
                                        <span class="d-inline-flex"><i class="close_pp pegk pe-7s-close ts__03 cd fs-20 fw-bold filter-close" style="transform: rotate(45deg);"></i></span>
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h5 class="mb-2 mt-3">{{ __('storefront.shop.brands') }}</h5>
                        <div class="filter-title mb-4"></div>
                        <ul class="list-unstyled mb-4 navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link pt-0 pe-3 d-flex {{ $filters['brand'] === '' ? 'text-teal' : '' }}"
                                   href="{{ route('shop.index', $shopQuery(['brand' => ''])) }}">
                                    <span><i class="close_pp pegk pe-7s-close ts__03 cd fs-20 fw-bold filter-close" style="transform: rotate(45deg);"></i></span>
                                    {{ __('storefront.shop.all_brands') }}
                                </a>
                            </li>
                            @foreach ($brands as $brand)
                                <li class="nav-item">
                                    <a class="nav-link pt-0 pe-3 d-flex {{ $filters['brand'] === $brand->slug ? 'text-teal' : '' }}"
                                       href="{{ route('shop.index', $shopQuery(['brand' => $brand->slug])) }}">
                                        <span class="d-inline-flex"><i class="close_pp pegk pe-7s-close ts__03 cd fs-20 fw-bold filter-close" style="transform: rotate(45deg);"></i></span>
                                        {{ $brand->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3 my-md-4">
                        <p class="mb-0 text-muted fs-14">
                            {{ __('storefront.shop.showing', [
                                'from' => $products->firstItem() ?? 0,
                                'to' => $products->lastItem() ?? 0,
                                'total' => $products->total(),
                            ]) }}
                        </p>
                        @if ($filters['search'] !== '' || $filters['brand'] !== '' || $filters['category'] !== '')
                            <a href="{{ route('shop.index') }}" class="fs-14 text-muted">{{ __('storefront.shop.clear_filters') }}</a>
                        @endif
                    </div>

                    @if ($products->isEmpty())
                        <div class="text-center py-5">
                            <p class="mb-3">{{ __('storefront.shop.empty') }}</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-teal rounded-pill">{{ __('storefront.shop.clear_filters') }}</a>
                        </div>
                    @else
                        <div class="row g-lg-4 g-3">
                            @foreach ($products as $product)
                                <div class="px-lg-12 px-2"
                                     :class="{
                                         'col-12': cols === 1,
                                         'col-6': cols === 2,
                                         'col-md-4 col-6': cols === 3,
                                         'col-md-3 col-6': cols === 4
                                     }">
                                    @include('storefront.partials.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                            <div class="filter-pagination">
                                <div class="pagination py-4 d-flex justify-content-center mb-0">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
        <div class="offcanvas-header border-bottom bg-black text-white">
            <h5 class="offcanvas-title fs-16 text-uppercase" id="filterOffcanvasLabel">{{ __('storefront.shop.filter') }}</h5>
            <button type="button" class="btn-close btn-close-none text-white" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="pe-7s-close pegk"></i>
            </button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="filter-box p-3" style="box-shadow: none;">
                <h5 class="mb-1 fw-medium">{{ __('storefront.shop.search') }}</h5>
                <div class="filter-title"></div>
                <form action="{{ route('shop.index') }}" method="get" class="mt-3">
                    @if ($filters['brand'] !== '')
                        <input type="hidden" name="brand" value="{{ $filters['brand'] }}">
                    @endif
                    @if ($filters['category'] !== '')
                        <input type="hidden" name="category" value="{{ $filters['category'] }}">
                    @endif
                    @if ($filters['sort'] !== 'featured')
                        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                    @endif
                    <input type="text"
                           name="search"
                           class="form-control mb-3"
                           value="{{ $filters['search'] }}"
                           placeholder="{{ __('storefront.shop.search_placeholder') }}">
                    <button type="submit" class="btn btn-custom-dark fw-medium min-w-150">{{ __('storefront.shop.apply_filters') }}</button>
                </form>
            </div>

            <div class="filter-box p-3 border-0" style="box-shadow: none;">
                <h5 class="mb-1 fw-medium">{{ __('storefront.shop.product_categories') }}</h5>
                <div class="filter-title"></div>
                <div class="mt-3 filter-category">
                    <p class="mb-2">
                        <a href="{{ route('shop.index', $shopQuery(['category' => ''])) }}"
                           class="text-reset {{ $filters['category'] === '' ? 'text-teal' : '' }}">
                            {{ __('storefront.shop.all_categories') }}
                        </a>
                    </p>
                    @foreach ($categories as $category)
                        <p class="mb-2">
                            <a href="{{ route('shop.index', $shopQuery(['category' => $category->slug])) }}"
                               class="text-reset {{ $filters['category'] === $category->slug ? 'text-teal' : '' }}">
                                {{ $category->name }}
                            </a>
                        </p>
                    @endforeach
                </div>
            </div>

            <div class="filter-box p-3" style="box-shadow: none;">
                <h5 class="mb-1 fw-medium">{{ __('storefront.shop.brands') }}</h5>
                <div class="filter-title"></div>
                <ul class="list-unstyled mt-3 mb-0 navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link pt-0 pe-3 d-flex {{ $filters['brand'] === '' ? 'text-teal' : '' }}"
                           href="{{ route('shop.index', $shopQuery(['brand' => ''])) }}">
                            <span><i class="close_pp pegk pe-7s-close ts__03 cd fs-20 fw-bold filter-close" style="transform: rotate(45deg);"></i></span>
                            {{ __('storefront.shop.all_brands') }}
                        </a>
                    </li>
                    @foreach ($brands as $brand)
                        <li class="nav-item">
                            <a class="nav-link pt-0 pe-3 d-flex {{ $filters['brand'] === $brand->slug ? 'text-teal' : '' }}"
                               href="{{ route('shop.index', $shopQuery(['brand' => $brand->slug])) }}">
                                <span class="d-inline-flex"><i class="close_pp pegk pe-7s-close ts__03 cd fs-20 fw-bold filter-close" style="transform: rotate(45deg);"></i></span>
                                {{ $brand->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="filter-box p-3 border-0" style="box-shadow: none;">
                <h5 class="mb-1 fw-medium">{{ __('storefront.shop.sort_by') }}</h5>
                <div class="filter-title"></div>
                <div class="mt-3">
                    @foreach ($sortLabels as $sortValue => $sortLabel)
                        <p class="mb-2 {{ $filters['sort'] === $sortValue ? 'text-teal' : '' }}">
                            <a href="{{ route('shop.index', $shopQuery(['sort' => $sortValue])) }}"
                               class="text-reset {{ $filters['sort'] === $sortValue ? 'text-teal' : '' }}">
                                {{ $sortLabel }}
                            </a>
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Neutralize Bootstrap blue on the selected sort option. */
        .filter-dropdown .dropdown-item.active,
        .filter-dropdown .dropdown-item:active,
        .filter-dropdown .dropdown-item:focus,
        .filter-dropdown .dropdown-item.active:focus,
        .filter-dropdown .dropdown-item.active:hover {
            background-color: var(--perfume-accent-soft);
            color: var(--perfume-accent) !important;
            outline: none;
            box-shadow: none;
        }
    </style>
@endpush
