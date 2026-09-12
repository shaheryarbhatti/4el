{{--
    Admin top header bar.
    Kept intentionally lean: sidebar toggle, theme switch, and the profile
    dropdown with a working Logout. Add more header widgets as needed.
--}}
<header class="app-header">
    <div class="main-header-container container-fluid">

        {{-- Left: logo + sidebar toggle --}}
        <div class="header-content-left">
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ route('admin.dashboard') }}" class="header-logo">
                        <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('admin-assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
                    </a>
                </div>
            </div>
            <div class="header-element mx-lg-0 mx-2">
                <a aria-label="Hide Sidebar"
                   class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                   data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
            </div>
        </div>

        {{-- Right: view site, theme toggle, notifications, profile --}}
        <div class="header-content-right">

            {{-- Quick link to open the storefront in a new tab --}}
            <div class="header-element d-none d-md-block">
                <a href="{{ route('home') }}" target="_blank" class="header-link">
                    <i class="bx bx-store header-link-icon"></i>
                </a>
            </div>

            {{-- Light / dark toggle (built into the YNEX theme) --}}
            <div class="header-element">
                <a href="javascript:void(0);" class="header-link layout-setting">
                    <span class="light-layout"><i class="bx bx-moon header-link-icon"></i></span>
                    <span class="dark-layout"><i class="bx bx-sun header-link-icon"></i></span>
                </a>
            </div>

            {{-- ── Notifications Bell ── --}}
            @php
                use App\Models\Order;
                $notifOrders = Order::where('status','pending')
                    ->where('created_at', '>=', now()->subHours(24))
                    ->latest()
                    ->take(5)
                    ->get();
                $notifCount = Order::where('status','pending')
                    ->where('created_at', '>=', now()->subHours(24))
                    ->count();
            @endphp
            <div class="header-element">
                <a href="javascript:void(0);" class="header-link dropdown-toggle"
                   data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                   style="position:relative;">
                    <i class="bx bx-bell header-link-icon"></i>
                    @if($notifCount > 0)
                    <span style="position:absolute;top:4px;right:4px;background:#ef4444;color:#fff;font-size:9px;font-weight:800;min-width:16px;height:16px;border-radius:8px;display:flex;align-items:center;justify-content:center;line-height:1;padding:0 3px;border:1.5px solid #fff;">
                        {{ $notifCount > 99 ? '99+' : $notifCount }}
                    </span>
                    @endif
                </a>
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end"
                     style="width:320px;padding:0;border-radius:12px;overflow:hidden;border:1px solid #f0f0f0;box-shadow:0 12px 40px rgba(0,0,0,.12);">
                    {{-- Header --}}
                    <div style="padding:14px 18px;background:linear-gradient(135deg,#1a1a2e,#0f3460);display:flex;align-items:center;justify-content:space-between;">
                        <div style="color:#fff;font-size:14px;font-weight:700;">
                            <i class="bx bx-bell" style="margin-right:6px;"></i>New Orders
                        </div>
                        @if($notifCount > 0)
                        <span style="background:#ef4444;color:#fff;font-size:10px;font-weight:800;padding:2px 8px;border-radius:10px;">
                            {{ $notifCount }} pending
                        </span>
                        @else
                        <span style="color:rgba(255,255,255,.6);font-size:11px;">All caught up</span>
                        @endif
                    </div>

                    {{-- Items --}}
                    @if($notifOrders->count())
                    <div style="max-height:280px;overflow-y:auto;">
                        @foreach($notifOrders as $no)
                        <a href="{{ route('admin.orders.show', $no->id) }}"
                           style="display:flex;align-items:flex-start;gap:12px;padding:12px 16px;border-bottom:1px solid #f8f8f8;text-decoration:none;transition:background .15s;"
                           onmouseover="this.style.background='#f8fafc';"
                           onmouseout="this.style.background='';">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bx bx-package" style="color:#fff;font-size:16px;"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:12.5px;font-weight:700;color:#1a1a2e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $no->order_number }}
                                </div>
                                <div style="font-size:11.5px;color:#666;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $no->customer_name }}
                                    &nbsp;&bull;&nbsp;
                                    <strong style="color:#1a1a2e;">{{ setting('currency_symbol','$') }}{{ number_format($no->grand_total, 2) }}</strong>
                                </div>
                                <div style="font-size:10.5px;color:#aaa;margin-top:2px;">
                                    {{ $no->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <span style="background:#fef9c3;color:#854d0e;font-size:10px;font-weight:700;padding:2px 7px;border-radius:8px;flex-shrink:0;margin-top:2px;">
                                Pending
                            </span>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div style="padding:32px 16px;text-align:center;color:#aaa;font-size:13px;">
                        <i class="bx bx-check-circle" style="font-size:32px;color:#22c55e;display:block;margin-bottom:8px;"></i>
                        No new pending orders in the last 24 hours.
                    </div>
                    @endif

                    {{-- Footer link --}}
                    <div style="border-top:1px solid #f0f0f0;padding:10px 16px;background:#fafafa;">
                        <a href="{{ route('admin.orders.index') }}"
                           style="display:block;text-align:center;font-size:12.5px;font-weight:700;color:#336699;text-decoration:none;">
                            View All Orders <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Profile dropdown --}}
            <div class="header-element">
                <a href="javascript:void(0);" class="header-link dropdown-toggle"
                   id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-sm-2 me-0">
                            <img src="{{ asset('admin-assets/images/faces/9.jpg') }}" alt="img"
                                 width="32" height="32" class="rounded-circle">
                        </div>
                        <div class="d-none d-xl-block">
                            <p class="fw-semibold mb-0 lh-1">{{ auth()->user()->name }}</p>
                            <span class="op-7 fw-normal d-block fs-11">Administrator</span>
                        </div>
                    </div>
                </a>
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                    aria-labelledby="mainHeaderProfile">
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.settings.index', 'general') }}">
                        <i class="bx bx-cog fs-16 me-2"></i>Settings</a></li>
                    <li>
                        {{-- Logout must be a POST for CSRF safety --}}
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center border-0 bg-transparent w-100">
                                <i class="bx bx-log-out fs-16 me-2"></i>Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</header>
