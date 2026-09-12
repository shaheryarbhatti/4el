@extends('admin.layouts.app')
@section('title', 'Payment Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Payment</li>
@endsection

@section('content')
@include('admin.settings._nav')

<form action="{{ route('admin.settings.update', 'payment') }}" method="POST">
@csrf

{{-- ── Stripe ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#635bff,#4f46e5)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#635bff,#4f46e5)">
                <i class="bx bxl-stripe"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Stripe</h6>
                <p>Accept credit/debit cards via Stripe's secure hosted checkout</p>
            </div>
            <div class="ms-auto d-flex align-items-center gap-3">
                @if($values['stripe_enabled'])
                <span class="badge" style="background:#ecfdf5;color:#166534;border:1px solid #bbf7d0;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700"><i class="bx bx-check-circle me-1"></i>Active</span>
                @else
                <span class="badge" style="background:#f9fafb;color:#9ca3af;border:1px solid #e5e7eb;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700"><i class="bx bx-x-circle me-1"></i>Inactive</span>
                @endif
                <div class="form-check form-switch mb-0">
                    <input type="hidden" name="stripe_enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="stripe_enabled" value="1"
                           id="stripe_enabled" style="width:44px;height:22px;cursor:pointer"
                           {{ $values['stripe_enabled'] ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-12">
                <div class="st-field">
                    <label class="st-label">Environment</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-globe st-input-icon"></i>
                        @php $stripeMode = old('stripe_mode', $values['stripe_mode'] ?? 'test'); @endphp
                        <select name="stripe_mode" class="form-select">
                            <option value="test" {{ $stripeMode === 'test' ? 'selected' : '' }}>🧪 Sandbox / Test (use pk_test_ / sk_test_ keys)</option>
                            <option value="live" {{ $stripeMode === 'live' ? 'selected' : '' }}>🚀 Live (use pk_live_ / sk_live_ keys)</option>
                        </select>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Use <b>Sandbox</b> with your Stripe <b>test</b> keys to test payments; switch to <b>Live</b> with live keys before going public.</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Publishable Key</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-key st-input-icon"></i>
                        <input type="text" name="stripe_key" class="form-control"
                               value="{{ old('stripe_key', $values['stripe_key']) }}"
                               placeholder="{{ $stripeMode === 'live' ? 'pk_live_...' : 'pk_test_...' }}">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Safe to expose in frontend JavaScript</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Secret Key</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-lock st-input-icon"></i>
                        <input type="password" name="stripe_secret" class="form-control"
                               value="{{ old('stripe_secret', $values['stripe_secret']) }}"
                               placeholder="sk_live_...">
                    </div>
                    <div class="st-hint" style="color:#ef4444"><i class="bx bx-shield"></i> Never share — keep this private</div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">Webhook Secret <span class="opt">(optional)</span></label>
                    <div class="st-input-wrap">
                        <i class="bx bx-link st-input-icon"></i>
                        <input type="password" name="stripe_webhook_secret" class="form-control"
                               value="{{ old('stripe_webhook_secret', $values['stripe_webhook_secret']) }}"
                               placeholder="whsec_...">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Required for real-time payment confirmations via Stripe webhooks</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── PayPal ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#003087,#009cde)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#003087,#009cde)">
                <i class="bx bxl-paypal"></i>
            </div>
            <div class="st-card-header-text">
                <h6>PayPal</h6>
                <p>Accept payments via PayPal accounts and PayPal-supported cards</p>
            </div>
            <div class="ms-auto d-flex align-items-center gap-3">
                @if($values['paypal_enabled'])
                <span class="badge" style="background:#ecfdf5;color:#166534;border:1px solid #bbf7d0;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700"><i class="bx bx-check-circle me-1"></i>Active</span>
                @else
                <span class="badge" style="background:#f9fafb;color:#9ca3af;border:1px solid #e5e7eb;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700"><i class="bx bx-x-circle me-1"></i>Inactive</span>
                @endif
                <div class="form-check form-switch mb-0">
                    <input type="hidden" name="paypal_enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="paypal_enabled" value="1"
                           id="paypal_enabled" style="width:44px;height:22px;cursor:pointer"
                           {{ $values['paypal_enabled'] ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Environment</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-globe st-input-icon"></i>
                        <select name="paypal_mode" class="form-select">
                            <option value="sandbox" {{ $values['paypal_mode'] === 'sandbox' ? 'selected' : '' }}>🧪 Sandbox (testing)</option>
                            <option value="live"    {{ $values['paypal_mode'] === 'live'    ? 'selected' : '' }}>🚀 Live (production)</option>
                        </select>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Switch to Live before going public</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Client ID</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-user-check st-input-icon"></i>
                        <input type="text" name="paypal_client_id" class="form-control"
                               value="{{ old('paypal_client_id', $values['paypal_client_id']) }}"
                               placeholder="Axxx...">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">Secret</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-lock st-input-icon"></i>
                        <input type="password" name="paypal_secret" class="form-control"
                               value="{{ old('paypal_secret', $values['paypal_secret']) }}"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Cash on Delivery (toggle) ── --}}
@php $codEnabled = old('cod_enabled', $values['cod_enabled'] ?? '1'); @endphp
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#f59e0b,#d97706)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                <i class="bx bx-money"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Cash on Delivery (COD)</h6>
                <p>Let buyers pay in cash when their order is delivered</p>
            </div>
            <div class="ms-auto d-flex align-items-center gap-3">
                @if($codEnabled)
                <span class="badge" style="background:#ecfdf5;color:#166534;border:1px solid #bbf7d0;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700"><i class="bx bx-check-circle me-1"></i>Active</span>
                @else
                <span class="badge" style="background:#f9fafb;color:#9ca3af;border:1px solid #e5e7eb;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700"><i class="bx bx-x-circle me-1"></i>Inactive</span>
                @endif
                <div class="form-check form-switch mb-0">
                    <input type="hidden" name="cod_enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="cod_enabled" value="1"
                           id="cod_enabled" style="width:44px;height:22px;cursor:pointer"
                           {{ $codEnabled ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
    <div class="st-card-body" style="padding:14px 20px">
        <div class="st-hint"><i class="bx bx-info-circle"></i> When enabled, "Cash on Delivery" appears as a payment option at checkout. Turn it off to accept online payments only.</div>
    </div>
</div>

<div class="st-save-bar">
    <div class="st-save-bar-text">
        <i class="bx bx-shield-check"></i>
        API keys are stored securely and never exposed to buyers.
    </div>
    <button type="submit" class="st-btn-save" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
        <i class="bx bx-save"></i> Save Payment Settings
    </button>
</div>

</form>

@include('admin.settings._nav_end')
@endsection
