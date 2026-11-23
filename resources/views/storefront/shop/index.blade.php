@extends('layouts.storefront')

@section('content')
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>{{ __('storefront.shop.title') }}</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a>
                            <span>{{ __('storefront.shop.breadcrumb') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form action="{{ route('shop.index') }}" method="get">
                                @if ($filters['brand'] !== '')
                                    <input type="hidden" name="brand" value="{{ $filters['brand'] }}">
                                @endif
                                @if ($filters['category'] !== '')
                                    <input type="hidden" name="category" value="{{ $filters['category'] }}">
                                @endif
                                @if ($filters['sort'] !== 'latest')
                                    <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                                @endif
                                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="{{ __('storefront.shop.search_placeholder') }}">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="shopSidebarAccordion">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseCategories">{{ __('storefront.shop.categories') }}</a>
                                    </div>
                                    <div id="collapseCategories" class="collapse show" data-parent="#shopSidebarAccordion">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                                <ul class="nice-scroll">
                                                    <li>
                                                        <a href="{{ route('shop.index', array_filter(['search' => $filters['search'] ?: null, 'brand' => $filters['brand'] ?: null, 'sort' => $filters['sort'] !== 'latest' ? $filters['sort'] : null])) }}"
                                                           class="{{ $filters['category'] === '' ? 'font-weight-bold' : '' }}">
                                                            {{ __('storefront.shop.all_categories') }}
                                                        </a>
                                                    </li>
                                                    @foreach ($categories as $category)
                                                        <li>
                                                            <a href="{{ route('shop.index', array_filter(['search' => $filters['search'] ?: null, 'brand' => $filters['brand'] ?: null, 'category' => $category->slug, 'sort' => $filters['sort'] !== 'latest' ? $filters['sort'] : null])) }}"
                                                               class="{{ $filters['category'] === $category->slug ? 'font-weight-bold' : '' }}">
                                                                {{ $category->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseBrands">{{ __('storefront.shop.brands') }}</a>
                                    </div>
                                    <div id="collapseBrands" class="collapse show" data-parent="#shopSidebarAccordion">
                                        <div class="card-body">
                                            <div class="shop__sidebar__brand">
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('shop.index', array_filter(['search' => $filters['search'] ?: null, 'category' => $filters['category'] ?: null, 'sort' => $filters['sort'] !== 'latest' ? $filters['sort'] : null])) }}"
                                                           class="{{ $filters['brand'] === '' ? 'font-weight-bold' : '' }}">
                                                            {{ __('storefront.shop.all_brands') }}
                                                        </a>
                                                    </li>
                                                    @foreach ($brands as $brand)
                                                        <li>
                                                            <a href="{{ route('shop.index', array_filter(['search' => $filters['search'] ?: null, 'brand' => $brand->slug, 'category' => $filters['category'] ?: null, 'sort' => $filters['sort'] !== 'latest' ? $filters['sort'] : null])) }}"
                                                               class="{{ $filters['brand'] === $brand->slug ? 'font-weight-bold' : '' }}">
                                                                {{ $brand->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>
                                        {{ __('storefront.shop.showing', [
                                            'from' => $products->firstItem() ?? 0,
                                            'to' => $products->lastItem() ?? 0,
                                            'total' => $products->total(),
                                        ]) }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>{{ __('storefront.shop.sort_by') }}</p>
                                    <form action="{{ route('shop.index') }}" method="get" id="shop-sort-form">
                                        @if ($filters['search'] !== '')
                                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                                        @endif
                                        @if ($filters['brand'] !== '')
                                            <input type="hidden" name="brand" value="{{ $filters['brand'] }}">
                                        @endif
                                        @if ($filters['category'] !== '')
                                            <input type="hidden" name="category" value="{{ $filters['category'] }}">
                                        @endif
                                        <select name="sort" onchange="this.form.submit()">
                                            <option value="latest" @selected($filters['sort'] === 'latest')>{{ __('storefront.shop.sort.latest') }}</option>
                                            <option value="price_asc" @selected($filters['sort'] === 'price_asc')>{{ __('storefront.shop.sort.price_asc') }}</option>
                                            <option value="price_desc" @selected($filters['sort'] === 'price_desc')>{{ __('storefront.shop.sort.price_desc') }}</option>
                                            <option value="name" @selected($filters['sort'] === 'name')>{{ __('storefront.shop.sort.name') }}</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($products->isEmpty())
                        <div class="text-center py-5">
                            <p class="mb-3">{{ __('storefront.shop.empty') }}</p>
                            <a href="{{ route('shop.index') }}" class="primary-btn">{{ __('storefront.shop.clear_filters') }}</a>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    @include('storefront.partials.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product__pagination mt-4">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
