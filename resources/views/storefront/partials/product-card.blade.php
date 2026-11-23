@php
    $activeVariants = $product->variants;
    $minPrice = $activeVariants->min('price');
    $hasSale = $activeVariants->contains(fn ($variant) => filled($variant->compare_at_price));
    $inStock = $activeVariants->contains(fn ($variant) => ($variant->stock?->availableQuantity() ?? 0) > 0);
    $image = $product->images->first();
    $imageUrl = $image
        ? asset('storage/'.$image->path)
        : asset('malefashion/img/product/product-1.jpg');
@endphp
<div class="product__item {{ $hasSale ? 'sale' : '' }}">
    <div class="product__item__pic set-bg" data-setbg="{{ $imageUrl }}">
        @if (! $inStock)
            <span class="label">{{ __('storefront.product.out_of_stock') }}</span>
        @elseif ($hasSale)
            <span class="label">{{ __('storefront.product.sale') }}</span>
        @endif
        <ul class="product__hover">
            <li>
                <a href="{{ route('shop.show', $product->slug) }}">
                    <img src="{{ asset('malefashion/img/icon/search.png') }}" alt="">
                </a>
            </li>
        </ul>
    </div>
    <div class="product__item__text">
        <h6>
            <a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
        </h6>
        @if ($product->brand)
            <span class="d-block text-muted small mb-1">{{ $product->brand->name }}</span>
        @endif
        <a href="{{ route('shop.show', $product->slug) }}" class="add-cart">
            {{ __('storefront.product.view_details') }}
        </a>
        @if ($minPrice !== null)
            <h5>{{ __('storefront.product.price_from', ['price' => number_format((float) $minPrice, 2)]) }}</h5>
        @endif
    </div>
</div>
