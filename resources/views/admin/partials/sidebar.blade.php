@php
    $navigation = [
        ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge-high', 'route' => 'admin.dashboard'],
        ['label' => 'Brands', 'icon' => 'fa-solid fa-tags', 'route' => null],
        ['label' => 'Categories', 'icon' => 'fa-solid fa-folder-tree', 'route' => null],
        ['label' => 'Products', 'icon' => 'fa-solid fa-bottle-droplet', 'route' => null],
    ];
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
                @foreach ($navigation as $item)
                    @php $active = $item['route'] && request()->routeIs($item['route']); @endphp
                    <li class="{{ $active ? 'active' : '' }}">
                        @if ($item['route'])
                            <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif>
                                <i class="{{ $item['icon'] }}"></i>{{ $item['label'] }}
                            </a>
                        @else
                            <a href="#" aria-disabled="true">
                                <i class="{{ $item['icon'] }}"></i>{{ $item['label'] }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
