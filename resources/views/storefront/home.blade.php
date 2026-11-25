@extends('layouts.storefront')

@section('content')
<div class="backdrop-shadow d-none"></div><div>

    <!-- main slide -->
    <div class="kalles-home-section type_slideshow type_carousel">
        <div class="slideshow" data-flickity='{ "fade":0,"cellAlign": "center","imagesLoaded": 0,"lazyLoad": 0,"freeScroll": 0,"wrapAround": true,"autoPlay" : 0,"pauseAutoPlayOnHover" : true, "rightToLeft": false, "prevNextButtons": false,"pageDots": true, "contain" : 1,"adaptiveHeight" : 1,"dragThreshold" : 5,"percentPosition": 1 }'>
            <!-- first slide -->
            <div class="slideshow__slide w-100" style='background-image: url("{{ asset('kalles/images/slide/slider-01.jpg') }}");'>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div data-aos="fade-right" data-aos-delay="300">
                                <h4 class="fs-18 fw-medium">{{ __('storefront.home.eyebrow') }}</h4>
                                <h1 class="display-4 fw-semibold mb-3">{{ __('storefront.home.title') }}</h1>
                                <a class="btn btn-dark rounded-0 min-w-150" href="{{ route('shop.index') }}">{{ __('storefront.home.cta') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end first slide -->

            <!-- second slide -->
            <div class="slideshow__slide w-100" style='background-image: url("{{ asset('kalles/images/slide/slider-02.jpg') }}");'>
                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-lg-6">
                            <div class="text-end" data-aos="fade-right" data-aos-delay="300">
                                <h4 class="fs-18 fw-medium">NEW SEASON</h4>
                                <h3 class="display-4 fw-semibold mb-3">Lookbook Collection</h3>
                                <a class="btn btn-dark rounded-0 min-w-150" href="{{ route('shop.index') }}">Explore
                                    Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end second slide -->

            <!-- third slide -->
            <div class="slideshow__slide w-100" style='background-image: url("{{ asset('kalles/images/slide/slider-03.jpg') }}");'>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div data-aos="fade-right" data-aos-delay="300">
                                <h4 class="fs-18 fw-medium">SUMMER SALE</h4>
                                <h1 class="display-4 fw-semibold mb-3">Save up to 70%</h1>
                                <a class="btn btn-dark rounded-0 min-w-150" href="{{ route('shop.index') }}">Explore
                                    Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end third slide -->

        </div>
    </div>
    <!-- end main slide -->

    <section class="cat-section">
        <div class="container">
            <div class="row g-xl-4 g-2">
                <div class="col-md-6">
                    <a href="shop-left-sidebar.html" class="d-block position-relative cat_grid_item overflow-hidden h-570">
                        <div class="h-100 w-100 cat-grid-img" style='background-image: url("{{ asset('kalles/images/home-classic/cat-women.jpg') }}");'></div>
                        <div class="cat-grid-button text-body">
                            <div class="cat_grid_item__title">Women</div>
                        </div>
                    </a>
                    <div class="row gx-xl-4 gx-2 mt-xl-4 mt-2">
                        <div class="col-6">
                            <a href="shop-left-sidebar.html" class="d-block position-relative cat_grid_item overflow-hidden h-300">
                                <div class="h-100 w-100 cat-grid-img" style='background-image: url("{{ asset('kalles/images/home-classic/cat-bags.jpg') }}");'></div>
                                <div class="cat-grid-button text-body">
                                    <div class="cat_grid_item__title">Unisex</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="shop-left-sidebar.html" class="d-block position-relative cat_grid_item overflow-hidden h-300">
                                <div class="h-100 w-100 cat-grid-img" style='background-image: url("{{ asset('kalles/images/home-classic/cat-footerwear.jpg') }}");'>
                                </div>
                                <div class="cat-grid-button text-body">
                                    <div class="cat_grid_item__title">Oriental</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="shop-left-sidebar.html" class="d-block position-relative cat_grid_item overflow-hidden h-300 mb-xl-4 mb-2">
                        <div class="h-100 w-100 cat-grid-img" style='background-image: url("{{ asset('kalles/images/home-classic/cat-men.jpg') }}");background-position: center;'>
                        </div>
                        <div class="cat-grid-button text-body">
                            <div class="cat_grid_item__title">Men</div>
                        </div>
                    </a>
                    <a href="shop-left-sidebar.html" class="d-block position-relative cat_grid_item overflow-hidden h-570">
                        <div class="h-100 w-100 cat-grid-img" style='background-image: url("{{ asset('kalles/images/home-classic/cat-watch.jpg') }}"); background-position: center;'>
                        </div>
                        <div class="cat-grid-button text-body">
                            <div class="cat_grid_item__title">Fresh</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- best sellers -->
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="text-center">
                        <div class="mb-2">
                            <h3 class="section-title position-relative text-uppercase">
                                <span>{{ __('storefront.home.best_sellers') }}</span>
                            </h3>
                        </div>
                        <span class="section-subtitle sub-title font-secondary fst-italic fs-14 text-muted">{{ __('storefront.home.best_sellers_subtitle') }}</span>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            <div class="mt-4">
                <ul class="nav tab_header gap-lg-4 justify-content-center mt-4 mb-0" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill active" id="best-seller-tab" data-bs-toggle="pill" data-bs-target="#best-seller" type="button" role="tab" aria-controls="best-seller" aria-selected="true">Best Seller</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill" id="featured-tab" data-bs-toggle="pill" data-bs-target="#featured" type="button" role="tab" aria-controls="featured" aria-selected="false">Featured</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill" id="sale-tab" data-bs-toggle="pill" data-bs-target="#sale" type="button" role="tab" aria-controls="sale" aria-selected="false">Sale</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill" id="top-sale-tab" data-bs-toggle="pill" data-bs-target="#top-sale" type="button" role="tab" aria-controls="top-sale" aria-selected="false">Top Sale</button>
                    </li>
                </ul>
                                <div class="tab-content mt-4" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="best-seller" role="tabpanel" aria-labelledby="best-seller-tab" tabindex="0">
                        <div class="row g-4">
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-01.jpg', 'hover' => 'kalles/images/products/pr-02.jpg', 'title' => 'Ocean Breeze', 'price' => '$45.00', 'label' => 'New'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-04.jpg', 'hover' => 'kalles/images/products/pr-05.jpg', 'title' => 'Amber Night', 'price' => '$55.00'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-06.jpg', 'hover' => 'kalles/images/products/pr-07.jpg', 'title' => 'Rose Elixir', 'price' => '$40.00', 'comparePrice' => '$60.00', 'label' => '-25%', 'labelClass' => 'bg-danger'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-08.jpg', 'hover' => 'kalles/images/products/pr-09.jpg', 'title' => 'Citrus Splash', 'price' => '$35.00'])
                        </div>
                    </div>
                    <div class="tab-pane fade" id="featured" role="tabpanel" aria-labelledby="featured-tab" tabindex="0">
                        <div class="row g-4">
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-01.jpg', 'hover' => 'kalles/images/products/pr-02.jpg', 'title' => 'Ocean Breeze', 'price' => '$45.00', 'label' => 'New'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-04.jpg', 'hover' => 'kalles/images/products/pr-05.jpg', 'title' => 'Amber Night', 'price' => '$55.00'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-06.jpg', 'hover' => 'kalles/images/products/pr-07.jpg', 'title' => 'Rose Elixir', 'price' => '$40.00', 'comparePrice' => '$60.00', 'label' => '-25%', 'labelClass' => 'bg-danger'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-08.jpg', 'hover' => 'kalles/images/products/pr-09.jpg', 'title' => 'Citrus Splash', 'price' => '$35.00'])
                        </div>
                    </div>
                    <div class="tab-pane fade" id="sale" role="tabpanel" aria-labelledby="sale-tab" tabindex="0">
                        <div class="row g-4">
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-01.jpg', 'hover' => 'kalles/images/products/pr-02.jpg', 'title' => 'Ocean Breeze', 'price' => '$45.00', 'label' => 'New'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-04.jpg', 'hover' => 'kalles/images/products/pr-05.jpg', 'title' => 'Amber Night', 'price' => '$55.00'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-06.jpg', 'hover' => 'kalles/images/products/pr-07.jpg', 'title' => 'Rose Elixir', 'price' => '$40.00', 'comparePrice' => '$60.00', 'label' => '-25%', 'labelClass' => 'bg-danger'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-08.jpg', 'hover' => 'kalles/images/products/pr-09.jpg', 'title' => 'Citrus Splash', 'price' => '$35.00'])
                        </div>
                    </div>
                    <div class="tab-pane fade" id="top-sale" role="tabpanel" aria-labelledby="top-sale-tab" tabindex="0">
                        <div class="row g-4">
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-01.jpg', 'hover' => 'kalles/images/products/pr-02.jpg', 'title' => 'Ocean Breeze', 'price' => '$45.00', 'label' => 'New'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-04.jpg', 'hover' => 'kalles/images/products/pr-05.jpg', 'title' => 'Amber Night', 'price' => '$55.00'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-06.jpg', 'hover' => 'kalles/images/products/pr-07.jpg', 'title' => 'Rose Elixir', 'price' => '$40.00', 'comparePrice' => '$60.00', 'label' => '-25%', 'labelClass' => 'bg-danger'])
                            @include('storefront.partials.demo-product-card', ['image' => 'kalles/images/products/pr-08.jpg', 'hover' => 'kalles/images/products/pr-09.jpg', 'title' => 'Citrus Splash', 'price' => '$35.00'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row g-lg-4 g-3 gy-md-0 gy-4 align-items-center">
                <div class="col-md-4">
                    <a href="shop-right-sidebar.html" class="kalles-banner-promotion d-block">
                        <img src="{{ asset('kalles/images/home-classic/pr-big-23.jpg') }}" alt="" class="img-fluid">
                        <div class="p-20 position-absolute bottom-0 left-0 text-body">
                            <p class="text-uppercase fw-medium fs-14 mb-1">View Collections</p>
                            <h3 class="fs-35">LOOKBOOK</h3>
                            <p class="text-muted mb-0">your world of fashion in numbers</p>
                        </div>
                    </a>
                </div><!--end col-->
                <div class="col-md-4">
                    <div x-data="{ imageUrl: '{{ asset('kalles/images/home-classic/pr-big-24.jpg') }}' }" class="kalles-banner-promotion topbar-product-card">
                        <div class="position-relative overflow-hidden">
                            <span class="new-label bg-danger text-white rounded-circle"> -34% </span>
                            <img :src="imageUrl" alt="" class="img-fluid">
                            <a href="#" class="d-lg-none position-absolute " style="z-index: 1; top:10px; left:10px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add to Wishlist"><i class="facl facl-heart-o text-white"></i></a>
                            <a href="#" class="wishlistadd d-none d-lg-flex position-absolute" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add to Wishlist"><i class="facl facl-heart-o text-white"></i></a>

                            <div class="product-button d-none d-lg-flex flex-column gap-2">
                                <a href="#exampleModal" data-bs-toggle="modal" class="btn rounded-pill fs-14"><span>Quick View</span> <i class="iccl iccl-eye"></i></a>
                            </div>
                            <div class="position-absolute d-lg-none bottom-0 end-0 d-flex flex-column bg-white rounded-pill m-2" style="z-index: 1;">
                                <a href="#exampleModal" data-bs-toggle="modal" class="btn responsive-cart rounded-pill fs-14 p-2" style="width:36px; height: 36px;"><i class="iccl iccl-eye fw-semibold"></i></a>
                            </div>
                            <div class="product-size text-center d-none d-lg-block">
                                <h6 class="mb-1"><a href="product-detail-layout-01.html" class="main_link">La Bohème
                                        Rose Gold</a></h6>
                                <p class="mb-0 fs-14 text-muted">
                                    <del>$60.00</del>
                                    <span class="text-danger">$45.00</span>
                                </p>
                                <div class="product-color-list mt-1 gap-2 d-flex align-items-center justify-content-center p-2">
                                    <a href="#!" x-on:mouseover="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-24.jpg') }}'" x-on:click.prevent="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-24.jpg') }}'" class="d-inline-block bg_color_pink rounded-circle"></a>
                                    <a href="#!" x-on:mouseover="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-25.jpg') }}'" x-on:click.prevent="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-25.jpg') }}'" class="d-inline-block bg-dark rounded-circle"></a>
                                </div>
                            </div>
                            <div class="count-time d-none d-lg-block" data-date="10-10-2028">
                                <ul class="list-unstyled d-flex gap-2 align-items-center text-center justify-content-center mb-0">
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded"><span class="days text-white fs-14">0</span>days</li>
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded"><span class="text-white fs-14">00</span>Hours</li>
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded"><span class="min text-white fs-14">00</span>Minutes</li>
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded"><span class="sc text-white fs-14">00</span>Seconds</li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-lg-none">
                            <div class="text-center mt-3">
                                <h6 class="mb-1"><a href="product-detail-layout-01.html" class="main_link">La Bohème
                                        Rose Gold</a></h6>
                                <p class="mb-0 fs-14 text-muted">
                                    <del>$60.00</del>
                                    <span class="text-danger">$45.00</span>
                                </p>
                                <div class="product-color-list mt-1 gap-2 d-flex align-items-center justify-content-center p-2">
                                    <a href="#!" x-on:mouseover="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-24.jpg') }}'" x-on:click.prevent="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-24.jpg') }}'" class="d-inline-block bg_color_pink rounded-circle"></a>
                                    <a href="#!" x-on:mouseover="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-25.jpg') }}'" x-on:click.prevent="imageUrl = '{{ asset('kalles/images/home-classic/pr-big-25.jpg') }}'" class="d-inline-block bg-dark rounded-circle"></a>
                                </div>
                            </div>
                            <div class="count-time position-relative mt-4 d-lg-block d-none" data-date="10-10-2028">
                                <ul class="list-unstyled d-flex gap-2 align-items-center text-center justify-content-center mb-0">
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded" style="min-width: 50px;"><span class="days text-white fs-14">0</span>days</li>
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded" style="min-width: 50px;"><span class=" text-white fs-14">00</span>Hours</li>
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded" style="min-width: 50px;"><span class="min text-white fs-14">00</span>Minutes</li>
                                    <li class="bg-dark text-white text-white-50 fs-12 rounded" style="min-width: 50px;"><span class="sc text-white fs-14">00</span>Seconds</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="col-md-4">
                    <a href="{{ route('shop.index') }}" class="kalles-banner-promotion d-block">
                        <img src="{{ asset('kalles/images/home-classic/pr-big-26.jpg') }}" alt="" class="img-fluid">
                        <div class="p-20 position-absolute top-50 end-0 text-body start-0 content-position text-center">
                            <p class="text-uppercase fw-medium fs-18 mb-1">Men Collection</p>
                            <h3 class="fs-50">SALE 70%</h3>
                            <button class="btn btn-custom-dark fw-medium min-w-150 rounded-pill">Shop Now</button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- new arrivals -->
    <section class="type_tab_collection">
        <div class="container" dir="ltr">
            <div class="row justify-content-center ">
                <div class="col-lg-7">
                    <div class="text-center">
                        <div class="mb-2">
                            <h3 class="section-title position-relative flex">
                                <span>{{ __('storefront.home.new_arrivals') }}</span>
                            </h3>
                        </div>
                        <span class="section-subtitle sub-title font-secondary fst-italic fs-14 text-muted">{{ __('storefront.home.new_arrivals_subtitle') }}</span>
                    </div>
                </div>
            </div>
            <div class="row my-4 py-2 tranding-card" data-flickity='{"imagesLoaded": 0, "adaptiveHeight": 0, "contain": 1, "groupCells": "100%", "dragThreshold": 5, "cellAlign": "left", "wrapAround": true, "prevNextButtons": true, "percentPosition": 1, "pageDots": false, "autoPlay": 0, "pauseAutoPlayOnHover": true }' dir="ltr">
                @include('storefront.partials.demo-product-card', ['colClass' => 'col-md-3 col-6 px-lg-12 px-2', 'image' => 'kalles/images/products/pr-01.jpg', 'hover' => 'kalles/images/products/pr-02.jpg', 'title' => 'Ocean Breeze', 'price' => '$45.00', 'label' => 'New'])
                @include('storefront.partials.demo-product-card', ['colClass' => 'col-md-3 col-6 px-lg-12 px-2', 'image' => 'kalles/images/products/pr-04.jpg', 'hover' => 'kalles/images/products/pr-05.jpg', 'title' => 'Amber Night', 'price' => '$55.00'])
                @include('storefront.partials.demo-product-card', ['colClass' => 'col-md-3 col-6 px-lg-12 px-2', 'image' => 'kalles/images/products/pr-06.jpg', 'hover' => 'kalles/images/products/pr-07.jpg', 'title' => 'Rose Elixir', 'price' => '$40.00', 'comparePrice' => '$60.00', 'label' => '-25%', 'labelClass' => 'bg-danger'])
                @include('storefront.partials.demo-product-card', ['colClass' => 'col-md-3 col-6 px-lg-12 px-2', 'image' => 'kalles/images/products/pr-08.jpg', 'hover' => 'kalles/images/products/pr-09.jpg', 'title' => 'Citrus Splash', 'price' => '$35.00'])
            </div>
        
        </div>
    </section>
    <div class="kalles-section-type-shipping">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-3 col-md-6">
                    <div class="d-flex gap-3">
                        <i class="pegk pe-7s-car fs-36 text-muted flex-shrink-0"></i>
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase">Free Shipping</h6>
                            <p class="text-muted mb-0">Free shipping on all US order or order above $100</p>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="col-xl-3 col-md-6">
                    <div class="d-flex gap-3">
                        <i class="pegk pe-7s-help2 fs-36 text-muted flex-shrink-0"></i>
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase">Support 24/7</h6>
                            <p class="text-muted mb-0">Contact us 24 hours a day, 7 days a week</p>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="col-xl-3 col-md-6">
                    <div class="d-flex gap-3">
                        <i class="pegk pe-7s-refresh fs-36 text-muted flex-shrink-0"></i>
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase">30 Days Return</h6>
                            <p class="text-muted mb-0">Simply return it within 30 days for an exchange.</p>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="col-xl-3 col-md-6">
                    <div class="d-flex gap-3">
                        <i class="pegk pe-7s-door-lock fs-36 text-muted flex-shrink-0"></i>
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase">100% Payment Secure</h6>
                            <p class="text-muted mb-0">We ensure secure payment with PEV</p>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </div><!--end shipping-->

</div>

@endsection
