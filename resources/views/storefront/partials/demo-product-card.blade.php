@php
    $image = $image ?? 'kalles/images/products/pr-01.jpg';
    $hover = $hover ?? $image;
    $title = $title ?? 'Perfume';
    $price = $price ?? '$30.00';
    $comparePrice = $comparePrice ?? null;
    $label = $label ?? null;
    $labelClass = $labelClass ?? 'bg-success';
    $colClass = $colClass ?? 'col-md-3 col-6';
    $link = $link ?? route('shop.index');
@endphp
<div class="{{ $colClass }}">
    <div
        x-data="{ imageUrl: '{{ asset($image) }}', isHovered: false }"
        class="topbar-product-card pb-3"
        x-on:mouseenter="isHovered = true"
        x-on:mouseleave="isHovered = false"
    >
        <div class="position-relative overflow-hidden product-card-media">
            @if ($label)
                <span class="new-label {{ $labelClass }} text-white rounded-circle text-center">{{ $label }}</span>
            @endif
            <img :src="isHovered ? '{{ asset($hover) }}' : imageUrl" alt="{{ $title }}" class="img-fluid object-fit-contain w-100 h-100">
            <div class="product-button d-none d-lg-flex flex-column gap-2">
                <a href="{{ $link }}" class="btn rounded-pill fs-14">
                    <span>{{ __('storefront.product.quick_view') }}</span>
                    <i class="iccl iccl-eye"></i>
                </a>
            </div>
            <div class="position-absolute d-lg-none bottom-0 end-0 d-flex flex-column bg-white rounded-pill m-2" style="z-index: 1;">
                <a href="{{ $link }}" class="btn responsive-cart rounded-pill fs-14 p-2" style="width:36px; height: 36px;">
                    <i class="iccl iccl-eye fw-semibold"></i>
                </a>
            </div>
        </div>
        <a href="{{ $link }}" class="mt-3 d-block">
            <h6 class="mb-1">{{ $title }}</h6>
            <p class="mb-0 fs-14 text-muted">
                @if ($comparePrice)
                    <del>{{ $comparePrice }}</del>
                    <span class="text-danger">{{ $price }}</span>
                @else
                    <span>{{ $price }}</span>
                @endif
            </p>
        </a>
    </div>
</div>
