<div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch">+</div>
        <form class="search-model-form" action="{{ route('shop.index') }}" method="get">
            <input type="text" id="search-input" name="search" placeholder="{{ __('storefront.search.placeholder') }}">
        </form>
    </div>
</div>
