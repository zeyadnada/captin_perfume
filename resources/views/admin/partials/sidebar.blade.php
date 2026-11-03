@php
    $masterDataActive = request()->routeIs('admin.brands.*', 'admin.categories.*');
@endphp

<aside class="menu-sidebar" id="main-sidebar">
    <div class="logo">
        <a class="logo-link" href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }} home">
            <span class="logo-mark" aria-hidden="true">C</span>
            <span class="logo-text">{{ config('app.name') }}</span>
        </a>
        <button class="sidebar-close js-sidebar-toggle" type="button" aria-label="Close navigation">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                        <i class="fa-solid fa-gauge-high"></i>Dashboard
                    </a>
                </li>
                <li class="has-sub {{ $masterDataActive ? 'active' : '' }}">
                    <a class="js-arrow {{ $masterDataActive ? 'open' : '' }}" href="#" @if ($masterDataActive) aria-expanded="true" @endif>
                        <i class="fa-solid fa-layer-group"></i>Master Data
                    </a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list" @if ($masterDataActive) style="display: block;" @endif>
                        <li class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.brands.index') }}" >
                                Brands
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.categories.index') }}">Categories</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" aria-disabled="true">
                        <i class="fa-solid fa-bottle-droplet"></i>Products
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
