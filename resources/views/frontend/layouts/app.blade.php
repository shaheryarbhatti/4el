{{--
    STOREFRONT MASTER LAYOUT  (Porto demo36 template)
    ============================================================================
    Front-end pages extend this:

        @extends('frontend.layouts.app')
        @section('content') ... page body ... @endsection

    The header, footer, mobile nav and all CSS/JS live in the partials under
    resources/views/frontend/layouts/partials/. Asset paths resolve to
    public/frontend-assets/ (copied from the Porto template).
--}}
<!DOCTYPE html>
<html lang="en">

@include('frontend.layouts.partials.head')

<body>
    <div class="page-wrapper">

        {{-- Top notice bar + main header/navigation --}}
        @include('frontend.layouts.partials.header')

        {{-- ================= PAGE CONTENT ================= --}}
        <main class="main">
            @yield('content')
        </main>
        {{-- =============== /PAGE CONTENT =============== --}}

        {{-- Footer (links, newsletter, payment icons) --}}
        @include('frontend.layouts.partials.footer')

    </div>
    {{-- End .page-wrapper --}}

    {{-- ========= Login / Register Modal ========= --}}
    @guest
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="position:relative;">

                {{-- Close button --}}
                <button class="lm-close" data-dismiss="modal" aria-label="Close">&times;</button>

                {{-- ===== LEFT DARK PANEL ===== --}}
                <div class="lm-panel">
                    <div class="lm-panel__dots"></div>
                    <div class="lm-panel__orb lm-panel__orb--1"></div>
                    <div class="lm-panel__orb lm-panel__orb--2"></div>
                    <div class="lm-panel__orb lm-panel__orb--3"></div>

                    {{-- Brand --}}
                    <div class="lm-brand">
                        <div class="lm-brand__logo">
                            <div class="lm-brand__icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div>
                                <div class="lm-brand__name">{{ setting('site_name', 'Marketplace') }}</div>
                                <div class="lm-brand__tagline">Buy &amp; Sell Everything</div>
                            </div>
                        </div>
                        <div class="lm-headline">
                            Your next great<br>deal is <span>one click</span><br>away.
                        </div>
                        <div class="lm-desc">
                            Join millions of buyers and sellers. Sign in to track orders, manage listings, and discover deals.
                        </div>
                    </div>

                    {{-- Feature list --}}
                    <div class="lm-features">
                        <div class="lm-feature">
                            <div class="lm-feature__icon lm-feature__icon--blue">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="lm-feature__text">Secure &amp; encrypted login</div>
                        </div>
                        <div class="lm-feature">
                            <div class="lm-feature__icon lm-feature__icon--pink">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="lm-feature__text">Exclusive member-only deals</div>
                        </div>
                        <div class="lm-feature">
                            <div class="lm-feature__icon lm-feature__icon--green">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="lm-feature__text">Track orders in real-time</div>
                        </div>
                    </div>
                </div>

                {{-- ===== RIGHT FORM PANEL ===== --}}
                <div class="lm-form-panel">

                    {{-- Tabs --}}
                    <div class="lm-tabs">
                        <button class="lm-tab active" data-lm-tab="login">Sign In</button>
                        <button class="lm-tab" data-lm-tab="register">Create Account</button>
                    </div>

                    {{-- ---- Login pane ---- --}}
                    <div class="lm-pane active" id="lm-pane-login">
                        @if ($errors->has('email') && old('_form') === 'login')
                            <div class="lm-alert">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="_form" value="login">

                            <div class="lm-field">
                                <label>Email address</label>
                                <div class="lm-input-wrap">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        placeholder="you@example.com" required>
                                    <i class="lm-icon fas fa-envelope"></i>
                                </div>
                            </div>

                            <div class="lm-field">
                                <label>Password</label>
                                <div class="lm-input-wrap">
                                    <input type="password" name="password" placeholder="••••••••" required>
                                    <i class="lm-icon fas fa-lock"></i>
                                </div>
                            </div>

                            <div class="lm-row">
                                <label class="lm-remember">
                                    <input type="checkbox" name="remember"> Remember me
                                </label>
                                <a href="{{ route('login') }}" class="lm-forgot">Forgot password?</a>
                            </div>

                            <button type="submit" class="lm-submit">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </button>
                        </form>

                        <div class="lm-divider">or</div>

                        <div class="lm-switch">
                            No account? <a href="#" class="lm-tab-switch" data-lm-target="register">Create one free</a>
                        </div>

                        <div class="lm-trust">
                            <div class="lm-trust__item"><i class="fas fa-lock"></i> SSL Secured</div>
                            <div class="lm-trust__item"><i class="fas fa-check-circle"></i> Verified Sellers</div>
                            <div class="lm-trust__item"><i class="fas fa-star"></i> Trusted Platform</div>
                        </div>
                    </div>

                    {{-- ---- Register pane ---- --}}
                    <div class="lm-pane" id="lm-pane-register">
                        @if ($errors->any() && old('_form') === 'register')
                            <div class="lm-alert">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="hidden" name="_form" value="register">

                            <div class="lm-field">
                                <label>Full Name</label>
                                <div class="lm-input-wrap">
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        placeholder="John Doe" required>
                                    <i class="lm-icon fas fa-user"></i>
                                </div>
                            </div>

                            <div class="lm-field">
                                <label>Email address</label>
                                <div class="lm-input-wrap">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        placeholder="you@example.com" required>
                                    <i class="lm-icon fas fa-envelope"></i>
                                </div>
                            </div>

                            <div class="lm-field">
                                <label>Password</label>
                                <div class="lm-input-wrap">
                                    <input type="password" name="password"
                                        placeholder="Min. 8 characters" required>
                                    <i class="lm-icon fas fa-lock"></i>
                                </div>
                            </div>

                            <div class="lm-field">
                                <label>Confirm Password</label>
                                <div class="lm-input-wrap">
                                    <input type="password" name="password_confirmation"
                                        placeholder="Repeat password" required>
                                    <i class="lm-icon fas fa-lock"></i>
                                </div>
                            </div>

                            <button type="submit" class="lm-submit">
                                <i class="fas fa-user-plus mr-2"></i> Create Account
                            </button>
                        </form>

                        <div class="lm-switch" style="margin-top:16px;">
                            Already have an account? <a href="#" class="lm-tab-switch" data-lm-target="login">Sign in</a>
                        </div>
                    </div>

                </div>{{-- /lm-form-panel --}}

            </div>{{-- /modal-content --}}
        </div>
    </div>
    @endguest
    {{-- ========= /Login Modal ========= --}}

    {{-- Mobile off-canvas menu + sticky bottom bar --}}
    @include('frontend.layouts.partials.mobile-nav')

    @include('frontend.layouts.partials.scripts')
</body>
</html>
