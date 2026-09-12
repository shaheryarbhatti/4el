@extends('admin.layouts.app')
@section('title', 'Vendor Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Vendors</li>
@endsection

@section('content')
@include('admin.settings._nav')

<form action="{{ route('admin.settings.update', 'vendors') }}" method="POST">
@csrf

{{-- Approval Policy --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#10b981,#059669)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                <i class="bx bx-shield-quarter"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Vendor Approval Policy</h6>
                <p>Control whether new vendors are approved instantly or require manual review</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="st-toggle-row">
            <div class="st-toggle-info">
                <h6>Require Admin Approval</h6>
                <p>
                    When <strong>ON</strong> — new applications are held as Pending until an admin reviews them. Vendors cannot list products until approved.<br>
                    When <strong>OFF</strong> — vendors are approved instantly on registration and can start selling immediately.
                </p>
            </div>
            <div class="flex-shrink-0">
                <input type="hidden" name="vendor_approval_required" value="0">
                <input class="form-check-input" type="checkbox" name="vendor_approval_required" value="1"
                       id="vendor_approval_required" style="width:44px;height:22px;cursor:pointer"
                       {{ (old('vendor_approval_required', $values['vendor_approval_required'] ?? '1')) == '1' ? 'checked' : '' }}>
            </div>
        </div>

        {{-- Live status --}}
        <div class="mt-3" id="v-status-wrap">
            <div id="v-on-badge" class="{{ ($values['vendor_approval_required'] ?? '1') == '1' ? '' : 'd-none' }}">
                <div style="display:inline-flex;align-items:center;gap:8px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:8px 14px;font-size:12.5px;color:#991b1b;font-weight:600">
                    <i class="bx bx-lock" style="font-size:15px"></i> Manual approval required — vendors wait for review
                </div>
            </div>
            <div id="v-off-badge" class="{{ ($values['vendor_approval_required'] ?? '1') == '1' ? 'd-none' : '' }}">
                <div style="display:inline-flex;align-items:center;gap:8px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:8px 14px;font-size:12.5px;color:#166534;font-weight:600">
                    <i class="bx bx-check-shield" style="font-size:15px"></i> Auto-approved — vendors start selling immediately
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Vendor Stats quick-view --}}
<div class="st-card" style="background:linear-gradient(135deg,#f8faff,#f0f4ff);border-color:#e0e7ff">
    <div class="st-card-body" style="padding:20px 24px">
        <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#6366f1;margin-bottom:14px">
            <i class="bx bx-bar-chart-alt me-1"></i> Vendor Onboarding Info
        </div>
        <div class="row gy-3">
            <div class="col-md-4">
                <div style="background:#fff;border-radius:12px;border:1px solid #e0e7ff;padding:14px 18px;display:flex;align-items:center;gap:12px">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;flex-shrink:0">
                        <i class="bx bx-store-alt"></i>
                    </div>
                    <div>
                        <div style="font-size:20px;font-weight:800;color:#111827;line-height:1">{{ \App\Models\VendorProfile::count() }}</div>
                        <div style="font-size:12px;color:#6b7280">Total Vendors</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:#fff;border-radius:12px;border:1px solid #bbf7d0;padding:14px 18px;display:flex;align-items:center;gap:12px">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;flex-shrink:0">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div>
                        <div style="font-size:20px;font-weight:800;color:#111827;line-height:1">{{ \App\Models\VendorProfile::where('status','approved')->count() }}</div>
                        <div style="font-size:12px;color:#6b7280">Approved</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:#fff;border-radius:12px;border:1px solid #fde68a;padding:14px 18px;display:flex;align-items:center;gap:12px">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;flex-shrink:0">
                        <i class="bx bx-time"></i>
                    </div>
                    <div>
                        <div style="font-size:20px;font-weight:800;color:#111827;line-height:1">{{ \App\Models\VendorProfile::where('status','pending')->count() }}</div>
                        <div style="font-size:12px;color:#6b7280">Pending</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pending Page Message --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#3b82f6,#2563eb)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
                <i class="bx bx-message-square-dots"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Pending Review Message</h6>
                <p>Shown to vendors while their application is waiting for admin approval</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="st-field" style="margin-bottom:0">
            <label class="st-label">Message Text</label>
            <div class="st-input-wrap">
                <i class="bx bx-message-detail st-input-icon" style="top:14px;transform:none"></i>
                <textarea name="vendor_welcome_message" rows="3" class="form-control"
                          style="padding-left:38px;resize:none"
                          placeholder="e.g. Thank you for applying! Our team reviews applications within 24 hours.">{{ old('vendor_welcome_message', $values['vendor_welcome_message'] ?? '') }}</textarea>
            </div>
            <div class="st-hint"><i class="bx bx-info-circle"></i> Leave blank to show the system default message</div>
        </div>
    </div>
</div>

<div class="st-save-bar">
    <div class="st-save-bar-text">
        <i class="bx bx-shield-check"></i>
        Approval changes take effect for all new vendor registrations.
    </div>
    <button type="submit" class="st-btn-save" style="background:linear-gradient(135deg,#10b981,#059669)">
        <i class="bx bx-save"></i> Save Vendor Settings
    </button>
</div>

</form>

@include('admin.settings._nav_end')
@endsection

@push('scripts')
<script>
document.getElementById('vendor_approval_required').addEventListener('change', function() {
    document.getElementById('v-on-badge').classList.toggle('d-none', !this.checked);
    document.getElementById('v-off-badge').classList.toggle('d-none', this.checked);
});
</script>
@endpush
