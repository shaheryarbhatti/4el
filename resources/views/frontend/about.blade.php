@extends('frontend.layouts.app')
@section('title', 'About Us — ' . config('app.name'))

@push('styles')
<style>
/* ── Hero ── */
.about-hero {
    background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 60%,#336699 100%);
    padding:80px 0 90px;
    text-align:center;
    position:relative;
    overflow:hidden;
}
.about-hero::before {
    content:'';
    position:absolute; inset:0;
    background:radial-gradient(circle at 20% 50%,rgba(255,255,255,.06) 0%,transparent 60%),
               radial-gradient(circle at 80% 20%,rgba(255,255,255,.04) 0%,transparent 50%);
}
.about-hero__tag {
    display:inline-block;
    background:rgba(255,255,255,.12);
    color:rgba(255,255,255,.9);
    font-size:11px;
    font-weight:700;
    letter-spacing:.12em;
    text-transform:uppercase;
    padding:6px 16px;
    border-radius:20px;
    margin-bottom:20px;
    border:1px solid rgba(255,255,255,.18);
    position:relative;
}
.about-hero h1 { color:#fff; font-size:40px; font-weight:900; margin:0 0 18px; position:relative; line-height:1.15; }
.about-hero p  { color:rgba(255,255,255,.72); font-size:17px; max-width:600px; margin:0 auto; position:relative; line-height:1.7; }

/* ── Stats row ── */
.about-stats {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:0;
    background:#fff;
    border-radius:16px;
    box-shadow:0 8px 40px rgba(0,0,0,.10);
    overflow:hidden;
    margin:-50px auto 56px;
    position:relative;
    z-index:2;
    border:1px solid #f0f0f0;
}
@media(max-width:767px){ .about-stats{grid-template-columns:1fr;} }

.about-stat {
    padding:30px 24px;
    text-align:center;
    border-right:1px solid #f0f0f0;
    transition:background .2s;
}
.about-stat:last-child { border-right:none; }
.about-stat:hover { background:#f8fafc; }
.about-stat__num   { font-size:36px; font-weight:900; color:#0f3460; line-height:1; }
.about-stat__label { font-size:13px; color:#888; font-weight:600; text-transform:uppercase; letter-spacing:.06em; margin-top:6px; }

/* ── Section wrapper ── */
.about-section { padding:56px 0; }
.about-section-title {
    font-size:28px; font-weight:800; color:#1a1a2e;
    margin-bottom:8px;
}
.about-section-sub { color:#888; font-size:15px; margin-bottom:36px; }

/* ── Mission two-col ── */
.mission-grid { display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:center; }
@media(max-width:767px){ .mission-grid{grid-template-columns:1fr;gap:30px;} }
.mission-art {
    background:linear-gradient(135deg,#dbeafe,#ede9fe);
    border-radius:18px;
    height:280px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:90px;
}
.mission-body p { font-size:15px; color:#555; line-height:1.8; margin-bottom:14px; }
.mission-body strong { color:#1a1a2e; }

/* ── How it works steps ── */
.hiw-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:28px;
    position:relative;
}
@media(max-width:767px){ .hiw-grid{grid-template-columns:1fr;} }
.hiw-card {
    background:#fff;
    border-radius:16px;
    padding:32px 24px;
    text-align:center;
    box-shadow:0 4px 20px rgba(0,0,0,.07);
    border:1px solid #f0f0f0;
    position:relative;
    transition:transform .2s,box-shadow .2s;
}
.hiw-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(0,0,0,.10); }
.hiw-card__num {
    position:absolute;
    top:-18px; left:50%; transform:translateX(-50%);
    width:36px; height:36px; border-radius:50%;
    background:linear-gradient(135deg,#336699,#0f3460);
    color:#fff; font-size:16px; font-weight:800;
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 12px rgba(51,102,153,.4);
}
.hiw-card__icon {
    width:72px; height:72px; border-radius:50%;
    margin:12px auto 18px;
    display:flex; align-items:center; justify-content:center;
    font-size:28px;
}
.hiw-card__title { font-size:17px; font-weight:700; color:#1a1a2e; margin-bottom:10px; }
.hiw-card__desc  { font-size:13.5px; color:#666; line-height:1.7; }

/* ── Values ── */
.values-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
@media(max-width:991px){ .values-grid{grid-template-columns:repeat(2,1fr);} }
@media(max-width:575px){ .values-grid{grid-template-columns:1fr;} }
.value-card {
    background:#fff;
    border-radius:14px;
    padding:24px;
    border:1px solid #f0f0f0;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    transition:transform .2s,box-shadow .2s;
}
.value-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.09); }
.value-card__icon { font-size:28px; margin-bottom:12px; }
.value-card__title { font-size:14px; font-weight:700; color:#1a1a2e; margin-bottom:6px; }
.value-card__desc  { font-size:13px; color:#777; line-height:1.6; }

/* ── CTA band ── */
.about-cta {
    background:linear-gradient(135deg,#0f3460,#1a1a2e);
    border-radius:20px;
    padding:56px 40px;
    text-align:center;
    margin-bottom:56px;
}
.about-cta h2 { color:#fff; font-size:30px; font-weight:800; margin:0 0 12px; }
.about-cta p  { color:rgba(255,255,255,.7); font-size:15px; margin:0 0 28px; }
.about-cta__btns { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
.cta-btn-primary {
    background:#fbbf24; color:#1a1a2e;
    border:none; border-radius:10px; padding:13px 30px;
    font-size:15px; font-weight:700; cursor:pointer;
    text-decoration:none; display:inline-block;
    transition:all .2s;
}
.cta-btn-primary:hover { background:#f59e0b; color:#1a1a2e; transform:translateY(-2px); box-shadow:0 8px 20px rgba(245,158,11,.4); }
.cta-btn-ghost {
    background:rgba(255,255,255,.1); color:#fff;
    border:1.5px solid rgba(255,255,255,.3); border-radius:10px; padding:13px 30px;
    font-size:15px; font-weight:700;
    text-decoration:none; display:inline-block;
    transition:all .2s;
}
.cta-btn-ghost:hover { background:rgba(255,255,255,.18); color:#fff; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="about-hero">
    <div class="container">
        <span class="about-hero__tag"><i class="fas fa-star" style="margin-right:6px;"></i> Our Story</span>
        <h1>The Marketplace Built<br>for Everyone</h1>
        <p>We connect buyers and sellers across the globe with a trusted, transparent, and feature-rich marketplace where great deals happen every day.</p>
    </div>
</div>

<div class="container">

    {{-- Stats row --}}
    <div class="about-stats">
        <div class="about-stat">
            <div class="about-stat__num">{{ number_format($usersCount) }}+</div>
            <div class="about-stat__label">Registered Users</div>
        </div>
        <div class="about-stat">
            <div class="about-stat__num">{{ number_format($productsCount) }}+</div>
            <div class="about-stat__label">Products Listed</div>
        </div>
        <div class="about-stat">
            <div class="about-stat__num">{{ number_format($vendorsCount) }}+</div>
            <div class="about-stat__label">Verified Vendors</div>
        </div>
    </div>

    {{-- Mission --}}
    <div class="about-section">
        <div class="mission-grid">
            <div class="mission-art">🛒</div>
            <div class="mission-body">
                <div class="about-section-title">Our Mission</div>
                <p>We believe commerce should be <strong>open, fair, and accessible</strong> to everyone — whether you're a first-time buyer looking for a great deal, a small business wanting to reach new customers, or a collector hunting for that rare find.</p>
                <p>Our platform brings buyers and sellers together with tools that make listing, bidding, and buying effortless — backed by seller verification, secure payments, and transparent policies.</p>
                <p>From everyday essentials to one-of-a-kind auction items, <strong>{{ config('app.name') }}</strong> is where real commerce happens.</p>
            </div>
        </div>
    </div>

    {{-- How it works --}}
    <div class="about-section" style="padding-top:0;">
        <div style="text-align:center;margin-bottom:48px;">
            <div class="about-section-title">How It Works</div>
            <div class="about-section-sub">Getting started takes just minutes. Here's how.</div>
        </div>
        <div class="hiw-grid">
            <div class="hiw-card">
                <div class="hiw-card__num">1</div>
                <div class="hiw-card__icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="fas fa-search"></i>
                </div>
                <div class="hiw-card__title">Browse &amp; Discover</div>
                <div class="hiw-card__desc">Explore thousands of products across hundreds of categories. Use filters, search, and auction listings to find exactly what you need at the price you want.</div>
            </div>
            <div class="hiw-card">
                <div class="hiw-card__num">2</div>
                <div class="hiw-card__icon" style="background:#dcfce7;color:#16a34a;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="hiw-card__title">Add to Cart or Bid</div>
                <div class="hiw-card__desc">Found something you love? Add it to your cart for instant purchase, or place a bid on auction items and watch the excitement unfold in real time.</div>
            </div>
            <div class="hiw-card">
                <div class="hiw-card__num">3</div>
                <div class="hiw-card__icon" style="background:#ffedd5;color:#ea580c;">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="hiw-card__title">Secure Checkout</div>
                <div class="hiw-card__desc">Complete your purchase with our safe, encrypted checkout. Multiple payment options, buyer protection, and order tracking keep every transaction worry-free.</div>
            </div>
        </div>
    </div>

    {{-- Values --}}
    <div class="about-section" style="padding-top:0;">
        <div style="text-align:center;margin-bottom:40px;">
            <div class="about-section-title">Our Values</div>
            <div class="about-section-sub">The principles we build everything around.</div>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-card__icon">🛡️</div>
                <div class="value-card__title">Trust &amp; Safety</div>
                <div class="value-card__desc">Every vendor is verified. Every transaction is protected. Your security is our top priority.</div>
            </div>
            <div class="value-card">
                <div class="value-card__icon">⚡</div>
                <div class="value-card__title">Speed &amp; Simplicity</div>
                <div class="value-card__desc">List in minutes, buy in seconds. We remove friction so commerce flows naturally.</div>
            </div>
            <div class="value-card">
                <div class="value-card__icon">🤝</div>
                <div class="value-card__title">Fairness</div>
                <div class="value-card__desc">Transparent fees, honest policies, and equal opportunity for every seller — big or small.</div>
            </div>
            <div class="value-card">
                <div class="value-card__icon">🌍</div>
                <div class="value-card__title">Community</div>
                <div class="value-card__desc">We're more than a marketplace — we're a community of buyers and sellers who grow together.</div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="about-cta">
        <h2>Ready to Get Started?</h2>
        <p>Join thousands of buyers and sellers already on {{ config('app.name') }}.</p>
        <div class="about-cta__btns">
            <a href="{{ route('register') }}" class="cta-btn-primary">
                <i class="fas fa-user-plus" style="margin-right:8px;"></i> Create Free Account
            </a>
            <a href="{{ route('home') }}" class="cta-btn-ghost">
                <i class="fas fa-search" style="margin-right:8px;"></i> Browse Products
            </a>
            <a href="{{ route('contact') }}" class="cta-btn-ghost">
                <i class="fas fa-envelope" style="margin-right:8px;"></i> Contact Us
            </a>
        </div>
    </div>

</div>

@endsection
