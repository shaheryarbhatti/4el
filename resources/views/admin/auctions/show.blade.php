{{-- Admin auction detail — rich (bid history + live countdown) --}}
@extends('admin.layouts.app')
@section('title', 'Auction: '.$auction->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.auctions.index') }}">Auctions</a></li>
    <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($auction->name, 30) }}</li>
@endsection

@push('styles')<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-auctions.css') }}">@endpush

@section('content')
@php
    $cur = setting('currency_symbol', '$');
    $img = $auction->primaryImage;
    $src = $img ? (str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path)) : null;
    $current = $auction->current_bid ?? $auction->starting_bid;
    $isLive  = !$auction->auction_closed_at && $auction->auction_ends_at && $auction->auction_ends_at->isFuture();
    $st      = $auction->auction_closed_at ? ($auction->auction_winner_id ? 'sold' : 'unsold') : ($isLive ? 'live' : 'ended');
    $stLabel = ['live'=>'Live','ended'=>'Ended','sold'=>'Sold','unsold'=>'Unsold'][$st];
@endphp

{{-- Hero --}}
<div class="aud-hero">
    @if ($src)<img src="{{ $src }}" class="aud-hero__img" alt="">@else<div class="aud-hero__img-ph"><i class="bx bx-package"></i></div>@endif
    <div class="aud-hero__info">
        <h1 class="aud-hero__name">{{ $auction->name }}</h1>
        <div class="aud-hero__sub">
            <i class="bx bx-store-alt"></i> {{ $auction->vendor?->vendorProfile?->store_name ?? $auction->vendor?->name ?? 'Platform' }}
            · <span class="au-badge au-badge--{{ $st }}">{{ $stLabel }}</span>
        </div>
        @if ($isLive)
            <div class="aud-cd au-countdown" data-ends="{{ $auction->auction_ends_at->toIso8601String() }}"><i class="bx bx-time"></i> …</div>
        @endif
    </div>
    <div class="aud-hero__right">
        <div class="aud-hero__curlbl">Current Bid</div>
        <div class="aud-hero__cur">{{ $cur }}{{ number_format((float) $current, 2) }}</div>
    </div>
</div>

<div class="aud-grid">
    {{-- LEFT --}}
    <div>
        {{-- Mini stats --}}
        <div class="aud-mini">
            <div class="aud-minicard"><div class="aud-minicard__lbl">Starting Bid</div><div class="aud-minicard__val">{{ $cur }}{{ number_format((float) $auction->starting_bid, 2) }}</div></div>
            <div class="aud-minicard"><div class="aud-minicard__lbl">Current Bid</div><div class="aud-minicard__val">{{ $cur }}{{ number_format((float) $current, 2) }}</div></div>
            <div class="aud-minicard"><div class="aud-minicard__lbl">Reserve</div><div class="aud-minicard__val">{{ !is_null($auction->reserve_price) ? $cur.number_format((float) $auction->reserve_price, 2) : '—' }}</div></div>
            <div class="aud-minicard"><div class="aud-minicard__lbl">Total Bids</div><div class="aud-minicard__val">{{ $bids->count() }}</div></div>
        </div>

        {{-- Bid history --}}
        <div class="aud-card">
            <div class="aud-card__head"><i class="bx bx-list-ul"></i> Bid History ({{ $bids->count() }})</div>
            <div class="aud-card__body">
                @forelse ($bids as $bid)
                    <div class="aud-bidrow {{ $bid->is_winning ? 'is-winning' : '' }}">
                        <div class="aud-bidder">{{ mb_strtoupper(mb_substr($bid->user?->name ?? '?', 0, 1)) }}</div>
                        <div>
                            <div class="aud-bidrow__name">{{ $bid->user?->name ?? 'Unknown' }}
                                @if ($bid->is_winning)<i class="bx bx-crown aud-bidrow__crown" title="Winning bid"></i>@endif
                            </div>
                            <div class="aud-bidrow__time">{{ $bid->created_at->format('d M Y, g:i A') }}</div>
                        </div>
                        <div class="aud-bidrow__amt">{{ $cur }}{{ number_format((float) $bid->amount, 2) }}</div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4"><i class="bx bx-purchase-tag" style="font-size:30px;display:block;margin-bottom:6px"></i>No bids placed yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Result / winner --}}
        <div class="aud-card">
            <div class="aud-card__head"><i class="bx bx-trophy"></i> Result</div>
            <div class="aud-card__body">
                @if ($winner)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="aud-bidder" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)"><i class="bx bx-crown"></i></div>
                        <div><div class="aud-bidrow__name">{{ $winner->name }}</div><div class="aud-bidrow__time">{{ $winner->email }}</div></div>
                    </div>
                    <div class="au-badge au-badge--sold">Won at {{ $cur }}{{ number_format((float) $current, 2) }}</div>
                @elseif ($auction->auction_closed_at)
                    <div class="au-badge au-badge--unsold">Ended without a winner{{ !is_null($auction->reserve_price) ? ' (reserve not met)' : '' }}</div>
                @elseif ($isLive)
                    <div class="text-muted">Auction is still live — highest bidder wins when it ends.</div>
                @else
                    <div class="au-badge au-badge--ended">Ended — awaiting close</div>
                @endif
            </div>
        </div>

        {{-- Details --}}
        <div class="aud-card">
            <div class="aud-card__head"><i class="bx bx-info-circle"></i> Details</div>
            <div class="aud-card__body">
                <div class="aud-bidrow"><div class="aud-bidrow__name">Category</div><div class="aud-bidrow__amt" style="color:var(--au-ink)">{{ $auction->category?->name ?? '—' }}</div></div>
                <div class="aud-bidrow"><div class="aud-bidrow__name">Condition</div><div class="aud-bidrow__amt" style="color:var(--au-ink)">{{ ucfirst($auction->condition) }}</div></div>
                <div class="aud-bidrow"><div class="aud-bidrow__name">Ends</div><div class="aud-bidrow__amt" style="color:var(--au-ink);font-weight:600">{{ $auction->auction_ends_at?->format('d M Y, g:i A') ?? '—' }}</div></div>
                <a href="{{ route('admin.auctions.index') }}" class="btn btn-light w-100 mt-3">Back to list</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    function fmt(ms){ if(ms<=0)return 'Ended'; var s=Math.floor(ms/1000),d=Math.floor(s/86400);s%=86400;var h=Math.floor(s/3600);s%=3600;var m=Math.floor(s/60);s%=60;var p=n=>(n<10?'0':'')+n;return d>0?d+'d '+p(h)+'h '+p(m)+'m':p(h)+':'+p(m)+':'+p(s);}
    function tick(){ $('.au-countdown').each(function(){ var e=new Date($(this).data('ends')).getTime(); $(this).html('<i class="bx bx-time"></i> '+fmt(e-Date.now())); }); }
    tick(); setInterval(tick, 1000);
});
</script>
@endpush
