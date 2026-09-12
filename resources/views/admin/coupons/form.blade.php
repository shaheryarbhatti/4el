@extends('admin.layouts.app')
@section('title', isset($coupon->id) ? 'Edit Coupon' : 'Add Coupon')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }
.page-heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; }
.page-heading h4 { font-size:22px; font-weight:800; color:#1e293b; margin:0; display:flex; align-items:center; gap:10px; }

.form-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); padding:32px; max-width:720px; }
.form-section { margin-bottom:32px; }
.form-section-title { font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#64748b; margin-bottom:18px; padding-bottom:10px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px; }
.form-section-title i { color:#6c63ff; }

.form-group { margin-bottom:20px; }
.form-group label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:7px; }
.form-group label .req { color:#ef4444; margin-left:2px; }
.form-input, .form-select, .form-textarea {
    width:100%; padding:11px 14px; border:1.5px solid #e2e8f0;
    border-radius:10px; font-size:14px; color:#374151;
    background:#f8fafc; outline:none; transition:border-color .15s, background .15s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color:#6c63ff; background:#fff;
}
.form-textarea { min-height:80px; resize:vertical; }
.form-hint { font-size:12px; color:#94a3b8; margin-top:5px; }
.input-prefix-group { display:flex; }
.input-prefix-group .prefix {
    background:#e2e8f0; border:1.5px solid #e2e8f0; border-right:none;
    border-radius:10px 0 0 10px; padding:11px 14px; font-size:14px; font-weight:700;
    color:#64748b; white-space:nowrap; line-height:1.4;
}
.input-prefix-group .form-input { border-radius:0 10px 10px 0; }

.grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; }

.btn-save {
    display:inline-flex; align-items:center; gap:10px; padding:13px 28px;
    background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff;
    border:none; border-radius:14px; font-size:15px; font-weight:700;
    cursor:pointer; box-shadow:0 4px 16px rgba(108,99,255,.4);
    transition:transform .2s,box-shadow .2s;
}
.btn-save:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(108,99,255,.55); }
.btn-cancel { display:inline-flex; align-items:center; gap:8px; padding:13px 22px; background:#f1f5f9; color:#475569; border:none; border-radius:14px; font-size:15px; font-weight:700; cursor:pointer; text-decoration:none; transition:background .15s; }
.btn-cancel:hover { background:#e2e8f0; color:#1e293b; text-decoration:none; }

.toggle-switch { display:flex; align-items:center; gap:12px; }
.toggle-switch input[type=checkbox] { display:none; }
.toggle-switch .slider {
    width:48px; height:26px; background:#e2e8f0; border-radius:13px;
    position:relative; cursor:pointer; transition:background .2s;
}
.toggle-switch .slider::after {
    content:''; position:absolute; top:3px; left:3px;
    width:20px; height:20px; border-radius:50%; background:#fff;
    transition:transform .2s; box-shadow:0 1px 4px rgba(0,0,0,.2);
}
.toggle-switch input:checked + .slider { background:#6c63ff; }
.toggle-switch input:checked + .slider::after { transform:translateX(22px); }
.toggle-switch .lbl { font-size:14px; font-weight:600; color:#374151; cursor:pointer; }

.invalid-feedback { color:#ef4444; font-size:12px; margin-top:4px; display:block; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="page-heading">
        <div>
            <h4>
                <i class="bi bi-ticket-perforated-fill" style="color:#6c63ff;"></i>
                {{ isset($coupon->id) ? 'Edit Coupon' : 'New Coupon' }}
            </h4>
            <nav style="margin-top:4px;">
                <ol class="breadcrumb mb-0" style="font-size:12.5px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active">{{ isset($coupon->id) ? 'Edit' : 'Create' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST"
          action="{{ isset($coupon->id) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}">
        @csrf
        @if(isset($coupon->id)) @method('PUT') @endif

        <div class="form-card">

            {{-- Basic Info --}}
            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-info-circle-fill"></i> Basic Information</div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Coupon Code <span class="req">*</span></label>
                        <input type="text" name="code" class="form-input {{ $errors->has('code') ? 'border-danger' : '' }}"
                               value="{{ old('code', $coupon->code ?? '') }}"
                               placeholder="e.g. SAVE20" style="text-transform:uppercase;" required>
                        @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <div class="form-hint">Will be auto-uppercased. Share this code with customers.</div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="form-input"
                               value="{{ old('description', $coupon->description ?? '') }}"
                               placeholder="Internal note (optional)">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Discount Type <span class="req">*</span></label>
                        <select name="type" class="form-select" id="type-select" required>
                            <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed"      {{ old('type', $coupon->type ?? '') === 'fixed'      ? 'selected' : '' }}>Fixed Amount ($)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Discount Value <span class="req">*</span></label>
                        <div class="input-prefix-group">
                            <span class="prefix" id="value-prefix">%</span>
                            <input type="number" name="value" class="form-input {{ $errors->has('value') ? 'border-danger' : '' }}"
                                   value="{{ old('value', $coupon->value ?? '') }}"
                                   step="0.01" min="0.01" placeholder="10.00" required>
                        </div>
                        @error('value')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Restrictions --}}
            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-shield-check-fill"></i> Usage Restrictions</div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Minimum Order Amount</label>
                        <div class="input-prefix-group">
                            <span class="prefix">$</span>
                            <input type="number" name="min_order_amount" class="form-input"
                                   value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}"
                                   step="0.01" min="0" placeholder="Leave blank for none">
                        </div>
                        <div class="form-hint">Coupon only works on orders above this amount.</div>
                    </div>

                    <div class="form-group">
                        <label>Maximum Uses</label>
                        <input type="number" name="max_uses" class="form-input"
                               value="{{ old('max_uses', $coupon->max_uses ?? '') }}"
                               min="1" placeholder="Leave blank for unlimited">
                        <div class="form-hint">How many times this coupon can be used in total.</div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="datetime-local" name="expires_at" class="form-input"
                               value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at?->format('Y-m-d\TH:i') : '') }}">
                        <div class="form-hint">Leave blank to never expire.</div>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <div style="padding-top:6px;">
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                                <span class="slider"></span>
                                <span class="lbl">Active (customers can use this coupon)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div style="display:flex; gap:14px; align-items:center;">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i>
                    {{ isset($coupon->id) ? 'Update Coupon' : 'Create Coupon' }}
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('type-select').addEventListener('change', function () {
    document.getElementById('value-prefix').textContent = this.value === 'percentage' ? '%' : '$';
});
// Init on load
(function() {
    var t = document.getElementById('type-select').value;
    document.getElementById('value-prefix').textContent = t === 'percentage' ? '%' : '$';
})();
</script>
@endpush
