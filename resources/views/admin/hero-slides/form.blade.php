@extends('admin.layouts.app')
@php $editing = $slide->exists; @endphp
@section('title', $editing ? 'Edit Hero Slide' : 'Add Hero Slide')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }

/* ── banner ── */
.cf-banner {
    background: linear-gradient(135deg,#6c63ff 0%,#5a7cff 40%,#a78bfa 100%);
    border-radius: 20px; padding: 26px 32px; margin-bottom: 26px;
    display: flex; align-items: center; justify-content: space-between;
    box-shadow: 0 8px 32px rgba(108,99,255,.32); position: relative; overflow: hidden;
}
.cf-banner::before { content:''; position:absolute; top:-50px; right:-50px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.07); }
.cf-banner::after  { content:''; position:absolute; bottom:-70px; right:80px; width:170px; height:170px; border-radius:50%; background:rgba(255,255,255,.04); }
.cf-banner__left { position:relative; z-index:1; }
.cf-banner__icon { width:54px; height:54px; background:rgba(255,255,255,.22); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; color:#fff; margin-bottom:10px; backdrop-filter:blur(4px); }
.cf-banner__title { font-size:22px; font-weight:800; color:#fff; margin:0; letter-spacing:-.3px; }
.cf-banner__sub { font-size:13px; color:rgba(255,255,255,.78); margin-top:5px; display:flex; align-items:center; gap:6px; }
.cf-banner__sub a { color:rgba(255,255,255,.85); text-decoration:none; }
.cf-banner__sub a:hover { color:#fff; }
.cf-banner__badge { background:rgba(255,255,255,.2); color:#fff; border-radius:20px; padding:8px 18px; font-size:12.5px; font-weight:700; backdrop-filter:blur(4px); position:relative; z-index:1; display:flex; align-items:center; gap:8px; }

/* ── two-col grid (content + settings) — equal height columns ── */
.cf-grid {
    display:grid; grid-template-columns:1fr 390px; gap:22px;
    align-items:stretch;       /* both cols same height */
}
.cf-grid > div { display:flex; flex-direction:column; }
/* last card in each column stretches to fill remaining space */
.cf-grid > div > .cf-card:last-child { flex:1; margin-bottom:0; }

/* ── three-col grid for feature images ── */
.cf-img-grid {
    display:grid; grid-template-columns:repeat(3,1fr); gap:20px;
    margin-bottom:22px;
}
@media(max-width:900px){
    .cf-grid     { grid-template-columns:1fr; }
    .cf-img-grid { grid-template-columns:1fr; }
}

/* ── card ── */
.cf-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; margin-bottom:22px; }
.cf-card__head { padding:15px 22px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:12px; }
.cf-card__head-icon { width:38px; height:38px; border-radius:11px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
.cf-card__head-title { font-size:14px; font-weight:700; color:#1e293b; }
.cf-card__head-sub { font-size:11.5px; color:#94a3b8; margin-top:1px; }
.cf-card__body { padding:22px; }

.cf-card--blue   .cf-card__head { background:linear-gradient(135deg,#ede9fe,#dbeafe); }
.cf-card--blue   .cf-card__head-icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.cf-card--green  .cf-card__head { background:linear-gradient(135deg,#d1fae5,#dcfce7); }
.cf-card--green  .cf-card__head-icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.cf-card--amber  .cf-card__head { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.cf-card--amber  .cf-card__head-icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.cf-card--slate  .cf-card__head { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); }
.cf-card--slate  .cf-card__head-icon { background:linear-gradient(135deg,#475569,#64748b); color:#fff; }
.cf-card--purple .cf-card__head { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
.cf-card--purple .cf-card__head-icon { background:linear-gradient(135deg,#7c3aed,#a78bfa); color:#fff; }

/* ── fields ── */
.cf-label { display:flex; align-items:center; gap:6px; font-size:11.5px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px; }
.cf-label i { font-size:12px; color:#6c63ff; }
.cf-label .req { color:#f43f5e; font-size:14px; line-height:1; }
.cf-input {
    width:100%; padding:11px 14px; border:1.5px solid #e2e8f0; border-radius:11px;
    font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit;
    transition:border-color .18s, box-shadow .18s, background .18s; box-sizing:border-box;
}
.cf-input:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.cf-input::placeholder { color:#c4cdd6; }
.cf-select { width:100%; padding:11px 36px 11px 14px; border:1.5px solid #e2e8f0; border-radius:11px; font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; transition:border-color .18s, box-shadow .18s; cursor:pointer; }
.cf-select:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.cf-field { margin-bottom:18px; }
.cf-field:last-child { margin-bottom:0; }
.cf-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

/* ── gradient preview ── */
.gradient-preview { height:62px; border-radius:12px; margin-bottom:18px; border:1.5px solid rgba(0,0,0,.06); transition:background .25s; display:flex; align-items:center; justify-content:center; }
.gradient-preview span { font-size:10px; font-weight:700; color:rgba(0,0,0,.2); letter-spacing:.05em; }
.color-picker-row { display:flex; align-items:center; gap:10px; }
.color-picker-row input[type="color"] { width:44px; height:44px; border:1.5px solid #e2e8f0; border-radius:10px; padding:3px; cursor:pointer; flex-shrink:0; background:#f8fafc; }
.color-picker-row .cf-input { flex:1; font-family:monospace; font-size:13px; }

/* ── image slot (inside 3-col grid) ── */
.img-slot-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; display:flex; flex-direction:column; }
.img-slot-head { padding:13px 18px; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#fef3c7,#fde68a); display:flex; align-items:center; gap:10px; }
.img-slot-head-icon { width:34px; height:34px; border-radius:9px; background:linear-gradient(135deg,#f59e0b,#fbbf24); display:flex; align-items:center; justify-content:center; font-size:15px; color:#fff; flex-shrink:0; }
.img-slot-head-title { font-size:13.5px; font-weight:700; color:#1e293b; }
.img-slot-body { padding:18px; flex:1; display:flex; flex-direction:column; }

.img-upload-zone {
    border:2px dashed #c7d2fe; border-radius:14px; padding:22px 16px;
    text-align:center; cursor:pointer; background:#f5f3ff;
    transition:border-color .2s, background .2s; position:relative; overflow:hidden;
}
.img-upload-zone:hover { border-color:#6c63ff; background:#ede9fe; }
.img-upload-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.img-upload-zone i { font-size:28px; color:#a78bfa; margin-bottom:6px; display:block; }
.img-upload-zone .upz-title { font-size:13px; font-weight:700; color:#4f46e5; }
.img-upload-zone .upz-sub { font-size:11px; color:#94a3b8; margin-top:3px; }
.img-preview-current { display:flex; align-items:center; gap:10px; margin-bottom:12px; background:#f8fafc; border-radius:10px; padding:10px 12px; border:1.5px solid #e2e8f0; }
.img-preview-current img { width:48px; height:48px; border-radius:8px; object-fit:cover; border:2px solid #ddd6fe; }
.img-preview-current .ipc-text strong { display:block; font-size:12.5px; color:#1e293b; font-weight:700; }
.img-preview-current .ipc-text span { font-size:11px; color:#94a3b8; }
.img-new-preview { margin-top:10px; text-align:center; display:none; }
.img-new-preview img { max-height:80px; border-radius:10px; border:2px solid #ddd6fe; }
.del-check-row { display:flex; align-items:center; gap:6px; margin-top:8px; font-size:12px; color:#ef4444; cursor:pointer; }
.del-check-row input { cursor:pointer; }
.slot-fields { margin-top:14px; }
.slot-fields .cf-field { margin-bottom:12px; }
.slot-fields .cf-field:last-child { margin-bottom:0; }

/* ── toggles ── */
.toggle-row { display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid #f1f5f9; }
.toggle-row:first-child { padding-top:0; }
.toggle-row:last-child { border-bottom:none; padding-bottom:0; }
.toggle-info strong { display:block; font-size:13.5px; font-weight:700; color:#1e293b; margin-bottom:2px; }
.toggle-info span { font-size:12px; color:#94a3b8; }
.cf-switch { position:relative; display:inline-block; width:50px; height:27px; flex-shrink:0; }
.cf-switch input { opacity:0; width:0; height:0; }
.cf-slider { position:absolute; inset:0; border-radius:50px; background:#e2e8f0; transition:background .25s; cursor:pointer; }
.cf-slider::before { content:''; position:absolute; width:21px; height:21px; border-radius:50%; left:3px; top:3px; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.2); transition:transform .25s; }
.cf-switch input:checked + .cf-slider::before { transform:translateX(23px); }
.cf-switch--green  input:checked + .cf-slider { background:#10b981; }
.cf-switch--purple input:checked + .cf-slider { background:#6c63ff; }

/* ── sort ── */
.sort-wrap { display:flex; align-items:center; gap:14px; }
.sort-wrap .cf-input { max-width:120px; text-align:center; font-size:20px; font-weight:700; padding:10px; }
.sort-hint { font-size:12.5px; color:#94a3b8; line-height:1.6; }

/* ── action bar ── */
.cf-action-bar { background:#fff; border-radius:18px; padding:20px 26px; box-shadow:0 4px 24px rgba(0,0,0,.07); display:flex; align-items:center; justify-content:space-between; margin-top:22px; }
.cf-action-bar__left { font-size:13px; color:#94a3b8; display:flex; align-items:center; gap:8px; }
.cf-action-bar__left i { color:#f59e0b; }
.cf-action-bar__right { display:flex; align-items:center; gap:12px; }

.btn-cf-save { display:inline-flex; align-items:center; gap:10px; padding:12px 28px; border:none; border-radius:14px; font-size:14.5px; font-weight:700; color:#fff; cursor:pointer; background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 45%,#a78bfa 100%); box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .22s, box-shadow .22s; position:relative; overflow:hidden; letter-spacing:.02em; }
.btn-cf-save::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent); transform:translateX(-100%); transition:transform .55s ease; }
.btn-cf-save:hover::after { transform:translateX(100%); }
.btn-cf-save:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(108,99,255,.55); }
.btn-cf-save .si { width:28px; height:28px; background:rgba(255,255,255,.25); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; transition:transform .3s; position:relative; z-index:1; flex-shrink:0; }
.btn-cf-save:hover .si { transform:scale(1.15); }
.btn-cf-save .sl { position:relative; z-index:1; }
.btn-cf-cancel { display:inline-flex; align-items:center; gap:8px; padding:12px 20px; border-radius:14px; font-size:14px; font-weight:600; color:#475569; text-decoration:none; background:#f1f5f9; border:1.5px solid #e2e8f0; transition:all .2s; }
.btn-cf-cancel:hover { background:#e2e8f0; color:#1e293b; text-decoration:none; }

/* ── error banner ── */
.cf-errors { background:#fff1f2; border:1.5px solid #fecdd3; border-radius:14px; padding:16px 20px; margin-bottom:22px; }
.cf-errors__title { font-size:13px; font-weight:700; color:#be123c; margin-bottom:8px; display:flex; align-items:center; gap:8px; }
.cf-errors ul { margin:0; padding-left:18px; }
.cf-errors li { font-size:13px; color:#be123c; margin-bottom:3px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    @if ($errors->any())
    <div class="cf-errors">
        <div class="cf-errors__title"><i class="bi bi-exclamation-circle-fill"></i> Please fix the errors below:</div>
        <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Hero banner --}}
    <div class="cf-banner">
        <div class="cf-banner__left">
            <div class="cf-banner__icon">
                <i class="bi {{ $editing ? 'bi-pencil-square' : 'bi-plus-circle-fill' }}"></i>
            </div>
            <h4 class="cf-banner__title">{{ $editing ? 'Edit Hero Slide' : 'Add New Hero Slide' }}</h4>
            <div class="cf-banner__sub">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <a href="{{ route('admin.hero-slides.index') }}">Hero Slides</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <span>{{ $editing ? 'Edit' : 'Create' }}</span>
            </div>
        </div>
        <div class="cf-banner__badge">
            @if ($editing)
                <i class="bi bi-pencil"></i>
                Editing: {{ mb_substr($slide->title, 0, 32) }}{{ mb_strlen($slide->title) > 32 ? '…' : '' }}
            @else
                <i class="bi bi-stars"></i> New Slide
            @endif
        </div>
    </div>

    <form action="{{ $editing ? route('admin.hero-slides.update', $slide) : route('admin.hero-slides.store') }}"
          method="POST" enctype="multipart/form-data" id="slideForm">
        @csrf
        @if ($editing) @method('PUT') @endif

        {{-- ══ ROW 1: two-col equal-height grid ══ --}}
        <div class="cf-grid">

            {{-- LEFT: Slide Content --}}
            <div>
                <div class="cf-card cf-card--blue">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-type"></i></div>
                        <div>
                            <div class="cf-card__head-title">Slide Content</div>
                            <div class="cf-card__head-sub">Text and call-to-action shown on the slide</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-badge-cc-fill"></i> Eyebrow Label</label>
                            <input type="text" name="eyebrow" class="cf-input"
                                   placeholder="e.g. Limited Time, New Season, Daily Deals"
                                   value="{{ old('eyebrow', $slide->eyebrow) }}">
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-card-heading"></i> Heading <span class="req">*</span></label>
                            <input type="text" name="title" class="cf-input @error('title') is-invalid @enderror"
                                   placeholder="e.g. Up to 50% off at The Brand Outlet"
                                   value="{{ old('title', $slide->title) }}" required>
                            @error('title')
                                <div style="font-size:12px;color:#f43f5e;margin-top:5px;display:flex;align-items:center;gap:5px;">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-text-paragraph"></i> Subtitle</label>
                            <input type="text" name="subtitle" class="cf-input"
                                   placeholder="Short description shown below the heading"
                                   value="{{ old('subtitle', $slide->subtitle) }}">
                        </div>
                        <div class="cf-row">
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-cursor-fill"></i> Button Text</label>
                                <input type="text" name="button_text" class="cf-input"
                                       placeholder="Shop Now" value="{{ old('button_text', $slide->button_text) }}">
                            </div>
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-link-45deg"></i> Button URL</label>
                                <input type="text" name="button_url" class="cf-input"
                                       placeholder="/shop" value="{{ old('button_url', $slide->button_url) }}">
                            </div>
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-palette-fill"></i> Button Style</label>
                            <select name="button_style" class="cf-select">
                                <option value="dark"  {{ old('button_style', $slide->button_style) === 'dark'  ? 'selected' : '' }}>Dark — black button (for light backgrounds)</option>
                                <option value="white" {{ old('button_style', $slide->button_style) === 'white' ? 'selected' : '' }}>White — white button (for dark backgrounds)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Background + Sort + Visibility --}}
            <div>

                {{-- Background Gradient --}}
                <div class="cf-card cf-card--purple">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-palette2"></i></div>
                        <div>
                            <div class="cf-card__head-title">Background Gradient</div>
                            <div class="cf-card__head-sub">Pick two colours for the slide background</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div id="gradientPreview" class="gradient-preview"
                             style="background: linear-gradient(135deg, {{ old('bg_color_start', $slide->bg_color_start ?? '#6c63ff') }} 0%, {{ old('bg_color_end', $slide->bg_color_end ?? '#a78bfa') }} 100%);">
                            <span>GRADIENT PREVIEW</span>
                        </div>
                        <div class="cf-row">
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-circle-half"></i> Start Colour</label>
                                <div class="color-picker-row">
                                    <input type="color" id="startPicker" value="{{ old('bg_color_start', $slide->bg_color_start ?? '#6c63ff') }}">
                                    <input type="text" name="bg_color_start" id="startHex" class="cf-input"
                                           value="{{ old('bg_color_start', $slide->bg_color_start ?? '#6c63ff') }}" maxlength="20" placeholder="#6c63ff">
                                </div>
                            </div>
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-circle-fill"></i> End Colour</label>
                                <div class="color-picker-row">
                                    <input type="color" id="endPicker" value="{{ old('bg_color_end', $slide->bg_color_end ?? '#a78bfa') }}">
                                    <input type="text" name="bg_color_end" id="endHex" class="cf-input"
                                           value="{{ old('bg_color_end', $slide->bg_color_end ?? '#a78bfa') }}" maxlength="20" placeholder="#a78bfa">
                                </div>
                            </div>
                        </div>
                        <div class="cf-field" style="margin-bottom:0;">
                            <label class="cf-label"><i class="bi bi-fonts"></i> Text Colour</label>
                            <select name="text_color" class="cf-select">
                                <option value="light" {{ old('text_color', $slide->text_color) === 'light' ? 'selected' : '' }}>Light (white) — use on dark backgrounds</option>
                                <option value="dark"  {{ old('text_color', $slide->text_color) === 'dark'  ? 'selected' : '' }}>Dark (black) — use on light backgrounds</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="cf-card cf-card--slate">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-sort-numeric-up-alt"></i></div>
                        <div>
                            <div class="cf-card__head-title">Sort Order</div>
                            <div class="cf-card__head-sub">Lower number = appears first in slider</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="sort-wrap">
                            <input type="number" name="sort_order" class="cf-input"
                                   value="{{ old('sort_order', $slide->sort_order ?? 0) }}" min="0" max="9999">
                            <div class="sort-hint">
                                <i class="bi bi-info-circle" style="color:#6c63ff;"></i>
                                Use <strong>0</strong> for default ordering.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Visibility --}}
                <div class="cf-card cf-card--green" style="margin-bottom:0;">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-toggles"></i></div>
                        <div>
                            <div class="cf-card__head-title">Visibility</div>
                            <div class="cf-card__head-sub">Control whether this slide is shown</div>
                        </div>
                    </div>
                    <div class="cf-card__body" style="padding-top:18px;">
                        <div class="toggle-row" style="padding-top:0;">
                            <div class="toggle-info">
                                <strong><i class="bi bi-eye-fill" style="color:#10b981;font-size:12px;margin-right:4px;"></i>Active</strong>
                                <span>Slide is visible in the hero slider</span>
                            </div>
                            <label class="cf-switch cf-switch--green">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $slide->is_active ?? true))>
                                <span class="cf-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ ROW 2: Feature Images — full width, 3 equal columns ══ --}}
        <div class="cf-img-grid" style="margin-top:22px;">
            @foreach ([1,2,3] as $n)
            @php
                $imgPath  = $slide->{"img{$n}_path"} ?? null;
                $imgLabel = old("img{$n}_label", $slide->{"img{$n}_label"} ?? '');
                $imgUrl   = old("img{$n}_url",   $slide->{"img{$n}_url"}   ?? '');
            @endphp
            <div class="img-slot-card">
                <div class="img-slot-head">
                    <div class="img-slot-head-icon"><i class="bi bi-image"></i></div>
                    <div class="img-slot-head-title">Feature Image {{ $n }}</div>
                </div>
                <div class="img-slot-body">
                    @if($imgPath)
                    <div class="img-preview-current">
                        <img src="{{ asset('storage/'.$imgPath) }}" alt="">
                        <div class="ipc-text">
                            <strong>Current Image</strong>
                            <span>Upload new to replace</span>
                        </div>
                    </div>
                    @endif

                    <div class="img-upload-zone">
                        <input type="file" name="img{{ $n }}" accept="image/*" id="imgInput{{ $n }}">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                        <div class="upz-title">{{ $imgPath ? 'Replace image' : 'Upload image' }}</div>
                        <div class="upz-sub">PNG, JPG, WEBP · max 2 MB</div>
                    </div>

                    <div class="img-new-preview" id="newPreview{{ $n }}">
                        <img id="newPreviewImg{{ $n }}" src="" alt="">
                    </div>

                    @if($imgPath)
                    <label class="del-check-row">
                        <input type="checkbox" name="delete_img{{ $n }}" value="1">
                        <i class="bi bi-trash-fill"></i> Delete this image
                    </label>
                    @endif

                    <div class="slot-fields">
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-tag-fill"></i> Label</label>
                            <input type="text" name="img{{ $n }}_label" class="cf-input"
                                   placeholder="e.g. Top Picks" value="{{ $imgLabel }}">
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-link-45deg"></i> Link URL</label>
                            <input type="text" name="img{{ $n }}_url" class="cf-input"
                                   placeholder="/shop/category" value="{{ $imgUrl }}">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Action bar --}}
        <div class="cf-action-bar">
            <div class="cf-action-bar__left">
                <i class="bi bi-info-circle-fill"></i>
                {{ $editing ? 'Changes are saved immediately after submission.' : 'Fill in all required fields before saving.' }}
            </div>
            <div class="cf-action-bar__right">
                <a href="{{ route('admin.hero-slides.index') }}" class="btn-cf-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn-cf-save">
                    <span class="si"><i class="bi {{ $editing ? 'bi-check-lg' : 'bi-plus-lg' }}"></i></span>
                    <span class="sl">{{ $editing ? 'Update Slide' : 'Create Slide' }}</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
// ── Image preview ──
@foreach([1,2,3] as $n)
document.getElementById('imgInput{{ $n }}').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('newPreviewImg{{ $n }}').src = e.target.result;
        document.getElementById('newPreview{{ $n }}').style.display = 'block';
    };
    reader.readAsDataURL(file);
});
@endforeach

// ── Gradient preview sync ──
function syncGradient() {
    var s = document.getElementById('startHex').value;
    var e = document.getElementById('endHex').value;
    document.getElementById('gradientPreview').style.background =
        'linear-gradient(135deg, ' + s + ' 0%, ' + e + ' 100%)';
}
document.getElementById('startPicker').addEventListener('input', function () {
    document.getElementById('startHex').value = this.value; syncGradient();
});
document.getElementById('endPicker').addEventListener('input', function () {
    document.getElementById('endHex').value = this.value; syncGradient();
});
document.getElementById('startHex').addEventListener('input', function () {
    document.getElementById('startPicker').value = this.value; syncGradient();
});
document.getElementById('endHex').addEventListener('input', function () {
    document.getElementById('endPicker').value = this.value; syncGradient();
});
</script>
@endpush
