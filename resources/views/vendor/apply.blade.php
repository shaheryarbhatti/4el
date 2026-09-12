{{-- "Become a seller" — eBay-style professional onboarding (single-page form with JS step illusion) --}}
@extends('frontend.layouts.app')
@section('title', 'Start Selling — ' . setting('site_name', 'Marketplace'))

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('frontend-assets/css/checkout-autocomplete.css') }}">
<style>
/* ── QUILL EDITOR ── */
.va-quill-wrap .ql-toolbar.ql-snow {
    border: 2px solid #e8ecf0; border-bottom: none;
    border-radius: 10px 10px 0 0; background: #f8fafc;
    font-family: inherit;
}
.va-quill-wrap .ql-container.ql-snow {
    border: 2px solid #e8ecf0; border-top: none;
    border-radius: 0 0 10px 10px; font-size: 14px;
    font-family: inherit; min-height: 130px;
}
.va-quill-wrap .ql-editor { min-height: 120px; line-height: 1.6; color: #2d2d2d; }
.va-quill-wrap .ql-container.ql-snow:focus-within {
    border-color: #1565c0;
    box-shadow: 0 0 0 3px rgba(21,101,192,.12);
}
.va-quill-wrap .ql-toolbar.ql-snow:has(+ .ql-container.ql-snow:focus-within) {
    border-color: #1565c0;
}
</style>
<style>
/* ── PAGE BASE ── */
.va-page {
    background: linear-gradient(160deg, #f0f4ff 0%, #fafafa 60%, #fff 100%);
    min-height: 100vh; padding: 0 0 80px;
}

/* ── HERO (removed) ── */
.va-hero__sub {
    display: none;
}
.va-hero__perks {
    display: flex; justify-content: center; gap: 28px; flex-wrap: wrap;
    position: relative; z-index: 1;
}
.va-hero__perk {
    display: flex; align-items: center; gap: 8px;
    font-size: 13.5px; color: rgba(255,255,255,.85); font-weight: 600;
}
.va-hero__perk i { color: #4db6ff; font-size: 14px; }

/* ── STEPS INDICATOR ── */
.va-steps-wrap {
    background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,.09);
    padding: 0;
}
.va-steps {
    display: flex; max-width: 680px; margin: 0 auto;
}
.va-step-item {
    flex: 1; display: flex; flex-direction: column; align-items: center;
    padding: 14px 10px 12px; position: relative; cursor: pointer;
    transition: background .2s;
}
.va-step-item::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    background: transparent; transition: background .25s;
}
.va-step-item.active::after { background: linear-gradient(90deg, #1565c0, #42a5f5); }
.va-step-item.done::after   { background: linear-gradient(90deg, #2e7d32, #66bb6a); }
.va-step-num {
    width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 12px; font-weight: 800; margin-bottom: 5px;
    background: #e8ecf4; color: #999; transition: all .25s;
}
.va-step-item.active .va-step-num { background: linear-gradient(135deg,#1565c0,#42a5f5); color: #fff; }
.va-step-item.done   .va-step-num { background: linear-gradient(135deg,#2e7d32,#66bb6a); color: #fff; }
.va-step-label { font-size: 11.5px; font-weight: 600; color: #aaa; transition: color .2s; white-space: nowrap; }
.va-step-item.active .va-step-label,
.va-step-item.done   .va-step-label { color: #333; }

/* ── FORM CONTAINER ── */
.va-form-wrap {
    max-width: 680px; margin: 32px auto 0; padding: 0 16px;
}

/* ── PANELS ── */
.va-panel {
    background: #fff; border-radius: 18px;
    box-shadow: 0 8px 40px rgba(0,0,0,.11);
    padding: 36px 40px 28px;
    display: none; animation: vaPanelIn .3s ease both;
}
.va-panel.active { display: block; }
@keyframes vaPanelIn { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }

.va-panel-head { margin-bottom: 26px; }
.va-panel-icon {
    width: 56px; height: 56px; border-radius: 16px; margin-bottom: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff;
}
.va-panel-icon--blue   { background: linear-gradient(135deg,#1565c0,#42a5f5); }
.va-panel-icon--green  { background: linear-gradient(135deg,#2e7d32,#66bb6a); }
.va-panel-icon--orange { background: linear-gradient(135deg,#e65100,#ffa726); }
.va-panel-title { font-size: 22px; font-weight: 800; color: #1a1a2e; margin: 0 0 6px; }
.va-panel-sub   { font-size: 14px; color: #777; margin: 0; line-height: 1.5; }

/* ── FIELDS ── */
.va-fgroup { margin-bottom: 20px; }
.va-label {
    display: block; font-size: 11.5px; font-weight: 700; color: #555;
    text-transform: uppercase; letter-spacing: .06em; margin-bottom: 7px;
}
.va-label .req { color: #e53935; margin-left: 2px; }
.va-input, .va-textarea {
    width: 100%; padding: 11px 14px; border: 2px solid #e8ecf0; border-radius: 10px;
    font-size: 14px; color: #2d2d2d; background: #fff; outline: none;
    transition: border-color .2s, box-shadow .2s; box-sizing: border-box; font-family: inherit;
}
.va-input:focus, .va-textarea:focus {
    border-color: #1565c0; box-shadow: 0 0 0 3px rgba(21,101,192,.12);
}
.va-textarea { resize: vertical; min-height: 90px; line-height: 1.6; }
.va-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.va-hint { font-size: 12px; color: #94a3b8; margin-top: 5px; display: flex; align-items: flex-start; gap: 5px; line-height: 1.5; }
.va-hint i { color: #1565c0; flex-shrink: 0; margin-top: 2px; }

/* ── NAV BUTTONS ── */
.va-nav { display: flex; align-items: center; justify-content: space-between; margin-top: 28px; }
.va-btn-next {
    background: linear-gradient(135deg,#1a1a2e,#0f3460); color: #fff;
    border: none; border-radius: 10px; padding: 12px 28px;
    font-size: 14.5px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 9px;
    box-shadow: 0 4px 14px rgba(15,52,96,.35); transition: all .2s;
}
.va-btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,52,96,.45); }
.va-btn-back {
    background: none; border: 2px solid #e8ecf0; border-radius: 10px;
    padding: 11px 20px; font-size: 14px; font-weight: 600; cursor: pointer;
    color: #555; transition: all .2s; display: inline-flex; align-items: center; gap: 7px;
}
.va-btn-back:hover { border-color: #aaa; color: #222; }
.va-btn-submit {
    background: linear-gradient(135deg,#2e7d32,#66bb6a); color: #fff;
    border: none; border-radius: 10px; padding: 13px 32px;
    font-size: 15px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 9px;
    box-shadow: 0 4px 14px rgba(46,125,50,.35); transition: all .2s;
}
.va-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(46,125,50,.5); }

/* ── REVIEW CARD ── */
.va-review-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 0; border-bottom: 1px solid #f1f5f9; font-size: 13.5px;
}
.va-review-row:last-child { border-bottom: none; }
.va-review-key { color: #64748b; font-weight: 600; }
.va-review-val { color: #1e293b; font-weight: 700; text-align: right; }

/* ── ERROR ── */
.va-errors {
    background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px;
    padding: 14px 18px; margin-bottom: 22px;
}
.va-errors ul { margin: 0; padding-left: 18px; }
.va-errors li { font-size: 13px; color: #be123c; margin-bottom: 3px; }
.va-errors__title { font-size: 13px; font-weight: 700; color: #be123c; margin-bottom: 8px; display: flex; align-items: center; gap: 7px; }

/* ── TRUST BADGES ── */
.va-trust { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-top: 32px; }
.va-trust-item {
    text-align: center; padding: 16px 10px;
    border: 1.5px solid #e8ecf0; border-radius: 12px; background: #fff;
}
.va-trust-item i { font-size: 24px; color: #1565c0; margin-bottom: 8px; display: block; }
.va-trust-item strong { display: block; font-size: 13px; color: #1e293b; margin-bottom: 3px; }
.va-trust-item span { font-size: 11.5px; color: #94a3b8; line-height: 1.4; }

@media (max-width:576px) {
    .va-panel { padding: 24px 18px 20px; }
    .va-row { grid-template-columns: 1fr; }
    .va-trust { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="va-page">

    {{-- Steps bar --}}
    <div class="va-steps-wrap">
        <div class="va-steps" id="va-steps">
            <div class="va-step-item active" data-step="1" onclick="goStep(1)">
                <div class="va-step-num">1</div>
                <div class="va-step-label">Your Address</div>
            </div>
            <div class="va-step-item" data-step="2" onclick="goStep(2)">
                <div class="va-step-num">2</div>
                <div class="va-step-label">Store Setup</div>
            </div>
            <div class="va-step-item" data-step="3" onclick="goStep(3)">
                <div class="va-step-num">3</div>
                <div class="va-step-label">Review &amp; Submit</div>
            </div>
        </div>
    </div>

    <div class="va-form-wrap">

        {{-- Errors (back from server) --}}
        @if ($errors->any())
        <div class="va-errors" style="margin-top:20px;">
            <div class="va-errors__title"><i class="bi bi-exclamation-circle-fill"></i> Please fix the errors below:</div>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('vendor.apply.store') }}" method="POST" id="va-form">
            @csrf

            {{-- ════ PANEL 1 — Address ════ --}}
            <div class="va-panel {{ $errors->any() ? '' : 'active' }}" id="va-panel-1" data-panel="1">
                <div class="va-panel-head">
                    <div class="va-panel-icon va-panel-icon--blue"><i class="fas fa-map-marker-alt"></i></div>
                    <h3 class="va-panel-title">Where are you selling from?</h3>
                    <p class="va-panel-sub">We use this to help buyers calculate shipping and set the correct tax region for your store.</p>
                </div>

                <div class="va-fgroup">
                    <label class="va-label">Full Name / Business Name <span class="req">*</span></label>
                    <input type="text" name="business_name" class="va-input"
                           value="{{ old('business_name') }}"
                           placeholder="e.g. John's Electronics or ABC Trading Co.">
                    <div class="va-hint"><i class="fas fa-info-circle"></i> This is your legal name or registered business name.</div>
                </div>

                <div class="va-row">
                    <div class="va-fgroup">
                        <label class="va-label">Phone Number</label>
                        <input type="text" name="phone" class="va-input"
                               value="{{ old('phone') }}"
                               placeholder="+1 (555) 000-0000">
                    </div>
                    <div class="va-fgroup">
                        <label class="va-label">City</label>
                        <input type="text" name="city" class="va-input"
                               value="{{ old('city') }}"
                               placeholder="Your city">
                    </div>
                </div>

                <div class="va-fgroup">
                    <label class="va-label">Address</label>
                    <input type="text" name="address" id="va-address" class="va-input" autocomplete="off"
                           value="{{ old('address') }}"
                           placeholder="Start typing your address…">
                    {{-- Geo-coordinates captured from Google Places (saved with the store) --}}
                    <input type="hidden" name="latitude"  id="va-lat" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="va-lng" value="{{ old('longitude') }}">
                    <div class="va-hint" id="va-geo-hint"><i class="fas fa-map-marker-alt"></i> Pick your address from the suggestions — we'll pin your store location on the map.</div>
                </div>

                <div class="va-nav">
                    <span style="font-size:12.5px;color:#aaa;"><i class="fas fa-lock" style="color:#94a3b8;"></i> Your info is kept private</span>
                    <button type="button" class="va-btn-next" onclick="nextStep(1)">
                        Continue <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ════ PANEL 2 — Store Setup ════ --}}
            <div class="va-panel" id="va-panel-2" data-panel="2">
                <div class="va-panel-head">
                    <div class="va-panel-icon va-panel-icon--orange"><i class="fas fa-store"></i></div>
                    <h3 class="va-panel-title">Set up your store</h3>
                    <p class="va-panel-sub">Give your store a name and a brief description that buyers will see on your seller profile.</p>
                </div>

                <div class="va-fgroup">
                    <label class="va-label">Store Name <span class="req">*</span></label>
                    <input type="text" name="store_name" id="va-store-name" class="va-input" required
                           value="{{ old('store_name') }}"
                           placeholder="e.g. Tech Haven, Vintage Corner…"
                           oninput="document.getElementById('va-rev-store').textContent = this.value || '—'">
                    <div class="va-hint"><i class="fas fa-info-circle"></i> Choose a name that reflects what you sell. You can change it later.</div>
                </div>

                <div class="va-fgroup">
                    <label class="va-label">About Your Store</label>
                    <input type="hidden" name="description" id="va-desc-input" value="{{ old('description') }}">
                    <div class="va-quill-wrap">
                        <div id="va-desc-editor">{{ old('description') }}</div>
                    </div>
                    <div class="va-hint"><i class="fas fa-lightbulb"></i> Buyers who read a good description are more likely to trust you.</div>
                </div>

                <div class="va-fgroup">
                    <label class="va-label">Seller Type</label>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        @foreach(['individual'=>['Individual Seller','fa-user'],'business'=>['Business','fa-building']] as $val=>[$lbl,$ico])
                        <label style="flex:1;min-width:140px;">
                            <input type="radio" name="seller_type" value="{{ $val }}" style="display:none;"
                                   @checked(old('seller_type','individual')===$val)
                                   onchange="document.getElementById('va-rev-type').textContent = '{{ $lbl }}'">
                            <div class="va-seller-opt" style="border:2px solid #e8ecf0;border-radius:10px;padding:12px 14px;cursor:pointer;display:flex;align-items:center;gap:10px;font-size:13.5px;font-weight:600;color:#555;transition:all .18s;">
                                <i class="fas {{ $ico }}" style="font-size:16px;color:#1565c0;"></i> {{ $lbl }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="va-nav">
                    <button type="button" class="va-btn-back" onclick="goStep(1)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="button" class="va-btn-next" onclick="nextStep(2)">
                        Review & Submit <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ════ PANEL 3 — Review & Submit ════ --}}
            <div class="va-panel" id="va-panel-3" data-panel="3">
                <div class="va-panel-head">
                    <div class="va-panel-icon va-panel-icon--green"><i class="fas fa-check-double"></i></div>
                    <h3 class="va-panel-title">Review your details</h3>
                    <p class="va-panel-sub">Everything look good? Submit your application — an admin will review it, usually within 24 hours.</p>
                </div>

                <div style="border:1.5px solid #e8ecf0;border-radius:12px;overflow:hidden;margin-bottom:24px;">
                    <div style="background:#f8fafc;padding:10px 18px;font-size:11.5px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.06em;">
                        Your Application Summary
                    </div>
                    <div style="padding:4px 18px;">
                        <div class="va-review-row">
                            <span class="va-review-key">Store Name</span>
                            <span class="va-review-val" id="va-rev-store">—</span>
                        </div>
                        <div class="va-review-row">
                            <span class="va-review-key">Description</span>
                            <span class="va-review-val" id="va-rev-desc">—</span>
                        </div>
                        <div class="va-review-row">
                            <span class="va-review-key">Seller Type</span>
                            <span class="va-review-val" id="va-rev-type">Individual Seller</span>
                        </div>
                        <div class="va-review-row">
                            <span class="va-review-key">Account Email</span>
                            <span class="va-review-val">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="va-review-row">
                            <span class="va-review-key">Review Time</span>
                            <span class="va-review-val">Up to 24 hours</span>
                        </div>
                    </div>
                </div>

                <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px;">
                    <i class="fas fa-shield-alt" style="color:#16a34a;font-size:18px;margin-top:2px;flex-shrink:0;"></i>
                    <div style="font-size:13px;color:#166534;line-height:1.6;">
                        By submitting, you agree to our <a href="#" style="color:#166534;font-weight:700;">Seller Terms of Service</a>.
                        Your information is reviewed by our team for verification and marketplace quality.
                    </div>
                </div>

                <div class="va-nav">
                    <button type="button" class="va-btn-back" onclick="goStep(2)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" class="va-btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </div>
            </div>

        </form>

        {{-- Trust badges --}}
        <div class="va-trust">
            <div class="va-trust-item">
                <i class="fas fa-users"></i>
                <strong>Growing Community</strong>
                <span>Join thousands of trusted sellers on our platform</span>
            </div>
            <div class="va-trust-item">
                <i class="fas fa-gavel"></i>
                <strong>Auction &amp; Buy Now</strong>
                <span>List at a fixed price or run live bidding auctions</span>
            </div>
            <div class="va-trust-item">
                <i class="fas fa-headset"></i>
                <strong>Seller Support</strong>
                <span>Our team is here to help you succeed</span>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Google Places autocomplete + geo-coordinates (only if a Maps key is set) --}}
@if (setting('google_maps_api_key'))
    <script src="{{ asset('frontend-assets/js/vendor-address-autocomplete.js') }}"></script>
    <script async
            src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_api_key') }}&libraries=places&callback=initVendorAutocomplete&loading=async"></script>
@endif
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
// Init Quill rich text editor
var quill = new Quill('#va-desc-editor', {
    theme: 'snow',
    placeholder: 'Describe what you sell, your specialties, return policy, dispatch times…',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['clean']
        ]
    }
});

// Sync Quill content to hidden input + review card on every change
quill.on('text-change', function() {
    var html  = quill.root.innerHTML;
    var text  = quill.getText().trim();
    document.getElementById('va-desc-input').value = html;
    var preview = text.substring(0, 60) + (text.length > 60 ? '…' : '');
    document.getElementById('va-rev-desc').textContent = preview || '—';
});

// Also sync before form submit (safety net)
document.getElementById('va-form').addEventListener('submit', function() {
    document.getElementById('va-desc-input').value = quill.root.innerHTML;
});
</script>
<script>
var currentStep = 1;
var totalSteps  = 3;

function goStep(step) {
    // Hide all panels
    for (var i = 1; i <= totalSteps; i++) {
        var panel = document.getElementById('va-panel-' + i);
        if (panel) { panel.classList.remove('active'); }

        var si = document.querySelector('.va-step-item[data-step="' + i + '"]');
        if (si) {
            si.classList.remove('active', 'done');
            if (i < step) si.classList.add('done');
            if (i === step) si.classList.add('active');
        }
    }
    var target = document.getElementById('va-panel-' + step);
    if (target) target.classList.add('active');
    currentStep = step;

    // Scroll to form
    var wrap = document.querySelector('.va-steps-wrap');
    if (wrap) { window.scrollTo({ top: wrap.offsetTop - 60, behavior: 'smooth' }); }
}

function nextStep(from) {
    // Simple validation for required field in step 2
    if (from === 2) {
        var storeName = document.getElementById('va-store-name');
        if (storeName && !storeName.value.trim()) {
            storeName.style.borderColor = '#e53935';
            storeName.focus();
            return;
        }
        if (storeName) storeName.style.borderColor = '';
    }
    goStep(from + 1);
}

// Sync radio labels on selection
document.querySelectorAll('input[name="seller_type"]').forEach(function(r) {
    r.addEventListener('change', function() {
        document.querySelectorAll('.va-seller-opt').forEach(function(d) {
            d.style.borderColor = '#e8ecf0';
            d.style.background = '#fff';
            d.style.color = '#555';
        });
        var chosen = r.nextElementSibling;
        if (chosen) {
            chosen.style.borderColor = '#1565c0';
            chosen.style.background = '#e3f2fd';
            chosen.style.color = '#1565c0';
        }
    });
    // Init on load
    if (r.checked) {
        var chosen = r.nextElementSibling;
        if (chosen) {
            chosen.style.borderColor = '#1565c0';
            chosen.style.background = '#e3f2fd';
            chosen.style.color = '#1565c0';
        }
    }
});

// If there were validation errors, jump to step 2 (where store_name is required)
@if($errors->has('store_name') || $errors->has('description'))
goStep(2);
@elseif($errors->any())
goStep(1);
@endif
</script>
@endpush
