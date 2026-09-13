@extends('admin.layouts.app')
@section('title', 'General Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">General</li>
@endsection

@section('content')
@include('admin.settings._nav')

<form action="{{ route('admin.settings.update', 'general') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- ── Site Identity ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#6366f1,#8b5cf6)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="bx bx-id-card"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Site Identity</h6>
                <p>Name and tagline that appear on the storefront and browser tab</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Site Name <span class="req">*</span></label>
                    <div class="st-input-wrap">
                        <i class="bx bx-store st-input-icon"></i>
                        <input type="text" name="site_name" class="form-control"
                               value="{{ old('site_name', $values['site_name']) }}"
                               placeholder="eBay Clone">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Shown in browser tabs and email headers</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Tagline <span class="opt">(optional)</span></label>
                    <div class="st-input-wrap">
                        <i class="bx bx-quote-left st-input-icon"></i>
                        <input type="text" name="site_tagline" class="form-control"
                               value="{{ old('site_tagline', $values['site_tagline']) }}"
                               placeholder="Buy & Sell Everything">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Appears below the logo on the homepage hero</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Currency ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#10b981,#059669)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                <i class="bx bx-dollar-circle"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Currency</h6>
                <p>All prices across the platform use this currency</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Currency Code</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-world st-input-icon"></i>
                        <input type="text" name="currency" class="form-control"
                               value="{{ old('currency', $values['currency']) }}"
                               placeholder="USD" maxlength="3">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> ISO 4217 code — USD, GBP, EUR, PKR…</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Currency Symbol</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-money st-input-icon"></i>
                        <input type="text" name="currency_symbol" class="form-control"
                               value="{{ old('currency_symbol', $values['currency_symbol']) }}"
                               placeholder="$" maxlength="4">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Displayed next to prices — $, £, €, ₨…</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Preview</label>
                    <div style="height:42px;border:1.5px solid #e5e7eb;border-radius:10px;background:#f9fafb;display:flex;align-items:center;padding:0 14px;font-size:18px;font-weight:700;color:#6366f1;">
                        <span id="sym-prev">{{ $values['currency_symbol'] ?? '$' }}</span><span id="code-prev" style="font-size:13px;color:#9ca3af;margin-left:6px;font-weight:500">{{ $values['currency'] ?? 'USD' }}</span>
                    </div>
                    <div class="st-hint"><i class="bx bx-eye"></i> Live currency preview</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Contact Info ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#3b82f6,#2563eb)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
                <i class="bx bx-phone-call"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Contact Information</h6>
                <p>Shown in footers, emails, and the About page</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Contact Email</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-envelope st-input-icon"></i>
                        <input type="email" name="contact_email" class="form-control"
                               value="{{ old('contact_email', $values['contact_email']) }}"
                               placeholder="support@yourstore.com">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Contact Phone</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-phone st-input-icon"></i>
                        <input type="text" name="contact_phone" class="form-control"
                               value="{{ old('contact_phone', $values['contact_phone']) }}"
                               placeholder="+1 234 567 890">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">Business Address <span class="opt">(optional)</span></label>
                    <div class="st-input-wrap">
                        <i class="bx bx-map-pin st-input-icon" style="top:14px;transform:none"></i>
                        <textarea name="address" rows="2" class="form-control"
                                  style="padding-left:38px;resize:none"
                                  placeholder="123 Market St, City, Country">{{ old('address', $values['address']) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Branding (Logo + Favicon) ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#f59e0b,#d97706)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                <i class="bx bx-image-alt"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Branding</h6>
                <p>Upload your logo and favicon — PNG or SVG recommended</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <label class="st-label">Logo</label>
                @if (!empty($values['logo']))
                <div class="st-file-preview">
                    <img src="{{ asset('storage/'.$values['logo']) }}" alt="logo">
                    <span><i class="bx bx-check-circle me-1"></i>Current logo</span>
                </div>
                @endif
                <div class="st-file-box">
                    <input type="file" name="logo" accept="image/*">
                    <i class="bx bx-cloud-upload"></i>
                    <span>Click to upload logo<br><span style="color:#d1d5db">PNG, SVG, JPG — max 2MB</span></span>
                </div>
                <div class="st-hint"><i class="bx bx-info-circle"></i> Recommended: 200×60px transparent PNG</div>
            </div>
            <div class="col-md-6">
                <label class="st-label">Favicon</label>
                @if (!empty($values['favicon']))
                <div class="st-file-preview">
                    <img src="{{ asset('storage/'.$values['favicon']) }}" alt="favicon">
                    <span><i class="bx bx-check-circle me-1"></i>Current favicon</span>
                </div>
                @endif
                <div class="st-file-box">
                    <input type="file" name="favicon" accept="image/*">
                    <i class="bx bxs-star"></i>
                    <span>Click to upload favicon<br><span style="color:#d1d5db">ICO or 32×32 PNG</span></span>
                </div>
                <div class="st-hint"><i class="bx bx-info-circle"></i> Shown in browser tabs — use 32×32px ICO or PNG</div>
            </div>
        </div>

        <div class="row gy-4 mt-1">
            {{-- Admin panel logo (sidebar) --}}
            <div class="col-md-6">
                <label class="st-label">Admin Panel Logo</label>
                @if (!empty($values['admin_logo']))
                <div class="st-file-preview">
                    <img src="{{ asset('storage/'.$values['admin_logo']) }}" alt="admin logo">
                    <span><i class="bx bx-check-circle me-1"></i>Current admin logo</span>
                </div>
                @endif
                <div class="st-file-box">
                    <input type="file" name="admin_logo" accept="image/*">
                    <i class="bx bx-cloud-upload"></i>
                    <span>Click to upload admin panel logo</span>
                </div>
                <div class="st-hint"><i class="bx bx-info-circle"></i> Shown in the admin sidebar — 200×60px transparent PNG (falls back to default)</div>
            </div>
            {{-- Login page logo --}}
            <div class="col-md-6">
                <label class="st-label">Login Page Logo</label>
                @if (!empty($values['login_logo']))
                <div class="st-file-preview">
                    <img src="{{ asset('storage/'.$values['login_logo']) }}" alt="login logo">
                    <span><i class="bx bx-check-circle me-1"></i>Current login logo</span>
                </div>
                @endif
                <div class="st-file-box">
                    <input type="file" name="login_logo" accept="image/*">
                    <i class="bx bx-cloud-upload"></i>
                    <span>Click to upload login page logo</span>
                </div>
                <div class="st-hint"><i class="bx bx-info-circle"></i> Shown on the admin login screen — 200×60px PNG (falls back to default)</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Save Bar ── --}}
<div class="st-save-bar">
    <div class="st-save-bar-text">
        <i class="bx bx-shield-check"></i>
        Changes are applied site-wide immediately after saving.
    </div>
    <button type="submit" class="st-btn-save">
        <i class="bx bx-save"></i> Save General Settings
    </button>
</div>

</form>

@include('admin.settings._nav_end')
@endsection

@push('scripts')
<script>
// Live currency preview
var symEl  = document.querySelector('input[name="currency_symbol"]');
var codeEl = document.querySelector('input[name="currency"]');
var symPrev  = document.getElementById('sym-prev');
var codePrev = document.getElementById('code-prev');
if (symEl && symPrev) {
    symEl.addEventListener('input', function()  { symPrev.textContent  = this.value || '$'; });
    codeEl.addEventListener('input', function() { codePrev.textContent = this.value || 'USD'; });
}
// File upload preview label
document.querySelectorAll('.st-file-box').forEach(function(box) {
    var input = box.querySelector('input[type="file"]');
    if (!input) return;
    input.addEventListener('change', function() {
        var span = box.querySelector('span');
        if (this.files[0]) span.innerHTML = '<strong style="color:#6366f1">' + this.files[0].name + '</strong>';
    });
});
</script>
@endpush
