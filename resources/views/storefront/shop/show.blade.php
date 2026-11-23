@extends('layouts.storefront')

@php
    $images = $product->images;
    $variants = $product->variants;
    $selectedVariant = $variants->first();
    $placeholderImage = asset('malefashion/img/product/product-1.jpg');
@endphp

@section('content')
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a>
                            <a href="{{ route('shop.index') }}">{{ __('storefront.shop.breadcrumb') }}</a>
                            <span>{{ $product->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if ($images->isNotEmpty())
                        <div class="col-lg-3 col-md-3">
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach ($images as $index => $image)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index === 0 ? 'active' : '' }}" data-toggle="tab" href="#product-image-{{ $image->id }}" role="tab">
                                            <div class="product__thumb__pic set-bg" data-setbg="{{ asset('storage/'.$image->path) }}"></div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-9">
                            <div class="tab-content">
                                @foreach ($images as $index => $image)
                                    <div class="tab-pane {{ $index === 0 ? 'active' : '' }}" id="product-image-{{ $image->id }}" role="tabpanel">
                                        <div class="product__details__pic__item">
                                            <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="col-lg-9 mx-auto">
                            <div class="product__details__pic__item text-center">
                                <img src="{{ $placeholderImage }}" alt="{{ $product->name }}">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <h4>{{ $product->name }}</h4>
                            @if ($product->brand)
                                <p class="mb-2 text-muted">{{ $product->brand->name }}</p>
                            @endif

                            @if ($selectedVariant)
                                <h3 id="product-price">
                                    ${{ number_format((float) $selectedVariant->price, 2) }}
                                    @if (filled($selectedVariant->compare_at_price))
                                        <span id="product-compare-price">${{ number_format((float) $selectedVariant->compare_at_price, 2) }}</span>
                                    @else
                                        <span id="product-compare-price" class="d-none"></span>
                                    @endif
                                </h3>
                            @endif

                            @if ($product->short_description)
                                <p>{{ $product->short_description }}</p>
                            @endif

                            @if ($variants->isNotEmpty())
                                <div class="product__details__option">
                                    <div class="product__details__option__size">
                                        <span>{{ __('storefront.product.volume') }}:</span>
                                        @foreach ($variants as $index => $variant)
                                            @php
                                                $variantInStock = ($variant->stock?->availableQuantity() ?? 0) > 0;
                                            @endphp
                                            <label class="{{ $index === 0 ? 'active' : '' }} {{ $variantInStock ? '' : 'text-muted' }}" for="variant-{{ $variant->id }}">
                                                {{ $variant->display_name }}
                                                <input
                                                    type="radio"
                                                    name="variant_id"
                                                    id="variant-{{ $variant->id }}"
                                                    value="{{ $variant->id }}"
                                                    data-price="{{ number_format((float) $variant->price, 2, '.', '') }}"
                                                    data-compare-at="{{ filled($variant->compare_at_price) ? number_format((float) $variant->compare_at_price, 2, '.', '') : '' }}"
                                                    data-in-stock="{{ $variantInStock ? '1' : '0' }}"
                                                    @checked($index === 0)
                                                >
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="product__details__cart__option">
                                <button type="button" class="primary-btn" disabled>
                                    {{ __('storefront.product.add_to_cart_coming_soon') }}
                                </button>
                            </div>

                            <p id="product-stock-status" class="mt-2">
                                @if ($selectedVariant && ($selectedVariant->stock?->availableQuantity() ?? 0) > 0)
                                    {{ __('storefront.product.in_stock') }}
                                @else
                                    {{ __('storefront.product.out_of_stock') }}
                                @endif
                            </p>

                            <div class="product__details__last__option">
                                <ul>
                                    @if ($product->sku)
                                        <li><span>{{ __('storefront.product.sku') }}:</span> {{ $product->sku }}</li>
                                    @endif
                                    @if ($product->category)
                                        <li><span>{{ __('storefront.product.category') }}:</span> {{ $product->category->name }}</li>
                                    @endif
                                    @if ($product->brand)
                                        <li><span>{{ __('storefront.product.brand') }}:</span> {{ $product->brand->name }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($product->description)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product__details__tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#product-description" role="tab">
                                            {{ __('storefront.product.description') }}
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="product-description" role="tabpanel">
                                        <div class="product__details__tab__content">
                                            <div class="product__details__tab__content__item">
                                                {!! nl2br(e($product->description)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="related spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="related-title">{{ __('storefront.product.related') }}</h3>
                    </div>
                </div>
                <div class="row">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            @include('storefront.partials.product-card', ['product' => $relatedProduct])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        (function () {
            var priceEl = document.getElementById('product-price');
            var compareEl = document.getElementById('product-compare-price');
            var stockEl = document.getElementById('product-stock-status');
            if (!priceEl) {
                return;
            }

            var inStockText = @json(__('storefront.product.in_stock'));
            var outOfStockText = @json(__('storefront.product.out_of_stock'));

            document.querySelectorAll('input[name="variant_id"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    var label = input.closest('label');
                    document.querySelectorAll('.product__details__option__size label').forEach(function (item) {
                        item.classList.remove('active');
                    });
                    if (label) {
                        label.classList.add('active');
                    }

                    var price = input.getAttribute('data-price');
                    var compareAt = input.getAttribute('data-compare-at');
                    var inStock = input.getAttribute('data-in-stock') === '1';

                    priceEl.childNodes[0].textContent = '$' + price + ' ';
                    if (compareAt) {
                        compareEl.textContent = '$' + compareAt;
                        compareEl.classList.remove('d-none');
                    } else {
                        compareEl.textContent = '';
                        compareEl.classList.add('d-none');
                    }

                    stockEl.textContent = inStock ? inStockText : outOfStockText;
                });
            });
        })();
    </script>
@endpush
