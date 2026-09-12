@extends('admin.layouts.app')
@section('title', 'Google Maps Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Google Maps</li>
@endsection

@section('content')
@include('admin.settings._nav')

<form action="{{ route('admin.settings.update', 'map') }}" method="POST">
@csrf

{{-- API Key Card --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#ef4444,#dc2626)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                <i class="bx bx-map-pin"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Google Maps API</h6>
                <p>Powers address autocomplete, store location maps, and distance calculations</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="st-field">
            <label class="st-label">API Key <span class="req">*</span></label>
            <div class="st-input-wrap">
                <i class="bx bx-key st-input-icon"></i>
                <input type="text" name="google_maps_api_key" class="form-control"
                       value="{{ old('google_maps_api_key', $values['google_maps_api_key'] ?? '') }}"
                       placeholder="AIzaSy...">
            </div>
            <div class="st-hint"><i class="bx bx-info-circle"></i>
                Enable <strong>Maps JavaScript API</strong> and <strong>Places API</strong> in Google Cloud Console
            </div>
        </div>

        {{-- Feature list --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:4px">
            @foreach ([
                ['bx bx-map','Address Autocomplete','Checkout & profile address fields'],
                ['bx bx-store-alt','Store Locator','Show vendor pickup locations on a map'],
                ['bx bx-compass','Distance Calc','Estimate delivery zones by distance'],
                ['bx bx-current-location','Geolocation','Auto-fill buyer city from browser location'],
            ] as [$icon,$title,$desc])
            <div style="background:#fafafa;border:1px solid #f0f0f0;border-radius:10px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#ef4444,#dc2626);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;flex-shrink:0">
                    <i class="{{ $icon }}"></i>
                </div>
                <div>
                    <div style="font-size:12.5px;font-weight:700;color:#111827">{{ $title }}</div>
                    <div style="font-size:11.5px;color:#6b7280;margin-top:1px">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- How-to note --}}
<div class="st-card" style="border-color:#fee2e2;background:linear-gradient(135deg,#fff5f5,#fef2f2)">
    <div class="st-card-body" style="padding:16px 20px">
        <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#ef4444;margin-bottom:10px">
            <i class="bx bx-help-circle me-1"></i> How to get an API Key
        </div>
        <ol style="margin:0;padding-left:18px;font-size:12.5px;color:#7f1d1d;line-height:2">
            <li>Go to <strong>console.cloud.google.com</strong> → Create or select a project</li>
            <li>Navigate to <strong>APIs &amp; Services → Library</strong></li>
            <li>Enable <strong>Maps JavaScript API</strong> and <strong>Places API</strong></li>
            <li>Go to <strong>APIs &amp; Services → Credentials → Create Credentials → API Key</strong></li>
            <li>Restrict the key to your domain in the <strong>HTTP referrers</strong> section</li>
        </ol>
    </div>
</div>

<div class="st-save-bar">
    <div class="st-save-bar-text">
        <i class="bx bx-shield-check"></i>
        The API key is used server-side and on the frontend for map embeds.
    </div>
    <button type="submit" class="st-btn-save" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
        <i class="bx bx-save"></i> Save Maps Settings
    </button>
</div>

</form>

@include('admin.settings._nav_end')
@endsection
