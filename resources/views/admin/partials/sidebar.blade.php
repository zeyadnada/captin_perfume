@php
    $masterDataActive = request()->routeIs('admin.brands.*', 'admin.categories.*', 'admin.products.*');
@endphp

<aside class="menu-sidebar" id="main-sidebar">
    <div class="logo">
        <a class="logo-link" href="{{ route('admin.dashboard') }}" aria-label="{{ __('admin.nav.app_home_aria', ['app' => config('app.name')]) }}">
            <span class="logo-mark" aria-hidden="true">C</span>
            <span class="logo-text">{{ config('app.name') }}</span>
        </a>
        <button class="sidebar-close js-sidebar-toggle" type="button" aria-label="{{ __('admin.common.close') }}">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                        <i class="fa-solid fa-gauge-high"></i>{{ __('admin.nav.dashboard') }}
                    </a>
                </li>
                <li class="has-sub {{ $masterDataActive ? 'active' : '' }}">
                    <a class="js-arrow {{ $masterDataActive ? 'open' : '' }}" href="#" @if ($masterDataActive) aria-expanded="true" @endif>
                        <i class="fa-solid fa-layer-group"></i>{{ __('admin.nav.master_data') }}
                    </a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list" @if ($masterDataActive) style="display: block;" @endif>
                        <li class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.brands.index') }}" >
                                {{ __('admin.nav.brands') }}
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.categories.index') }}">{{ __('admin.nav.categories') }}</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.products.index') }}">{{ __('admin.nav.products') }}</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.inventory.index') }}">
                        <i class="fa-solid fa-boxes-stacked"></i>{{ __('admin.nav.inventory') }}
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.stock-movements.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.stock-movements.index') }}">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>{{ __('admin.nav.stock_movements') }}
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>