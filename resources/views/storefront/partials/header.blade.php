<div id="kalles-section-header_top" class="">
    <div class="h__top d-flex align-items-center">
        <div class="container-fluid">
            <div class="row py-2">
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex justify-content-center justify-content-md-start">
                        <p class="mb-0"><i class="pegk pe-7s-call fs-14 me-1 align-middle"></i> +01 23456789</p>
                        <p class="mb-0 ms-2"><i class="pe-7s-mail pegk fs-14 me-1 align-middle"></i> Kalles@domain.com
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 header-text text-center fs-12 py-1 py-md-0">
                    Summer sale discount off <span class="cr">50%</span>! <a href="{{ route('shop.index') }}" class="text-reset">Shop
                        Now</a>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-custom  py-0 d-flex align-items-center">
        <!-- MENUBAR -->
        <div class="container-fluid">
            <a class="d-lg-none" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="16" viewBox="0 0 30 16">
                    <rect width="30" height="1.5"></rect>
                    <rect y="7" width="20" height="1.5"></rect>
                    <rect y="14" width="30" height="1.5"></rect>
                </svg>
            </a>

            <a class="navbar-brand nav-link me-0" href="{{ route('home') }}"><img src="{{ asset('kalles/images/svg/kalles.svg') }}" alt="" width="95"></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="d-none d-lg-block mx-auto">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">{{ __('storefront.nav.shop') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.index') }}">{{ __('storefront.nav.product') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.index') }}">{{ __('storefront.nav.sale') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="topbar-toolbar d-flex align-items-center gap-3">
                <div class="dropdown d-none d-md-block">
                    <a href="#!" class="fs-12 text-reset" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('storefront.header.language') }}
                        <i class="facl facl-angle-down ms-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2">
                        @foreach (config('app.available_locales', []) as $code => $meta)
                            <li><a class="dropdown-item" href="{{ route('locale.switch', $code) }}">{{ $meta['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <a data-bs-toggle="offcanvas" href="#searchOffcanvas" aria-controls="searchOffcanvas">
                    <i class="iccl iccl-search"></i>
                </a>
                <a class="d-md-block d-none" data-bs-toggle="offcanvas" href="#accountOffcanvas" aria-controls="accountOffcanvas"><i class="iccl iccl-user"></i></a>
                <a class="d-md-block d-none" href="wishlist.html"><i class="iccl iccl-heart"></i><span class="tcount bg-dark text-white rounded-circle d-flex align-items-center justify-content-center">3</span></a>
                <a data-bs-toggle="offcanvas" href="#shoppingCartOffcanvas" aria-controls="shoppingCartOffcanvas"><i class="iccl iccl-cart"></i><span class="tcount bg-dark text-white rounded-circle d-flex align-items-center justify-content-center">5</span></a>
            </div>

        </div>
    </nav>
</div>

