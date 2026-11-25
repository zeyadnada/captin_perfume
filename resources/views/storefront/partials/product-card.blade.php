@php
    $activeVariants = $product->variants;
    $minPrice = $activeVariants->min('price');
    $compareAt = $activeVariants
        ->filter(fn ($variant) => filled($variant->compare_at_price))
        ->min('compare_at_price');
    $hasSale = $compareAt !== null && $minPrice !== null && (float) $compareAt > (float) $minPrice;
    $inStock = $activeVariants->contains(fn ($variant) => ($variant->stock?->availableQuantity() ?? 0) > 0);
    $image = $product->images->first();
    $imageUrl = $image
        ? asset('storage/'.$image->path)
        : asset('kalles/images/products/pr-01.jpg');
    $volumes = $activeVariants
        ->pluck('volume')
        ->filter()
        ->unique()
        ->values();
@endphp
<div class="topbar-product-card pb-3 w-100 h-100">
    <div class="position-relative overflow-hidden product-card-media">
        @if (! $inStock)
            <span class="new-label bg-secondary text-white rounded-circle">{{ __('storefront.product.out_of_stock') }}</span>
        @elseif ($hasSale)
            <span class="new-label bg-danger text-white rounded-circle">{{ __('storefront.product.sale') }}</span>
        @endif

        <a href="{{ route('shop.show', $product->slug) }}" class="d-block w-100 h-100">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="img-fluid object-fit-contain w-100 h-100">
        </a>

        <div class="product-button d-none d-lg-flex flex-column gap-2">
            <a href="{{ route('shop.show', $product->slug) }}" class="btn rounded-pill fs-14">
                <span>{{ __('storefront.product.quick_view') }}</span>
                <i class="iccl iccl-eye"></i>
            </a>
        </div>
        <div class="position-absolute d-lg-none bottom-0 end-0 d-flex flex-column bg-white rounded-pill m-2" style="z-index: 1;">
            <a href="{{ route('shop.show', $product->slug) }}" class="btn responsive-cart rounded-pill fs-14 p-2" style="width:36px; height: 36px;">
                <i class="iccl iccl-eye fw-semibold"></i>
            </a>
        </div>

        @if ($volumes->isNotEmpty())
            <p class="product-size mb-0 text-center text-white fw-medium">
                {{ $volumes->map(fn ($volume) => $volume.'ml')->implode(', ') }}
            </p>
        @endif
    </div>
    <div class="mt-3">
        <h6 class="mb-1">
            <a href="{{ route('shop.show', $product->slug) }}" class="product-title">{{ $product->name }}</a>
        </h6>
        @if ($product->brand)
            <p class="mb-1 fs-13 text-muted">{{ $product->brand->name }}</p>
        @endif
        @if ($minPrice !== null)
            <p class="mb-0 fs-14 text-muted">
                @if ($hasSale)
                    <del>${{ number_format((float) $compareAt, 2) }}</del>
                    <span class="text-danger">${{ number_format((float) $minPrice, 2) }}</span>
                @else
                    <span>{{ __('storefront.product.price_from', ['price' => number_format((float) $minPrice, 2)]) }}</span>
                @endif
            </p>
        @endif
    </div>
</div>
