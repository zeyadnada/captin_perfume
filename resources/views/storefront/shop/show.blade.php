@extends('layouts.storefront')

@php
    $images = $product->images;
    $variants = $product->variants;
    $selectedVariant = $variants->first();
    $placeholderImage = asset('kalles/images/products/pr-01.jpg');
    $initialStock = (int) ($selectedVariant?->stock?->availableQuantity() ?? 0);
    $hasSale = $selectedVariant
        && filled($selectedVariant->compare_at_price)
        && (float) $selectedVariant->compare_at_price > (float) $selectedVariant->price;
    $volumeOptions = $variants->map(fn ($variant) => $variant->display_name)->implode(', ');
    $reviews = $reviews ?? collect();
    $averageRating = $reviews->isNotEmpty() ? round((float) $reviews->avg('rating'), 1) : 0;
@endphp

@section('content')
    <section class="main-project-section">
        <div class="container">
            <nav aria-label="breadcrumb" class="py-3">
                <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '>';">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">{{ __('storefront.shop.breadcrumb') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            <div class="row py-3 gx-xl-4">
                <div class="col-md-6 product-gallery">
                    @if ($images->isNotEmpty())
                        <div class="row g-2">
                            <div class="col-xl-2 order-2 order-xl-1">
                                <div class="swiper productSmall">
                                    <div class="swiper-wrapper">
                                        @foreach ($images as $image)
                                            <div class="swiper-slide">
                                                <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}" class="object-fit-contain">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                            <div class="col-xl-10 order-1 order-xl-2">
                                <div class="swiper productMain">
                                    <div class="swiper-wrapper product-container">
                                        @foreach ($images as $image)
                                            <div class="swiper-slide product">
                                                <div class="thumbnail-container">
                                                    <img class="img-fluid w-100" src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <img src="{{ $placeholderImage }}" alt="{{ $product->name }}" class="img-fluid w-100">
                    @endif
                </div>

                <div
                    class="col-md-6 mt-5 mt-md-0 product-details"
                    @if ($variants->isNotEmpty())
                        x-data="{
                            volume: @js($selectedVariant->display_name),
                            price: @js(number_format((float) $selectedVariant->price, 2, '.', '')),
                            compareAt: @js($hasSale ? number_format((float) $selectedVariant->compare_at_price, 2, '.', '') : ''),
                            inStock: {{ $initialStock > 0 ? 'true' : 'false' }},
                            stockQty: {{ max($initialStock, 0) }},
                            selectVariant(volume, price, compareAt, inStock, stockQty) {
                                this.volume = volume;
                                this.price = price;
                                this.compareAt = compareAt;
                                this.inStock = inStock;
                                this.stockQty = stockQty;

                                const priceEl = document.getElementById('product-price');
                                const compareEl = document.getElementById('product-compare-price');
                                const qtyEl = document.getElementById('product-quantity');
                                const stockEl = document.getElementById('product-stock-status');

                                priceEl.textContent = '$' + price;
                                if (compareAt && parseFloat(compareAt) > parseFloat(price)) {
                                    compareEl.textContent = '$' + compareAt;
                                    compareEl.classList.remove('d-none');
                                    priceEl.classList.remove('text-muted');
                                    priceEl.classList.add('text-danger');
                                } else {
                                    compareEl.textContent = '';
                                    compareEl.classList.add('d-none');
                                    priceEl.classList.remove('text-danger');
                                    priceEl.classList.add('text-muted');
                                }

                                const maxQty = Math.max(stockQty, 1);
                                qtyEl.max = String(maxQty);
                                if (parseInt(qtyEl.value, 10) > maxQty) {
                                    qtyEl.value = String(maxQty);
                                }
                                if (stockQty < 1) {
                                    qtyEl.value = '1';
                                }

                                stockEl.textContent = inStock
                                    ? @js(__('storefront.product.in_stock'))
                                    : @js(__('storefront.product.out_of_stock'));
                            }
                        }"
                    @endif
                >
                    <h4 class="mb-3">{{ $product->name }}</h4>

                    @if ($selectedVariant)
                        <p class="mb-4" id="product-price-wrap">
                            <del id="product-compare-price" class="text-muted me-2 {{ $hasSale ? '' : 'd-none' }}">
                                @if ($hasSale)
                                    ${{ number_format((float) $selectedVariant->compare_at_price, 2) }}
                                @endif
                            </del>
                            <span id="product-price" class="fs-18 {{ $hasSale ? 'text-danger' : 'text-muted' }}">
                                ${{ number_format((float) $selectedVariant->price, 2) }}
                            </span>
                        </p>
                    @endif

                    @if ($product->short_description)
                        <p class="text-muted mb-3 text-break product-copy">{{ $product->short_description }}</p>
                    @endif

                    @if ($variants->isNotEmpty())
                        <div class="pt-2 mb-4 pb-3">
                            <h6 class="text-uppercase fw-bold mt-3">
                                {{ __('storefront.product.volume') }}: <span x-text="volume"></span>
                            </h6>
                            <div class="product-color-list size mt-2 gap-2 d-flex align-items-center flex-wrap">
                                @foreach ($variants as $variant)
                                    @php
                                        $variantStock = (int) ($variant->stock?->availableQuantity() ?? 0);
                                        $variantHasSale = filled($variant->compare_at_price)
                                            && (float) $variant->compare_at_price > (float) $variant->price;
                                    @endphp
                                    <a
                                        href="#!"
                                        class="d-inline-block rounded-circle square-xs d-flex align-items-center justify-content-center"
                                        :class="{ 'active': volume === @js($variant->display_name) }"
                                        x-on:click.prevent="selectVariant(
                                            @js($variant->display_name),
                                            @js(number_format((float) $variant->price, 2, '.', '')),
                                            @js($variantHasSale ? number_format((float) $variant->compare_at_price, 2, '.', '') : ''),
                                            {{ $variantStock > 0 ? 'true' : 'false' }},
                                            {{ max($variantStock, 0) }}
                                        )"
                                    >
                                        {{ $variant->display_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap align-items-center gap-2 mt-4">
                        <div class="input-step border border-dark rounded-pill">
                            <button type="button" class="minus material-shadow text-dark fw-bold" aria-label="{{ __('storefront.product.decrease_quantity') }}">–</button>
                            <input
                                type="number"
                                id="product-quantity"
                                class="product-quantity fw-bold fs-6"
                                value="1"
                                min="1"
                                max="{{ max($initialStock, 1) }}"
                                aria-label="{{ __('storefront.product.quantity') }}"
                            >
                            <button type="button" class="plus material-shadow text-dark fw-bold" aria-label="{{ __('storefront.product.increase_quantity') }}">+</button>
                        </div>
                        <button type="button" class="btn btn-teal text-uppercase rounded-pill min-w-150" disabled>
                            {{ __('storefront.product.add_to_cart_coming_soon') }}
                        </button>
                    </div>

                    <p id="product-stock-status" class="mt-3 mb-0">
                        @if ($initialStock > 0)
                            {{ __('storefront.product.in_stock') }}
                        @else
                            {{ __('storefront.product.out_of_stock') }}
                        @endif
                    </p>

                    <div class="mt-4">
                        @if ($product->category)
                            <p class="mb-2">
                                <span>{{ __('storefront.product.categories') }}:</span>
                                <span class="text-muted">
                                    <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-muted">
                                        {{ $product->category->name }}
                                    </a>
                                </span>
                            </p>
                        @endif
                        @if ($product->brand)
                            <p class="mb-2">
                                <span>{{ __('storefront.product.brand') }}:</span>
                                <span class="text-muted">
                                    <a href="{{ route('shop.index', ['brand' => $product->brand->slug]) }}" class="text-muted">
                                        {{ $product->brand->name }}
                                    </a>
                                </span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $hasAdditionalInfo = $variants->isNotEmpty() || $product->sku || $product->brand || $product->category;
        $showDescriptionTab = filled($product->description);
        $showReviewsTab = $reviews->isNotEmpty();
        $activeTab = $showDescriptionTab ? 'description' : ($hasAdditionalInfo ? 'additional' : 'reviews');
    @endphp

    @if ($showDescriptionTab || $hasAdditionalInfo || $showReviewsTab)
        <div class="mt-4 mb-5 py-5 main-project-section">
            <div class="container">
                <ul class="nav tab_header justify-content-center" id="productDetailTabs" role="tablist">
                    @if ($showDescriptionTab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill pill-border fw-medium {{ $activeTab === 'description' ? 'active' : '' }}" id="product-description-tab" data-bs-toggle="pill" data-bs-target="#product-description" type="button" role="tab" aria-controls="product-description" aria-selected="{{ $activeTab === 'description' ? 'true' : 'false' }}">
                                {{ __('storefront.product.description') }}
                            </button>
                        </li>
                    @endif
                    @if ($hasAdditionalInfo)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill pill-border fw-medium {{ $activeTab === 'additional' ? 'active' : '' }}" id="product-additional-tab" data-bs-toggle="pill" data-bs-target="#product-additional" type="button" role="tab" aria-controls="product-additional" aria-selected="{{ $activeTab === 'additional' ? 'true' : 'false' }}">
                                {{ __('storefront.product.additional_information') }}
                            </button>
                        </li>
                    @endif
                    @if ($showReviewsTab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill pill-border fw-medium {{ $activeTab === 'reviews' ? 'active' : '' }}" id="product-reviews-tab" data-bs-toggle="pill" data-bs-target="#product-reviews" type="button" role="tab" aria-controls="product-reviews" aria-selected="{{ $activeTab === 'reviews' ? 'true' : 'false' }}">
                                {{ __('storefront.product.reviews') }} ({{ $reviews->count() }})
                            </button>
                        </li>
                    @endif
                </ul>

                <div class="tab-content mt-5" id="productDetailTabsContent">
                    @if ($showDescriptionTab)
                        <div class="tab-pane fade {{ $activeTab === 'description' ? 'show active' : '' }}" id="product-description" role="tabpanel" aria-labelledby="product-description-tab" tabindex="0">
                            <p class="text-black mb-0 text-break product-copy">{!! nl2br(e($product->description)) !!}</p>
                        </div>
                    @endif

                    @if ($hasAdditionalInfo)
                        <div class="tab-pane fade {{ $activeTab === 'additional' ? 'show active' : '' }}" id="product-additional" role="tabpanel" aria-labelledby="product-additional-tab" tabindex="0">
                            <table class="table table-bordered">
                                <tbody>
                                    @if ($volumeOptions !== '')
                                        <tr>
                                            <th class="bg-transparent" scope="row">{{ __('storefront.product.volume') }}</th>
                                            <td class="bg-transparent">{{ $volumeOptions }}</td>
                                        </tr>
                                    @endif
                                    @if ($product->sku)
                                        <tr>
                                            <th class="bg-transparent" scope="row">{{ __('storefront.product.sku') }}</th>
                                            <td class="bg-transparent">{{ $product->sku }}</td>
                                        </tr>
                                    @endif
                                    @if ($product->brand)
                                        <tr>
                                            <th class="bg-transparent" scope="row">{{ __('storefront.product.brand') }}</th>
                                            <td class="bg-transparent">{{ $product->brand->name }}</td>
                                        </tr>
                                    @endif
                                    @if ($product->category)
                                        <tr>
                                            <th class="bg-transparent" scope="row">{{ __('storefront.product.category') }}</th>
                                            <td class="bg-transparent">{{ $product->category->name }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if ($showReviewsTab)
                        <div class="tab-pane fade {{ $activeTab === 'reviews' ? 'show active' : '' }}" id="product-reviews" role="tabpanel" aria-labelledby="product-reviews-tab" tabindex="0">
                            <div class="row mb-4">
                                <div class="col-md-3 text-center">
                                    <p class="mb-0">{{ __('storefront.product.average_rating') }}</p>
                                    <h2 class="fw-bold my-1">{{ number_format($averageRating, 1) }}</h2>
                                    <div class="kalles-rating-result">
                                        <span class="kalles-rating-result__pipe">
                                            @for ($star = 1; $star <= 5; $star++)
                                                <span class="kalles-rating-result__start kalles-rating-result__start--big {{ $star <= round($averageRating) ? '' : 'text-muted' }} {{ $star === (int) round($averageRating) && $averageRating < 5 ? 'active' : '' }}"></span>
                                            @endfor
                                        </span>
                                    </div>
                                    <p class="text-muted mb-0">{{ trans_choice('storefront.product.reviews_count', $reviews->count(), ['count' => $reviews->count()]) }}</p>
                                </div>
                            </div>

                            <div class="row g-3 review-container">
                                @foreach ($reviews as $review)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="rounded-pill d-inline-block align-items-center p-1 bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <p class="mb-0 rounded-pill bg-warning text-white d-inline-block text-center d-flex justify-content-center align-items-center" style="width: 25px; height: 25px;">
                                                            {{ mb_strtoupper(mb_substr($review->author_name, 0, 1)) }}
                                                        </p>
                                                        <span class="fw-bold mx-2">{{ $review->author_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="kalles-rating-result my-2">
                                                    <span class="kalles-rating-result__pipe">
                                                        @for ($star = 1; $star <= 5; $star++)
                                                            <span class="kalles-rating-result__start kalles-rating-result__start--big {{ $star <= $review->rating ? '' : 'text-muted' }}"></span>
                                                        @endfor
                                                    </span>
                                                </div>
                                                @if (filled($review->title))
                                                    <h6>{{ $review->title }}</h6>
                                                @endif
                                                <p class="text-muted mb-2">{{ $review->body }}</p>
                                                <p class="text-muted mb-0 opacity-75 fs-14">{{ $review->created_at?->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($relatedProducts->isNotEmpty())
        <section class="pb-5">
            <div class="container">
                <h3 class="mb-4">{{ __('storefront.product.related') }}</h3>
                <div class="row g-3 g-lg-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="col-6 col-md-3">
                            @include('storefront.partials.product-card', ['product' => $relatedProduct])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('styles')
    <style>
        .product-details {
            min-width: 0;
        }

        .product-copy {
            overflow-wrap: anywhere;
            word-break: break-word;
            white-space: normal;
            max-width: 100%;
        }

        /* Template size chips are 2rem circles for S/M/L; widen for volume labels like "100 ml". */
        .product-details .product-color-list.size a.square-xs {
            width: auto !important;
            min-width: 4.75rem;
            padding-inline: 0.9rem;
            white-space: nowrap;
            font-size: 0.8125rem;
            line-height: 1;
        }

        /* Scale full image down to match details column height — no crop. */
        .product-gallery .productMain .thumbnail-container,
        .product-gallery .productMain .product {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--perfume-well);
        }

        .product-gallery .productMain img,
        .product-gallery > img {
            max-height: 500px;
            width: 100%;
            height: auto;
            object-fit: contain;
            object-position: center;
        }

        .product-gallery .productSmall .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--perfume-well);
        }

        .product-gallery .productSmall img {
            max-height: 80px;
            width: 100%;
            height: auto;
            object-fit: contain;
            object-position: center;
        }
    </style>
@endpush
