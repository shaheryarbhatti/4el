{{--
    Admin left sidebar menu.
    ----------------------------------------------------------------------------
    HOW TO ADD A MENU ITEM:
      Copy a <li class="slide"> block, set the href to your route() and the
      text/icon. Use the `active` / `show` classes (via request()->routeIs())
      to highlight the current page.

    Items marked "coming soon" point to # and are placeholders for the modules
    we build in later phases (products, orders, auctions, vendors...).
--}}
<aside class="app-sidebar sticky" id="sidebar">

    {{-- Sidebar logo header --}}
    <div class="main-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
        </a>
    </div>

    {{-- Logged-in user card --}}
    @php
        $sbUser = auth()->user();
        $sbRole = $sbUser?->getRoleNames()->first() ?? 'Administrator';
        $sbAvatar = $sbUser && $sbUser->avatar
            ? asset('storage/'.$sbUser->avatar)
            : asset('admin-assets/images/faces/9.jpg');
    @endphp
    <div class="sb-user">
        <div class="sb-user__avatar">
            <img src="{{ $sbAvatar }}" alt="{{ $sbUser->name ?? 'User' }}">
            <span class="sb-user__status" title="Online"></span>
        </div>
        <div class="sb-user__info">
            <div class="sb-user__name">{{ $sbUser->name ?? 'Admin' }}</div>
            <div class="sb-user__role"><i class="bx bxs-shield-alt-2"></i> {{ ucfirst($sbRole) }}</div>
        </div>
    </div>

    <div class="main-sidebar" id="sidebar-scroll">
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <ul class="main-menu">

                {{-- ===================== MAIN ===================== --}}
                <li class="slide__category"><span class="category-name">Main</span></li>

                <li class="slide {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item">
                        <i class="bx bx-home-alt side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                {{-- ================== CATALOG (coming soon) ================== --}}
                <li class="slide__category"><span class="category-name">Catalog</span></li>

                <li class="slide {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="side-menu__item">
                        <i class="bx bx-purchase-tag side-menu__icon"></i>
                        <span class="side-menu__label">Categories</span>
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.brands.index') }}" class="side-menu__item">
                        <i class="bx bx-bookmark side-menu__icon"></i>
                        <span class="side-menu__label">Brands</span>
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.tax-classes.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tax-classes.index') }}" class="side-menu__item">
                        <i class="bx bx-receipt side-menu__icon"></i>
                        <span class="side-menu__label">Tax Classes</span>
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}" class="side-menu__item">
                        <i class="bx bx-package side-menu__icon"></i>
                        <span class="side-menu__label">Products</span>
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.product-reviews.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.product-reviews.index') }}" class="side-menu__item">
                        <i class="bx bx-star side-menu__icon"></i>
                        <span class="side-menu__label">Product Reviews</span>
                        @php $pendingCount = \App\Models\ProductReview::where('is_approved', false)->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.specifications.*') || request()->routeIs('admin.category-specifications.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.specifications.overview') }}" class="side-menu__item">
                        <i class="bx bx-slider-alt side-menu__icon"></i>
                        <span class="side-menu__label">Item Specifications</span>
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.auctions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.auctions.index') }}" class="side-menu__item">
                        <i class="bx bx-time-five side-menu__icon"></i>
                        <span class="side-menu__label">Auctions</span>
                    </a>
                </li>

                {{-- ================== SALES (coming soon) ================== --}}
                <li class="slide__category"><span class="category-name">Sales</span></li>

                <li class="slide {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}" class="side-menu__item">
                        <i class="bx bx-cart side-menu__icon"></i>
                        <span class="side-menu__label">Orders</span>
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.coupons.index') }}" class="side-menu__item">
                        <i class="bx bx-purchase-tag-alt side-menu__icon"></i>
                        <span class="side-menu__label">Coupons</span>
                    </a>
                </li>

                {{-- ================== USERS ================== --}}
                <li class="slide__category"><span class="category-name">Users</span></li>

                <li class="slide {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.vendors.index') }}" class="side-menu__item">
                        <i class="bx bx-store-alt side-menu__icon"></i>
                        <span class="side-menu__label">Vendors</span>
                    </a>
                </li>
                <li class="slide {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.customers.index') }}" class="side-menu__item">
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">Customers</span>
                    </a>
                </li>

                {{-- ================== CONTENT ================== --}}
                <li class="slide__category"><span class="category-name">Content</span></li>

                {{-- Pages (with submenu) --}}
                <li class="slide has-sub {{ request()->routeIs('admin.home-sections.*') || request()->routeIs('admin.hero-slides.*') || request()->routeIs('admin.promo-banners.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-file side-menu__icon"></i>
                        <span class="side-menu__label">Pages</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1 {{ request()->routeIs('admin.home-sections.*') || request()->routeIs('admin.hero-slides.*') || request()->routeIs('admin.promo-banners.*') ? 'active' : '' }}">
                        <li class="slide {{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.hero-slides.index') }}" class="side-menu__item">Hero Slides</a>
                        </li>
                        <li class="slide {{ request()->routeIs('admin.promo-banners.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.promo-banners.index') }}" class="side-menu__item">Promo Banners</a>
                        </li>
                        <li class="slide {{ request()->routeIs('admin.home-sections.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.home-sections.index') }}" class="side-menu__item">Home Sections</a>
                        </li>
                    </ul>
                </li>

                {{-- ================== SETTINGS ================== --}}
                <li class="slide__category"><span class="category-name">System</span></li>

                <li class="slide has-sub {{ request()->routeIs('admin.settings.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-cog side-menu__icon"></i>
                        <span class="side-menu__label">Settings</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        @php
                            // Settings tabs: key => label (matches SettingController groups)
                            $settingTabs = [
                                'general' => 'General',
                                'payment' => 'Payment (Stripe/PayPal)',
                                'map'     => 'Google Maps',
                                'smtp'    => 'Email / SMTP',
                            ];
                        @endphp
                        @foreach ($settingTabs as $key => $label)
                            <li class="slide {{ request()->routeIs('admin.settings.*') && request()->route('group', 'general') === $key ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.index', $key) }}" class="side-menu__item">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>
