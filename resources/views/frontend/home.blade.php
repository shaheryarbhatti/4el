@extends('frontend.layouts.app')

@push('styles')
<style>
/* ============================================================
   HOME PAGE  — marketplace-style layout
   ============================================================ */

/* ============================================================
   HERO SLIDER
   ============================================================ */
.eb-slider { position: relative; overflow: hidden; line-height: 1; margin: 12px 24px; border-radius: 16px; }
.eb-slider__track { display: flex; transition: transform .5s cubic-bezier(.4,0,.2,1); will-change: transform; }
.eb-slide {
    flex: 0 0 100%; min-width: 0; min-height: 280px;
    display: flex; align-items: center;
}
.eb-slide__inner {
    display: flex; align-items: center; justify-content: space-between;
    padding: 40px 56px; gap: 24px;
}
.eb-slide__text { flex: 0 0 auto; max-width: 360px; color: #191919; }
.eb-slide__eyebrow {
    font-size: 12px; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; opacity: .75; margin-bottom: 10px;
}
.eb-slide__heading {
    font-size: 34px; font-weight: 700; line-height: 1.2; margin: 0 0 10px;
    color: inherit;
}
.eb-slide__heading strong { font-weight: 900; }
.eb-slide__sub { font-size: 14px; opacity: .8; margin-bottom: 22px; }
.eb-slide__btn {
    display: inline-flex; align-items: center; gap: 7px;
    background: #191919; color: #fff; font-size: 14px; font-weight: 700;
    padding: 11px 24px; border-radius: 24px; text-decoration: none;
    box-shadow: 0 4px 16px rgba(0,0,0,.2); transition: box-shadow .15s, transform .12s;
}
.eb-slide__btn:hover { box-shadow: 0 8px 24px rgba(0,0,0,.28); transform: translateY(-1px); color: #fff; }
.eb-slide__btn--white {
    background: #fff; color: #191919;
}
.eb-slide__btn--white:hover { color: #191919; }
.eb-slide__btn i { font-size: 11px; }

.eb-slide__imgs {
    display: flex; align-items: flex-end; gap: 16px; flex: 1;
    justify-content: flex-end;
}
.eb-slide__img-item {
    display: flex; flex-direction: column; align-items: center; gap: 7px;
    text-decoration: none; color: #333; font-size: 12.5px; font-weight: 600;
    transition: transform .15s;
}
.eb-slide__img-item:hover { transform: translateY(-4px); }
.eb-slide__img-item img {
    width: 130px; height: 130px; object-fit: cover; border-radius: 14px;
    background: rgba(255,255,255,.3); box-shadow: 0 8px 24px rgba(0,0,0,.12);
}

/* Controls */
.eb-slider__prev,
.eb-slider__next {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,.9); border: 1px solid #e5e5e5;
    border-radius: 50%; width: 38px; height: 38px; font-size: 22px; line-height: 1;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #333; box-shadow: 0 2px 10px rgba(0,0,0,.1);
    transition: background .15s; z-index: 10; margin-top: -18px;
}
.eb-slider__prev { left: 12px; }
.eb-slider__next { right: 12px; }
.eb-slider__prev:hover, .eb-slider__next:hover { background: #fff; }
.eb-slider__pause {
    position: absolute; bottom: 32px; right: 14px;
    background: rgba(255,255,255,.8); border: 1px solid #e5e5e5; border-radius: 4px;
    font-size: 12px; padding: 3px 8px; cursor: pointer; color: #555;
    z-index: 10;
}
.eb-slider__pause:hover { background: #fff; }
.eb-slider__dots {
    display: flex; justify-content: center; align-items: center; gap: 6px;
    padding: 10px 0 6px; background: transparent; position: absolute;
    bottom: 6px; left: 50%; transform: translateX(-50%);
}
.eb-slider__dots span {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(0,0,0,.25); display: inline-block; cursor: pointer;
    transition: background .2s, transform .2s;
}
.eb-slider__dots span.on { background: #333; transform: scale(1.3); }

@media (max-width: 767px) {
    .eb-slide__imgs { display: none; }
    .eb-slide__text { max-width: 100%; }
    .eb-slide__heading { font-size: 24px; }
    .eb-slide { min-height: 200px; }
    .eb-slide__inner { padding: 28px 52px; }
}

/* Remove old hp-cats since categories moved to header */
/* --- Category tabs nav bar --- */
.hp-cats {
    background: #fff;
    border-bottom: 1px solid #e5e5e5;
    position: sticky;
    top: 0;
    z-index: 200;
}
.hp-cats__inner {
    display: flex;
    align-items: center;
    overflow-x: auto;
    scrollbar-width: none;
    gap: 0;
    padding: 0 8px;
}
.hp-cats__inner::-webkit-scrollbar { display: none; }
.hp-cats__link {
    white-space: nowrap;
    padding: 13px 14px;
    font-size: 13px;
    font-weight: 500;
    color: #333;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: color .15s, border-color .15s;
    flex: 0 0 auto;
}
.hp-cats__link:hover { color: #3665f3; border-bottom-color: #3665f3; }
.hp-cats__link.active { color: #3665f3; border-bottom-color: #3665f3; font-weight: 600; }

/* --- Hero banner --- */
.hp-hero {
    background: #00a39e;
    padding: 0;
    overflow: hidden;
    margin-bottom: 0;
}
.hp-hero__inner {
    display: flex;
    align-items: stretch;
    min-height: 260px;
}
.hp-hero__text {
    flex: 1;
    padding: 40px 48px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: #fff;
}
.hp-hero__eyebrow {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    opacity: .8;
    margin-bottom: 10px;
}
.hp-hero__heading {
    font-size: 36px;
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: 10px;
    color: #fff;
}
.hp-hero__sub {
    font-size: 14px;
    opacity: .85;
    margin-bottom: 22px;
}
.hp-hero__btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    color: #333;
    font-weight: 700;
    font-size: 13.5px;
    padding: 10px 22px;
    border-radius: 24px;
    text-decoration: none;
    align-self: flex-start;
    transition: box-shadow .15s;
    box-shadow: 0 4px 14px rgba(0,0,0,.15);
}
.hp-hero__btn:hover { box-shadow: 0 8px 22px rgba(0,0,0,.22); color: #222; }
.hp-hero__imgs {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    padding: 20px 40px 0;
    overflow: hidden;
}
.hp-hero__img-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    opacity: .9;
    transition: opacity .15s;
}
.hp-hero__img-item:hover { opacity: 1; color: #fff; }
.hp-hero__img-item img {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 12px;
    background: rgba(255,255,255,.15);
}

/* --- Dots slider indicator --- */
.hp-hero-dots {
    display: flex;
    justify-content: center;
    gap: 6px;
    padding: 10px 0 6px;
    background: #00a39e;
}
.hp-hero-dots span {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,.4);
    display: inline-block;
}
.hp-hero-dots span.on { background: #fff; }

/* --- Section title eBay style --- */
.hp-section { padding: 22px 0 28px; }
.hp-section__head {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin-bottom: 16px;
}
.hp-section__title {
    font-size: 22px;
    font-weight: 700;
    color: #191919;
    margin: 0;
}
.hp-section__see-all {
    font-size: 13px;
    color: #3665f3;
    font-weight: 600;
    text-decoration: none;
}
.hp-section__see-all:hover { text-decoration: underline; }
.hp-section__sub { font-size: 13px; color: #767676; margin-top: 2px; }

/* --- Category quick-links grid (eBay icon style) --- */
/* Category slider */
.hp-ql-wrap { position: relative; }
.hp-ql-track {
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 12px;
    padding: 6px 2px;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.hp-ql-track::-webkit-scrollbar { display: none; }
.hp-ql-btn {
    position: absolute;
    top: 50%; transform: translateY(-50%);
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 2px 8px rgba(0,0,0,.14);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #333;
    cursor: pointer; z-index: 2;
    transition: box-shadow .15s;
    line-height: 1;
}
.hp-ql-btn:hover { box-shadow: 0 4px 16px rgba(0,0,0,.2); }
.hp-ql-btn--prev { left: -18px; }
.hp-ql-btn--next { right: -18px; }
.hp-ql__item {
    flex: 1 1 0;
    min-width: 90px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #191919;
    background: #f7f7f7;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 16px 10px 14px;
    transition: box-shadow .15s, border-color .15s;
    text-align: center;
}
.hp-ql__item:hover { border-color: #3665f3; box-shadow: 0 4px 14px rgba(0,0,0,.08); color: #3665f3; }
.hp-ql__icon {
    width: 56px; height: 56px;
    border-radius: 50%;
    overflow: hidden;
    background: #e5e5e5;
    flex: 0 0 auto;
}
.hp-ql__icon img { width: 100%; height: 100%; object-fit: cover; }
.hp-ql__icon-bi {
    width: 56px; height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: #e8eeff;
    color: #3665f3;
    transition: background .15s, color .15s;
}
.hp-ql__item:hover .hp-ql__icon-bi { background: #3665f3; color: #fff; }
.hp-ql__name { font-size: 12px; font-weight: 600; line-height: 1.3; }
.hp-ql__count { font-size: 11px; color: #767676; }

/* Special Offers — Porto CSS handles product cards; only countdown needs custom styles */
.deal-products-section { padding: 28px 0 0; }
.eb-countdown {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
}
.eb-cd-unit {
    background: rgba(0,0,0,.5);
    border-radius: 3px;
    padding: 2px 6px;
    min-width: 28px;
    text-align: center;
    font-variant-numeric: tabular-nums;
}
.eb-cd-sep { opacity: .7; }

/* --- Full-width promo banners (eBay style) --- */
.hp-promo {
    margin: 8px 0;
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    min-height: 260px;
    display: flex;
    align-items: center;
}
.hp-promo--auction {
    background: linear-gradient(135deg, #2c1810 0%, #5c3520 60%, #8b5c3a 100%);
}
.hp-promo--sell {
    background: linear-gradient(135deg, #c45000 0%, #e8780a 60%, #f59e0b 100%);
}
.hp-promo__text {
    position: relative;
    z-index: 1;
    padding: 40px 48px;
    color: #fff;
    flex: 0 0 55%;
}
.hp-promo__label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    opacity: .7;
    margin-bottom: 10px;
}
.hp-promo__title {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 10px;
    color: #fff;
}
.hp-promo__sub { font-size: 14px; opacity: .8; margin-bottom: 22px; }
.hp-promo__btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    color: #333;
    font-weight: 700;
    font-size: 13px;
    padding: 10px 22px;
    border-radius: 24px;
    text-decoration: none;
    transition: box-shadow .15s;
    box-shadow: 0 4px 14px rgba(0,0,0,.2);
}
.hp-promo__btn:hover { box-shadow: 0 8px 22px rgba(0,0,0,.28); color: #111; }
.hp-promo__img {
    position: absolute;
    right: 0;
    bottom: 0;
    top: 0;
    width: 48%;
    overflow: hidden;
}
.hp-promo__img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: .55;
}
.hp-promo__dots {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,.06) 1px, transparent 1px);
    background-size: 22px 22px;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 767px) {
    .hp-hero__imgs { display: none; }
    .hp-hero__text { padding: 28px 24px; }
    .hp-hero__heading { font-size: 26px; }
    .hp-promo__img { display: none; }
    .hp-promo__text { flex: 1; padding: 28px 24px; }
    .hp-promo__title { font-size: 24px; }
    .hp-ql-btn--prev { left: -10px; }
    .hp-ql-btn--next { right: -10px; }
}
@media (max-width: 480px) {
    .hp-deals { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('content')

@foreach($sections as $section)
@switch($section->key)

{{-- ============================================================ HERO SLIDER ===== --}}
@case('hero_slider')
@if($heroSlides->isNotEmpty())
<div class="eb-slider" style="position:relative;overflow:hidden;">
    <div class="eb-slider__track" id="ebSlider">

        @foreach($heroSlides as $slide)
        @php
            $textColor  = $slide->text_color === 'light' ? '#fff' : '#191919';
            $subOpacity = $slide->text_color === 'light' ? 'rgba(255,255,255,.85)' : 'rgba(0,0,0,.7)';
            $eyeOpacity = $slide->text_color === 'light' ? 'rgba(255,255,255,.75)' : 'rgba(0,0,0,.55)';
            $btnClass   = $slide->button_style === 'white' ? 'eb-slide__btn eb-slide__btn--white' : 'eb-slide__btn';
            $imgItems   = $slide->imageItems();
        @endphp
        <div class="eb-slide" style="background: {{ $slide->bgGradient() }};">
            <div class="container eb-slide__inner">
                <div class="eb-slide__text" style="color:{{ $textColor }}">
                    @if($slide->eyebrow)
                        <div class="eb-slide__eyebrow" style="color:{{ $eyeOpacity }}">{{ $slide->eyebrow }}</div>
                    @endif
                    <h2 class="eb-slide__heading" style="color:{{ $textColor }}">{{ $slide->title }}</h2>
                    @if($slide->subtitle)
                        <p class="eb-slide__sub" style="color:{{ $subOpacity }}">{{ $slide->subtitle }}</p>
                    @endif
                    @if($slide->button_text)
                        <a href="{{ $slide->button_url ?? '#' }}" class="{{ $btnClass }}">
                            {{ $slide->button_text }} <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
                @if(count($imgItems))
                <div class="eb-slide__imgs">
                    @foreach($imgItems as $item)
                    <a href="{{ $item['url'] }}" class="eb-slide__img-item">
                        <img src="{{ asset('storage/'.$item['path']) }}" alt="{{ $item['label'] }}">
                        <span style="color:{{ $textColor }}">{{ $item['label'] }} &rsaquo;</span>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach

    </div>

    {{-- Controls --}}
    <button class="eb-slider__prev" id="ebPrev" aria-label="Previous">&#8249;</button>
    <button class="eb-slider__next" id="ebNext" aria-label="Next">&#8250;</button>
    <button class="eb-slider__pause" id="ebPause" aria-label="Pause">&#9646;&#9646;</button>

    {{-- Dots --}}
    <div class="eb-slider__dots" id="ebDots">
        @foreach($heroSlides as $i => $slide)
            <span class="{{ $i === 0 ? 'on' : '' }}"></span>
        @endforeach
    </div>
</div>
@endif
@break

{{-- ============================================================ CATEGORIES ===== --}}
@case('categories')
<div class="container">

    {{-- ===== SHOP BY CATEGORY ===== --}}
    <div class="hp-section">
        <div class="hp-section__head">
            <div>
                <h2 class="hp-section__title">Shop by category</h2>
                <p class="hp-section__sub mb-0">Find exactly what you're looking for</p>
            </div>
            <a href="#" class="hp-section__see-all">See all categories</a>
        </div>
        <div class="hp-ql-wrap">
            <button class="hp-ql-btn hp-ql-btn--prev" id="catPrev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
            <div class="hp-ql-track" id="catTrack">
                @forelse ($categories as $cat)
                    @php
                        $catIcon = $cat->icon ?: 'bi-tag';
                        $isBi = str_starts_with($catIcon, 'bi-');
                        $iconClass = $isBi ? "bi {$catIcon}" : "fas {$catIcon}";
                    @endphp
                    <a href="{{ route('category.show', $cat->slug) }}" class="hp-ql__item">
                        <div class="hp-ql__icon-bi">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                        <div class="hp-ql__name">{{ $cat->name }}</div>
                        @if ($cat->products_count ?? false)
                            <div class="hp-ql__count">{{ $cat->products_count }} items</div>
                        @endif
                    </a>
                @empty
                    @foreach ([
                        ['name'=>'Electronics',       'icon'=>'bi-laptop'],
                        ['name'=>'Clothing & Shoes',  'icon'=>'bi-handbag'],
                        ['name'=>'Collectibles',      'icon'=>'bi-gem'],
                        ['name'=>'Home & Garden',     'icon'=>'bi-house'],
                        ['name'=>'Motors',            'icon'=>'bi-car-front'],
                        ['name'=>'Sporting Goods',    'icon'=>'bi-bicycle'],
                        ['name'=>'Toys',              'icon'=>'bi-controller'],
                        ['name'=>'Jewelry & Watches', 'icon'=>'bi-watch'],
                        ['name'=>'Business',          'icon'=>'bi-briefcase'],
                        ['name'=>'Books',             'icon'=>'bi-book'],
                    ] as $fc)
                        <a href="#" class="hp-ql__item">
                            <div class="hp-ql__icon-bi">
                                <i class="bi {{ $fc['icon'] }}"></i>
                            </div>
                            <div class="hp-ql__name">{{ $fc['name'] }}</div>
                        </a>
                    @endforeach
                @endforelse
            </div>
            <button class="hp-ql-btn hp-ql-btn--next" id="catNext" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

</div>{{-- /container --}}
@break

{{-- ============================================================ PROMO BANNER ===== --}}
@case('promo_banner')
@php $pBanners = $section->resolved_banners ?? []; @endphp
@if(count($pBanners) > 0)
<div class="container" style="margin-top:48px; margin-bottom:48px;">
    <div style="display:flex; flex-direction:column; gap:24px;">
        @foreach($pBanners as $banner)
        @php
            $pbColor    = $banner->text_color === 'light' ? '#fff' : '#191919';
            $pbSubOp    = $banner->text_color === 'light' ? 'rgba(255,255,255,.82)' : 'rgba(0,0,0,.62)';
            $pbBtnStyle = match($banner->button_style ?? 'white') {
                'dark'    => 'background:#222; color:#fff; border:none;',
                'outline' => 'background:transparent; color:'.($banner->text_color==='light'?'#fff':'#333').'; border:2px solid currentColor;',
                default   => 'background:#fff; color:#333; border:none;',
            };
        @endphp
        <div style="background:{{ $banner->bgGradient() }}; border-radius:16px; overflow:hidden; display:flex; min-height:380px; position:relative; box-shadow:0 8px 32px rgba(0,0,0,.15);">
            <div style="flex:1; min-width:0; padding:48px 56px; display:flex; flex-direction:column; justify-content:center; color:{{ $pbColor }}; overflow:hidden; position:relative; z-index:1;">
                @if($banner->eyebrow)
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.14em; color:{{ $pbSubOp }}; margin-bottom:14px;">
                    {{ $banner->eyebrow }}
                </div>
                @endif
                <h2 style="font-size:42px; font-weight:800; line-height:1.12; margin:0 0 14px; color:{{ $pbColor }}; max-width:520px;">
                    {{ $banner->title }}
                </h2>
                @if($banner->subtitle)
                <p style="font-size:16px; color:{{ $pbSubOp }}; margin:0 0 28px; max-width:420px; line-height:1.65;">
                    {{ $banner->subtitle }}
                </p>
                @endif
                @if($banner->button_text)
                <a href="{{ $banner->button_url ?? '#' }}"
                   style="display:inline-block; padding:14px 36px; border-radius:50px; font-size:15px; font-weight:700; text-decoration:none; {{ $pbBtnStyle }} align-self:flex-start; transition:opacity .2s;"
                   onmouseover="this.style.opacity='.82'" onmouseout="this.style.opacity='1'">
                    {{ $banner->button_text }}
                </a>
                @endif
            </div>
            @if($banner->imageUrl())
            <div style="width:45%; flex-shrink:0; overflow:hidden; position:relative;">
                <img src="{{ $banner->imageUrl() }}" alt="{{ $banner->title }}"
                     style="width:100%; height:100%; object-fit:cover; object-position:center; display:block; position:absolute; inset:0;">
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
@break

{{-- ============================================================ SPECIAL OFFERS ===== --}}
@case('special_offers')
<div class="container">
    {{-- ===== SPECIAL OFFERS ===== --}}
    <div class="deal-products-section">
        <h2 class="section-title d-flex align-items-center text-transform-none">
            <i class="icon-percent-shape"></i>Special Offers
        </h2>

        @if ($dealHero)
        @php
            $_hiPath  = $dealHero->primaryImage->path ?? null;
            $heroImg  = $_hiPath
                ? (str_starts_with($_hiPath, 'frontend-assets/') ? asset($_hiPath) : asset('storage/'.$_hiPath))
                : asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');
            $heroSale = $dealHero->effective_price;
            $heroOrig = $dealHero->price;
            $heroEnds = $dealHero->deal_ends_at;
            $heroDisc = $heroOrig > $heroSale ? round((($heroOrig - $heroSale) / $heroOrig) * 100) : 0;
            $heroStars = rand(60, 100);
        @endphp
        <div class="row">
            {{-- LEFT: big deal product --}}
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="product-default deal-product">
                    <figure>
                        <a href="{{ route('product.show', $dealHero->slug) }}">
                            <img src="{{ $heroImg }}" width="450" height="450" alt="{{ $dealHero->name }}">
                        </a>
                        @if ($heroEnds)
                        <div class="product-countdown-container custom-product-countdown">
                            <span class="product-countdown-title">offer ends in:</span>
                            <div class="eb-countdown" id="soCountdown" data-ends="{{ $heroEnds->timestamp }}">
                                <span class="eb-cd-unit" id="soD">00</span><span class="eb-cd-sep">d</span>
                                <span class="eb-cd-unit" id="soH">00</span><span class="eb-cd-sep">:</span>
                                <span class="eb-cd-unit" id="soM">00</span><span class="eb-cd-sep">:</span>
                                <span class="eb-cd-unit" id="soS">00</span>
                            </div>
                        </div>
                        @endif
                    </figure>
                    <div class="product-details">
                        <div class="category-list">
                            <a href="{{ $dealHero->category ? route('category.show', $dealHero->category->slug) : '#' }}" class="product-category">{{ $dealHero->category->name ?? 'Special Deal' }}</a>
                        </div>
                        <h3 class="product-title"><a href="{{ route('product.show', $dealHero->slug) }}">{{ $dealHero->name }}</a></h3>
                        <div class="ratings-container">
                            <div class="product-ratings">
                                <span class="ratings" style="width:{{ $heroStars }}%"></span>
                                <span class="tooltiptext tooltip-top"></span>
                            </div>
                        </div>
                        <div class="price-box">
                            @if ($heroOrig > $heroSale)
                                <del class="old-price">${{ number_format($heroOrig, 2) }}</del>
                            @endif
                            <span class="product-price">${{ number_format($heroSale, 2) }}</span>
                        </div>
                        <div class="product-action">
                            <a href="#" class="btn-icon-wish js-wish-toggle {{ wishlist_has($dealHero->id) ? 'is-active' : '' }}" title="Add to Wishlist" data-url="{{ route('wishlist.toggle', $dealHero->id) }}"><i class="icon-heart"></i></a>
                            <a href="#" class="btn btn-primary btn-icon btn-add-cart product-type-simple"
                               data-product-id="{{ $dealHero->id }}">
                                <i class="icon-shopping-cart"></i><span>ADD TO CART</span>
                            </a>
                            <a href="{{ route('product.quick-view', $dealHero) }}" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: 2×4 grid --}}
            <div class="col-md-8">
                <div class="products-with-divide">
                    <div class="row row-joined">
                        @foreach ($dealGrid as $gi => $gp)
                        @php
                            $_gPath = $gp->primaryImage->path ?? null;
                            $gImg   = $_gPath
                                ? (str_starts_with($_gPath, 'frontend-assets/') ? asset($_gPath) : asset('storage/'.$_gPath))
                                : asset('frontend-assets/images/demoes/demo36/products/product-' . (($gi % 21) + 1) . '.jpg');
                            $gSale  = $gp->effective_price;
                            $gOrig  = $gp->price;
                            $gDisc  = $gOrig > $gSale ? round((($gOrig - $gSale) / $gOrig) * 100) : 0;
                            $gStars = rand(60, 100);
                        @endphp
                        <div class="col-xl-3 col-sm-4 col-6">
                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="{{ route('product.show', $gp->slug) }}"><img src="{{ $gImg }}" width="239" height="239" alt="{{ $gp->name }}" loading="lazy"></a>
                                    <div class="label-group">
                                        @if ($gi === 0)<div class="product-label label-hot">HOT</div>@endif
                                        @if ($gDisc > 0)<div class="product-label label-sale">-{{ $gDisc }}%</div>@endif
                                    </div>
                                    <div class="btn-icon-group">
                                        <a href="#" class="btn-icon btn-add-cart product-type-simple"
                                           data-product-id="{{ $gp->id }}"><i class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="{{ route('product.quick-view', $gp) }}" class="btn-quickview" title="Quick View">Quick View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="#" class="product-category">{{ $gp->category->name ?? '' }}</a>
                                        </div>
                                        <a href="#" class="btn-icon-wish js-wish-toggle {{ wishlist_has($gp->id) ? 'is-active' : '' }}" title="Add to Wishlist" data-url="{{ route('wishlist.toggle', $gp->id) }}"><i class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title"><a href="{{ route('product.show', $gp->slug) }}">{{ $gp->name }}</a></h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:{{ $gStars }}%"></span>
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                    </div>
                                    <div class="price-box">
                                        @if ($gOrig > $gSale)<span class="old-price">${{ number_format($gOrig, 2) }}</span>@endif
                                        <span class="product-price">${{ number_format($gSale, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <p class="text-muted text-center py-5">No special offers right now. Check back soon!</p>
        @endif
    </div>

</div>{{-- /container --}}
@break

{{-- ============================================================ FEATURED PRODUCTS ===== --}}
@case('featured_products')
<div style="background:#fff; padding: 20px 0;">
<div class="container">
    <div class="heading shop-list d-flex align-items-center flex-wrap mb-0 pl-0 pr-0" style="background:#fff;">
        <h4 class="section-title text-transform-none mb-0 mr-0">Featured Products</h4>
        <a class="view-all ml-auto" href="#">View All<i class="fas fa-long-arrow-alt-right"></i></a>
    </div>

    @if ($featured->isNotEmpty())
    <div class="products-slider owl-carousel owl-theme carousel-with-bg nav-circle pb-0" data-owl-options="{
        'margin': 1,
        'navText': [ '&lt;i class=icon-left-open-big&gt;', '&lt;i class=icon-right-open-big&gt;' ],
        'dots': false,
        'nav': true,
        'loop': false,
        'responsive': {
            '0':    { 'items': 2 },
            '576':  { 'items': 3 },
            '768':  { 'items': 4 },
            '992':  { 'items': 5 },
            '1200': { 'items': 6 }
        }
    }">
        @foreach ($featured as $product)
        @php
            $_fPath = $product->primaryImage->path ?? null;
            $fImg   = $_fPath
                ? (str_starts_with($_fPath, 'frontend-assets/') ? asset($_fPath) : asset('storage/'.$_fPath))
                : null;
            $fSale = $product->effective_price;
            $fOrig = $product->price;
            $fDisc = ($fOrig > 0 && $fSale < $fOrig) ? round((($fOrig - $fSale) / $fOrig) * 100) : 0;
            $fStars = rand(60, 100);
        @endphp
        <div class="product-default inner-quickview inner-icon">
            <figure>
                <a href="{{ route('product.show', $product->slug) }}">
                    @if ($fImg)
                        <img src="{{ $fImg }}" width="239" height="239" alt="{{ $product->name }}">
                    @else
                        <img src="/frontend-assets/images/demoes/demo36/products/product-1.jpg" width="239" height="239" alt="{{ $product->name }}">
                    @endif
                </a>
                <div class="btn-icon-group">
                    <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"
                       data-product-id="{{ $product->id }}">
                        <i class="icon-shopping-cart"></i>
                    </a>
                </div>
                <a href="{{ route('product.quick-view', $product) }}" class="btn-quickview" title="Quick View">Quick View</a>
            </figure>
            <div class="product-details">
                <div class="category-wrap">
                    <div class="category-list">
                        <a href="#" class="product-category">{{ $product->category->name ?? 'Category' }}</a>
                    </div>
                    <a href="#" title="Add to Wishlist" class="btn-icon-wish js-wish-toggle {{ wishlist_has($product->id) ? 'is-active' : '' }}" data-url="{{ route('wishlist.toggle', $product->id) }}"><i class="icon-heart"></i></a>
                </div>
                <h3 class="product-title">
                    <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                </h3>
                <div class="ratings-container">
                    <div class="product-ratings">
                        <span class="ratings" style="width:{{ $fStars }}%"></span>
                        <span class="tooltiptext tooltip-top"></span>
                    </div>
                </div>
                <div class="price-box">
                    @if ($fDisc > 0)
                        <span class="old-price">${{ number_format($fOrig, 2) }}</span>
                        <span class="product-price">${{ number_format($fSale, 2) }}</span>
                    @else
                        <span class="product-price">${{ number_format($fSale, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="fas fa-store fa-2x mb-3 d-block" style="color:#e5e5e5"></i>
        No featured products yet.
    </div>
    @endif
</div>{{-- /container --}}
</div>{{-- /featured white bg --}}
@break

@endswitch
@endforeach

@endsection

@push('scripts')
<script>
(function () {
    var track   = document.getElementById('ebSlider');
    if (!track) return;
    var slides  = track.children.length;
    var dots    = document.querySelectorAll('#ebDots span');
    var current = 0;
    var paused  = false;
    var timer;

    function goTo(n) {
        current = (n + slides) % slides;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach(function (d, i) {
            d.classList.toggle('on', i === current);
        });
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    function startAuto() {
        clearInterval(timer);
        if (!paused) timer = setInterval(next, 5000);
    }

    document.getElementById('ebNext').onclick = function () { next(); startAuto(); };
    document.getElementById('ebPrev').onclick = function () { prev(); startAuto(); };

    var pauseBtn = document.getElementById('ebPause');
    pauseBtn.onclick = function () {
        paused = !paused;
        pauseBtn.innerHTML = paused ? '&#9654;' : '&#9646;&#9646;';
        startAuto();
    };

    dots.forEach(function (d, i) {
        d.onclick = function () { goTo(i); startAuto(); };
    });

    startAuto();
})();

// Special Offers countdown
(function () {
    var el = document.getElementById('soCountdown');
    if (!el) return;
    var ends = parseInt(el.dataset.ends, 10) * 1000;
    var pad = function(n){ return String(n).padStart(2,'0'); };
    function tick() {
        var diff = ends - Date.now();
        if (diff < 0) diff = 0;
        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        document.getElementById('soD').textContent = pad(d);
        document.getElementById('soH').textContent = pad(h);
        document.getElementById('soM').textContent = pad(m);
        document.getElementById('soS').textContent = pad(s);
    }
    tick();
    setInterval(tick, 1000);
})();

// Category slider
(function () {
    var track = document.getElementById('catTrack');
    if (!track) return;
    var scrollAmt = 500;
    document.getElementById('catPrev').addEventListener('click', function () {
        track.scrollBy({ left: -scrollAmt, behavior: 'smooth' });
    });
    document.getElementById('catNext').addEventListener('click', function () {
        track.scrollBy({ left: scrollAmt, behavior: 'smooth' });
    });
})();


</script>
@endpush
