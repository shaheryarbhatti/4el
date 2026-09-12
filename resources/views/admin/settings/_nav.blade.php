{{--
    Settings — shared sidebar navigation + layout wrapper.
    Usage in each settings page:
        @include('admin.settings._nav')        ← opens .st-layout
        ... form content ...
        @include('admin.settings._nav_end')    ← closes .st-layout
--}}

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   Premium Settings Layout
═══════════════════════════════════════════════════════════════ */

/* Page header */
.st-page-header {
    background: linear-gradient(135deg, #2d3748 0%, #1a202c 50%, #2d3748 100%);
    border-radius: 16px; padding: 28px 32px; margin-bottom: 24px;
    display: flex; align-items: center; gap: 20px; position: relative; overflow: hidden;
}
.st-page-header::before {
    content: ''; position: absolute; top: -40px; right: -40px;
    width: 200px; height: 200px; border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.st-page-header::after {
    content: ''; position: absolute; bottom: -60px; right: 80px;
    width: 140px; height: 140px; border-radius: 50%;
    background: rgba(255,255,255,.03);
}
.st-ph-icon {
    width: 56px; height: 56px; border-radius: 14px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #fff; position: relative; z-index: 1;
}
.st-ph-text { position: relative; z-index: 1; }
.st-ph-text h4 { color: #fff; font-size: 20px; font-weight: 700; margin: 0 0 4px; }
.st-ph-text p  { color: rgba(255,255,255,.6); font-size: 13px; margin: 0; }
.st-ph-badge {
    margin-left: auto; position: relative; z-index: 1;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.8); border-radius: 20px; padding: 6px 14px;
    font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px;
}

/* Layout */
.st-layout { display: flex; gap: 24px; align-items: flex-start; }

/* Sidebar */
.st-sidebar {
    flex: 0 0 240px; background: #fff; border-radius: 16px;
    border: 1px solid #e9ecef; overflow: hidden; position: sticky; top: 80px;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
}
.st-sidebar-head {
    padding: 16px 18px 12px;
    background: linear-gradient(135deg, #f8f9ff, #f0f4ff);
    border-bottom: 1px solid #e9ecef;
}
.st-sidebar-head span {
    font-size: 10px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .1em; color: #6366f1;
}
.st-nav-item {
    display: flex; align-items: center; gap: 12px; padding: 12px 18px;
    text-decoration: none; border-left: 3px solid transparent;
    transition: all .15s; border-bottom: 1px solid #f3f4f6; position: relative;
}
.st-nav-item:last-child { border-bottom: none; }
.st-nav-item:hover { background: #f8f9ff; border-left-color: #c7d2fe; text-decoration: none; }
.st-nav-item.active { background: #f0f4ff; border-left-color: #6366f1; }
.st-nav-icon {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
    background: #f3f4f6; color: #9ca3af; transition: all .15s;
}
.st-nav-item.active .st-nav-icon { color: #fff; }
.st-nav-text-wrap { flex: 1; min-width: 0; }
.st-nav-label { font-size: 13px; font-weight: 600; color: #374151; line-height: 1.2; }
.st-nav-item.active .st-nav-label { color: #4338ca; }
.st-nav-desc { font-size: 11px; color: #9ca3af; margin-top: 1px; }
.st-nav-arrow { font-size: 10px; color: #d1d5db; flex-shrink: 0; }
.st-nav-item.active .st-nav-arrow { color: #6366f1; }

/* Main content area */
.st-main { flex: 1; min-width: 0; }

/* Section cards */
.st-card {
    background: #fff; border: 1px solid #e9ecef; border-radius: 16px;
    overflow: hidden; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.st-card-header {
    padding: 0; display: flex; align-items: stretch; border-bottom: 1px solid #f0f0f0;
}
.st-card-header-stripe {
    width: 5px; flex-shrink: 0; border-radius: 0;
}
.st-card-header-inner {
    display: flex; align-items: center; gap: 14px; padding: 16px 20px; flex: 1;
}
.st-card-header-icon {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 17px; color: #fff;
}
.st-card-header-text h6 { font-size: 14px; font-weight: 700; color: #111827; margin: 0 0 2px; }
.st-card-header-text p  { font-size: 12px; color: #6b7280; margin: 0; }
.st-card-body { padding: 24px; }

/* Form controls */
.st-field { margin-bottom: 20px; }
.st-label {
    font-size: 12px; font-weight: 700; color: #374151;
    text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; display: block;
}
.st-label .req { color: #ef4444; }
.st-label .opt { font-size: 10px; color: #9ca3af; font-weight: 500;
    text-transform: none; letter-spacing: 0; margin-left: 4px; }
.st-hint { font-size: 11.5px; color: #9ca3af; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
.st-hint i { font-size: 12px; color: #c4b5fd; }

.st-input-wrap { position: relative; }
.st-input-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #9ca3af; font-size: 15px; pointer-events: none; z-index: 2;
}
.st-input-wrap .form-control,
.st-input-wrap .form-select { padding-left: 38px; }

.form-control, .form-select {
    border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 13.5px;
    padding: 10px 14px; transition: border-color .15s, box-shadow .15s; color: #111827;
}
.form-control:focus, .form-select:focus {
    border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); outline: none;
}
.form-control::placeholder { color: #d1d5db; }

/* Toggle row */
.st-toggle-row {
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    padding: 16px 0; border-bottom: 1px solid #f9fafb;
}
.st-toggle-row:last-child { border-bottom: none; padding-bottom: 0; }
.st-toggle-info h6 { font-size: 13.5px; font-weight: 700; color: #111827; margin: 0 0 3px; }
.st-toggle-info p  { font-size: 12px; color: #6b7280; margin: 0; max-width: 480px; line-height: 1.5; }

/* Save button */
.st-save-bar {
    background: linear-gradient(135deg, #f8f9ff, #f0f4ff);
    border: 1px solid #e0e7ff; border-radius: 12px; padding: 16px 20px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    margin-top: 4px;
}
.st-save-bar-text { font-size: 13px; color: #4b5563; display: flex; align-items: center; gap: 8px; }
.st-save-bar-text i { color: #6366f1; font-size: 15px; }
.st-btn-save {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff; border: none; border-radius: 10px; padding: 11px 24px;
    font-size: 13.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 14px rgba(99,102,241,.35); transition: all .2s;
}
.st-btn-save:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(99,102,241,.4); }

/* Success flash */
.st-flash {
    display: flex; align-items: center; gap: 12px; padding: 14px 20px;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #86efac;
    border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; color: #166534;
}
.st-flash i { font-size: 18px; color: #22c55e; }

/* File upload */
.st-file-box {
    border: 2px dashed #e5e7eb; border-radius: 12px; padding: 20px;
    text-align: center; background: #fafafa; cursor: pointer;
    transition: border-color .15s, background .15s; position: relative;
}
.st-file-box:hover { border-color: #a5b4fc; background: #f5f3ff; }
.st-file-box input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.st-file-box i { font-size: 28px; color: #c4b5fd; margin-bottom: 8px; display: block; }
.st-file-box span { font-size: 12px; color: #6b7280; }
.st-file-preview {
    display: flex; align-items: center; gap: 10px; padding: 10px 14px;
    background: #f0f4ff; border-radius: 10px; border: 1px solid #e0e7ff; margin-bottom: 10px;
}
.st-file-preview img { max-height: 36px; border-radius: 6px; }
.st-file-preview span { font-size: 12px; color: #6366f1; font-weight: 600; }

@media (max-width: 900px) {
    .st-layout { flex-direction: column; }
    .st-sidebar { flex: none; width: 100%; position: static; }
}
</style>
@endpush

@php
$navItems = [
    'general'    => ['icon'=>'bx bx-store',          'label'=>'General',            'desc'=>'Site name, currency, logo',   'color'=>'linear-gradient(135deg,#6366f1,#8b5cf6)'],
    'commission' => ['icon'=>'bx bx-percentage',      'label'=>'Commission',          'desc'=>'Fees, payouts, auction',      'color'=>'linear-gradient(135deg,#f59e0b,#d97706)'],
    'payment'    => ['icon'=>'bx bx-credit-card',     'label'=>'Payment',             'desc'=>'Stripe, PayPal gateways',     'color'=>'linear-gradient(135deg,#3b82f6,#2563eb)'],
    'vendors'    => ['icon'=>'bx bx-store-alt',       'label'=>'Vendors',             'desc'=>'Approval, onboarding',        'color'=>'linear-gradient(135deg,#10b981,#059669)'],
    'map'        => ['icon'=>'bx bx-map',             'label'=>'Google Maps',         'desc'=>'Maps API key',                'color'=>'linear-gradient(135deg,#ef4444,#dc2626)'],
    'smtp'       => ['icon'=>'bx bx-envelope',        'label'=>'Email / SMTP',        'desc'=>'Mail server config',          'color'=>'linear-gradient(135deg,#8b5cf6,#7c3aed)'],
];
$pageHeaders = [
    'general'    => ['title'=>'General Settings',            'sub'=>'Configure your site identity, currency, and branding',    'icon'=>'bx bx-store',       'color'=>'linear-gradient(135deg,#6366f1,#8b5cf6)'],
    'commission' => ['title'=>'Commission & Payouts',         'sub'=>'Set platform fees, vendor earnings, and payout rules',    'icon'=>'bx bx-percentage',  'color'=>'linear-gradient(135deg,#f59e0b,#d97706)'],
    'payment'    => ['title'=>'Payment Gateways',             'sub'=>'Configure Stripe and PayPal for accepting payments',      'icon'=>'bx bx-credit-card', 'color'=>'linear-gradient(135deg,#3b82f6,#2563eb)'],
    'vendors'    => ['title'=>'Vendor Settings',              'sub'=>'Control how vendors apply and get approved on your platform','icon'=>'bx bx-store-alt', 'color'=>'linear-gradient(135deg,#10b981,#059669)'],
    'map'        => ['title'=>'Google Maps',                  'sub'=>'Enable location features with your Google Maps API key',  'icon'=>'bx bx-map',         'color'=>'linear-gradient(135deg,#ef4444,#dc2626)'],
    'smtp'       => ['title'=>'Email / SMTP',                 'sub'=>'Configure outgoing mail for notifications and receipts',  'icon'=>'bx bx-envelope',    'color'=>'linear-gradient(135deg,#8b5cf6,#7c3aed)'],
];
$ph = $pageHeaders[$group] ?? $pageHeaders['general'];
@endphp

{{-- ── Page Header Banner ── --}}
<div class="st-page-header" style="background: {{ $ph['color'] }}">
    <div class="st-ph-icon" style="background:rgba(255,255,255,.2)">
        <i class="{{ $ph['icon'] }}"></i>
    </div>
    <div class="st-ph-text">
        <h4>{{ $ph['title'] }}</h4>
        <p>{{ $ph['sub'] }}</p>
    </div>
    <div class="st-ph-badge">
        <i class="bx bx-cog"></i> System Settings
    </div>
</div>

@if(session('success'))
<div class="st-flash">
    <i class="bx bx-check-circle"></i>
    <div><strong>Saved!</strong> {{ session('success') }}</div>
</div>
@endif

{{-- ── Layout: sidebar + main ── --}}
<div class="st-layout">

    {{-- Sidebar --}}
    <div class="st-sidebar">
        <div class="st-sidebar-head">
            <span><i class="bx bx-slider-alt me-1"></i> Configuration</span>
        </div>
        @foreach ($navItems as $key => $item)
        <a href="{{ route('admin.settings.index', $key) }}"
           class="st-nav-item {{ $group === $key ? 'active' : '' }}">
            <div class="st-nav-icon" style="{{ $group === $key ? 'background:'.$item['color'].';' : '' }}">
                <i class="{{ $item['icon'] }}"></i>
            </div>
            <div class="st-nav-text-wrap">
                <div class="st-nav-label">{{ $item['label'] }}</div>
                <div class="st-nav-desc">{{ $item['desc'] }}</div>
            </div>
            <i class="bx bx-chevron-right st-nav-arrow"></i>
        </a>
        @endforeach
    </div>

    {{-- Main content (each page puts its form here, then includes _nav_end) --}}
    <div class="st-main">
