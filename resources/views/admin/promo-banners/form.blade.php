@extends('admin.layouts.app')
@php $editing = $banner->exists; @endphp
@section('title', $editing ? 'Edit Promo Banner' : 'Add Promo Banner')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }
.cf-banner { background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 40%,#a78bfa 100%); border-radius:20px; padding:26px 32px; margin-bottom:26px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 8px 32px rgba(108,99,255,.32); position:relative; overflow:hidden; }
.cf-banner::before { content:''; position:absolute; top:-50px; right:-50px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.07); }
.cf-banner__left { position:relative; z-index:1; }
.cf-banner__icon { width:54px; height:54px; background:rgba(255,255,255,.22); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; color:#fff; margin-bottom:10px; }
.cf-banner__title { font-size:22px; font-weight:800; color:#fff; margin:0; }
.cf-banner__sub { font-size:13px; color:rgba(255,255,255,.78); margin-top:5px; display:flex; align-items:center; gap:6px; }
.cf-banner__sub a { color:rgba(255,255,255,.85); text-decoration:none; }
.cf-banner__badge { background:rgba(255,255,255,.2); color:#fff; border-radius:20px; padding:8px 18px; font-size:12.5px; font-weight:700; backdrop-filter:blur(4px); position:relative; z-index:1; display:flex; align-items:center; gap:8px; }

.cf-grid { display:grid; grid-template-columns:1fr 390px; gap:22px; align-items:stretch; }
.cf-grid > div { display:flex; flex-direction:column; }
.cf-grid > div > .cf-card:last-child { flex:1; margin-bottom:0; }
@media(max-width:900px){ .cf-grid { grid-template-columns:1fr; } }

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

.cf-label { display:flex; align-items:center; gap:6px; font-size:11.5px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px; }
.cf-label i { font-size:12px; color:#6c63ff; }
.cf-label .req { color:#f43f5e; font-size:14px; line-height:1; }
.cf-input { width:100%; padding:11px 14px; border:1.5px solid #e2e8f0; border-radius:11px; font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit; transition:border-color .18s,box-shadow .18s; box-sizing:border-box; }
.cf-input:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.cf-select { width:100%; padding:11px 36px 11px 14px; border:1.5px solid #e2e8f0; border-radius:11px; font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; transition:border-color .18s; cursor:pointer; }
.cf-select:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.cf-field { margin-bottom:18px; }
.cf-field:last-child { margin-bottom:0; }
.cf-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

.gradient-preview { height:62px; border-radius:12px; margin-bottom:18px; border:1.5px solid rgba(0,0,0,.06); display:flex; align-items:center; justify-content:center; transition:background .25s; }
.gradient-preview span { font-size:10px; font-weight:700; color:rgba(255,255,255,.5); letter-spacing:.05em; }
.color-picker-row { display:flex; align-items:center; gap:10px; }
.color-picker-row input[type="color"] { width:44px; height:44px; border:1.5px solid #e2e8f0; border-radius:10px; padding:3px; cursor:pointer; flex-shrink:0; }
.color-picker-row .cf-input { flex:1; font-family:monospace; font-size:13px; }

.img-upload-zone { border:2px dashed #c7d2fe; border-radius:14px; padding:22px 16px; text-align:center; cursor:pointer; background:#f5f3ff; transition:border-color .2s,background .2s; position:relative; overflow:hidden; }
.img-upload-zone:hover { border-color:#6c63ff; background:#ede9fe; }
.img-upload-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.img-upload-zone i { font-size:28px; color:#a78bfa; margin-bottom:6px; display:block; }
.img-upload-zone .upz-title { font-size:13px; font-weight:700; color:#4f46e5; }
.img-upload-zone .upz-sub { font-size:11px; color:#94a3b8; margin-top:3px; }
.img-preview-current { display:flex; align-items:center; gap:10px; margin-bottom:12px; background:#f8fafc; border-radius:10px; padding:10px 12px; border:1.5px solid #e2e8f0; }
.img-preview-current img { width:80px; height:56px; border-radius:8px; object-fit:cover; border:2px solid #ddd6fe; }
.img-preview-current .ipc-text strong { display:block; font-size:12.5px; color:#1e293b; font-weight:700; }
.img-preview-current .ipc-text span { font-size:11px; color:#94a3b8; }
.img-new-preview { margin-top:10px; text-align:center; display:none; }
.img-new-preview img { max-height:120px; border-radius:10px; border:2px solid #ddd6fe; }
.del-check-row { display:flex; align-items:center; gap:6px; margin-top:8px; font-size:12px; color:#ef4444; cursor:pointer; }

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
.cf-switch--green input:checked + .cf-slider { background:#10b981; }

.sort-wrap { display:flex; align-items:center; gap:14px; }
.sort-wrap .cf-input { max-width:120px; text-align:center; font-size:20px; font-weight:700; padding:10px; }
.sort-hint { font-size:12.5px; color:#94a3b8; line-height:1.6; }

.cf-action-bar { background:#fff; border-radius:18px; padding:20px 26px; box-shadow:0 4px 24px rgba(0,0,0,.07); display:flex; align-items:center; justify-content:space-between; margin-top:22px; }
.cf-action-bar__left { font-size:13px; color:#94a3b8; display:flex; align-items:center; gap:8px; }
.cf-action-bar__right { display:flex; align-items:center; gap:12px; }
.btn-cf-save { display:inline-flex; align-items:center; gap:10px; padding:12px 28px; border:none; border-radius:14px; font-size:14.5px; font-weight:700; color:#fff; cursor:pointer; background:linear-gradient(135deg,#6c63ff,#5a7cff 45%,#a78bfa); box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .22s,box-shadow .22s; position:relative; overflow:hidden; }
.btn-cf-save:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(108,99,255,.55); }
.btn-cf-save .si { width:28px; height:28px; background:rgba(255,255,255,.25); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; }
.btn-cf-cancel { display:inline-flex; align-items:center; gap:8px; padding:12px 20px; border-radius:14px; font-size:14px; font-weight:600; color:#475569; text-decoration:none; background:#f1f5f9; border:1.5px solid #e2e8f0; transition:all .2s; }
.btn-cf-cancel:hover { background:#e2e8f0; color:#1e293b; text-decoration:none; }
.cf-errors { background:#fff1f2; border:1.5px solid #fecdd3; border-radius:14px; padding:16px 20px; margin-bottom:22px; }
.cf-errors__title { font-size:13px; font-weight:700; color:#be123c; margin-bottom:8px; display:flex; align-items:center; gap:8px; }
.cf-errors ul { margin:0; padding-left:18px; }
.cf-errors li { font-size:13px; color:#be123c; margin-bottom:3px; }

/* live banner preview */
.banner-live-preview {
    border-radius:16px; overflow:hidden; margin-bottom:22px;
    box-shadow:0 8px 32px rgba(0,0,0,.18); display:flex; min-height:160px;
    border:2px solid rgba(0,0,0,.06);
}
.blp-left { flex:1; padding:28px 32px; display:flex; flex-direction:column; justify-content:center; }
.blp-eyebrow { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; opacity:.75; margin-bottom:8px; }
.blp-title { font-size:22px; font-weight:800; line-height:1.2; margin-bottom:8px; }
.blp-subtitle { font-size:13px; opacity:.8; margin-bottom:14px; }
.blp-btn { display:inline-block; padding:8px 22px; border-radius:50px; font-size:13px; font-weight:700; text-decoration:none; }
.blp-btn--white   { background:#fff; color:#333; }
.blp-btn--dark    { background:#333; color:#fff; }
.blp-btn--outline { background:transparent; border:2px solid currentColor; }
.blp-right { width:260px; flex-shrink:0; background:rgba(0,0,0,.12); display:flex; align-items:center; justify-content:center; overflow:hidden; }
.blp-right img { width:100%; height:100%; object-fit:cover; }
.blp-right .blp-placeholder { font-size:11px; font-weight:600; opacity:.5; text-align:center; padding:16px; }
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

    <div class="cf-banner">
        <div class="cf-banner__left">
            <div class="cf-banner__icon"><i class="bi {{ $editing ? 'bi-pencil-square' : 'bi-plus-circle-fill' }}"></i></div>
            <h4 class="cf-banner__title">{{ $editing ? 'Edit Promo Banner' : 'Add New Promo Banner' }}</h4>
            <div class="cf-banner__sub">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <a href="{{ route('admin.promo-banners.index') }}">Promo Banners</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <span>{{ $editing ? 'Edit' : 'Create' }}</span>
            </div>
        </div>
        <div class="cf-banner__badge">
            @if ($editing)
                <i class="bi bi-pencil"></i> Editing: {{ mb_substr($banner->title, 0, 32) }}
            @else
                <i class="bi bi-stars"></i> New Banner
            @endif
        </div>
    </div>

    {{-- Live preview --}}
    <div class="banner-live-preview" id="livePreview"
         style="background: linear-gradient(135deg, {{ old('bg_color_start', $banner->bg_color_start ?? '#3d1c02') }} 0%, {{ old('bg_color_end', $banner->bg_color_end ?? '#6b3a1f') }} 100%);">
        @php $previewColor = old('text_color', $banner->text_color ?? 'light') === 'light' ? '#fff' : '#191919'; @endphp
        <div class="blp-left" style="color:{{ $previewColor }};" id="blpLeft">
            <div class="blp-eyebrow" id="blpEyebrow">{{ old('eyebrow', $banner->eyebrow ?? 'EYEBROW') }}</div>
            <div class="blp-title"   id="blpTitle">{{ old('title', $banner->title ?? 'Banner Title') }}</div>
            <div class="blp-subtitle" id="blpSubtitle">{{ old('subtitle', $banner->subtitle ?? 'Your subtitle text here') }}</div>
            @if (old('button_text', $banner->button_text))
            <a class="blp-btn blp-btn--{{ old('button_style', $banner->button_style ?? 'white') }}" id="blpBtn" href="#">
                {{ old('button_text', $banner->button_text) }}
            </a>
            @else
            <a class="blp-btn blp-btn--white" id="blpBtn" href="#" style="display:none;"></a>
            @endif
        </div>
        <div class="blp-right" id="blpRight">
            @if ($editing && $banner->image_path)
                <img src="{{ $banner->imageUrl() }}" alt="" id="blpImg">
            @else
                <div class="blp-placeholder" id="blpPlaceholder"><i class="bi bi-image" style="font-size:28px;display:block;margin-bottom:6px;"></i>Image preview</div>
                <img src="" alt="" id="blpImg" style="display:none;">
            @endif
        </div>
    </div>

    <form action="{{ $editing ? route('admin.promo-banners.update', $banner) : route('admin.promo-banners.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="cf-grid">

            {{-- LEFT: Content --}}
            <div>
                <div class="cf-card cf-card--blue">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-type"></i></div>
                        <div>
                            <div class="cf-card__head-title">Banner Content</div>
                            <div class="cf-card__head-sub">Text and call-to-action shown on the banner</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-badge-cc-fill"></i> Eyebrow Label</label>
                            <input type="text" name="eyebrow" id="inp-eyebrow" class="cf-input"
                                   placeholder="e.g. WEEKLY DROP, NEW ARRIVALS"
                                   value="{{ old('eyebrow', $banner->eyebrow) }}">
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-card-heading"></i> Title <span class="req">*</span></label>
                            <input type="text" name="title" id="inp-title" class="cf-input @error('title') is-invalid @enderror"
                                   placeholder="e.g. The Auction Hub"
                                   value="{{ old('title', $banner->title) }}" required>
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-text-paragraph"></i> Subtitle</label>
                            <input type="text" name="subtitle" id="inp-subtitle" class="cf-input"
                                   placeholder="Short description below the title"
                                   value="{{ old('subtitle', $banner->subtitle) }}">
                        </div>
                        <div class="cf-row">
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-cursor-fill"></i> Button Text</label>
                                <input type="text" name="button_text" id="inp-btn-text" class="cf-input"
                                       placeholder="Bid now" value="{{ old('button_text', $banner->button_text) }}">
                            </div>
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-link-45deg"></i> Button URL</label>
                                <input type="text" name="button_url" class="cf-input"
                                       placeholder="/auctions" value="{{ old('button_url', $banner->button_url) }}">
                            </div>
                        </div>
                        <div class="cf-field" style="margin-bottom:0;">
                            <label class="cf-label"><i class="bi bi-palette-fill"></i> Button Style</label>
                            <select name="button_style" id="inp-btn-style" class="cf-select">
                                @foreach(['white'=>'White pill (for dark backgrounds)','dark'=>'Dark (for light backgrounds)','outline'=>'Outline / ghost'] as $v=>$l)
                                <option value="{{ $v }}" {{ old('button_style', $banner->button_style) === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Banner Image --}}
                <div class="cf-card cf-card--amber" style="margin-bottom:0;">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-image"></i></div>
                        <div>
                            <div class="cf-card__head-title">Banner Image</div>
                            <div class="cf-card__head-sub">Large image shown on the right side</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        @if ($editing && $banner->image_path)
                        <div class="img-preview-current">
                            <img src="{{ $banner->imageUrl() }}" alt="">
                            <div class="ipc-text">
                                <strong>Current Image</strong>
                                <span>Upload new to replace</span>
                            </div>
                        </div>
                        @endif
                        <div class="img-upload-zone">
                            <input type="file" name="image" id="imageInput" accept="image/*">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <div class="upz-title">{{ ($editing && $banner->image_path) ? 'Replace image' : 'Upload image' }}</div>
                            <div class="upz-sub">PNG, JPG, WEBP · max 4 MB</div>
                        </div>
                        <div class="img-new-preview" id="newPreview">
                            <img id="newPreviewImg" src="" alt="">
                        </div>
                        @if ($editing && $banner->image_path)
                        <label class="del-check-row">
                            <input type="checkbox" name="delete_image" value="1">
                            <i class="bi bi-trash-fill"></i> Delete this image
                        </label>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: Background + Sort + Visibility --}}
            <div>
                <div class="cf-card cf-card--purple">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-palette2"></i></div>
                        <div>
                            <div class="cf-card__head-title">Background Gradient</div>
                            <div class="cf-card__head-sub">Left-side background colours</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div id="gradientPreview" class="gradient-preview"
                             style="background:linear-gradient(135deg,{{ old('bg_color_start',$banner->bg_color_start??'#3d1c02') }} 0%,{{ old('bg_color_end',$banner->bg_color_end??'#6b3a1f') }} 100%);">
                            <span>GRADIENT PREVIEW</span>
                        </div>
                        <div class="cf-row">
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-circle-half"></i> Start Colour</label>
                                <div class="color-picker-row">
                                    <input type="color" id="startPicker" value="{{ old('bg_color_start',$banner->bg_color_start??'#3d1c02') }}">
                                    <input type="text" name="bg_color_start" id="startHex" class="cf-input"
                                           value="{{ old('bg_color_start',$banner->bg_color_start??'#3d1c02') }}" maxlength="20">
                                </div>
                            </div>
                            <div class="cf-field">
                                <label class="cf-label"><i class="bi bi-circle-fill"></i> End Colour</label>
                                <div class="color-picker-row">
                                    <input type="color" id="endPicker" value="{{ old('bg_color_end',$banner->bg_color_end??'#6b3a1f') }}">
                                    <input type="text" name="bg_color_end" id="endHex" class="cf-input"
                                           value="{{ old('bg_color_end',$banner->bg_color_end??'#6b3a1f') }}" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="cf-field" style="margin-bottom:0;">
                            <label class="cf-label"><i class="bi bi-fonts"></i> Text Colour</label>
                            <select name="text_color" id="inp-text-color" class="cf-select">
                                <option value="light" {{ old('text_color',$banner->text_color)==='light'?'selected':'' }}>Light (white) — for dark backgrounds</option>
                                <option value="dark"  {{ old('text_color',$banner->text_color)==='dark' ?'selected':'' }}>Dark (black) — for light backgrounds</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cf-card cf-card--amber">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-layout-text-sidebar"></i></div>
                        <div>
                            <div class="cf-card__head-title">Placement</div>
                            <div class="cf-card__head-sub">Where on the homepage this banner appears</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="cf-field" style="margin-bottom:0;">
                            <label class="cf-label"><i class="bi bi-pin-map-fill"></i> Position <span class="req">*</span></label>
                            <select name="placement" class="cf-select">
                                <option value="top"    {{ old('placement', $banner->placement ?? 'top') === 'top'    ? 'selected' : '' }}>
                                    Top — between categories &amp; special offers
                                </option>
                                <option value="bottom" {{ old('placement', $banner->placement ?? 'top') === 'bottom' ? 'selected' : '' }}>
                                    Bottom — after featured products
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cf-card cf-card--slate">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-sort-numeric-up-alt"></i></div>
                        <div>
                            <div class="cf-card__head-title">Sort Order</div>
                            <div class="cf-card__head-sub">Lower number = higher priority when random picks multiple</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="sort-wrap">
                            <input type="number" name="sort_order" class="cf-input"
                                   value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0" max="9999">
                            <div class="sort-hint"><i class="bi bi-info-circle" style="color:#6c63ff;"></i> Use <strong>0</strong> for default.</div>
                        </div>
                    </div>
                </div>

                <div class="cf-card cf-card--green" style="margin-bottom:0;">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-toggles"></i></div>
                        <div>
                            <div class="cf-card__head-title">Visibility</div>
                            <div class="cf-card__head-sub">Active banners are eligible for random display</div>
                        </div>
                    </div>
                    <div class="cf-card__body" style="padding-top:18px;">
                        <div class="toggle-row" style="padding-top:0;">
                            <div class="toggle-info">
                                <strong><i class="bi bi-eye-fill" style="color:#10b981;font-size:12px;margin-right:4px;"></i> Active</strong>
                                <span>Banner is eligible to appear on the storefront</span>
                            </div>
                            <label class="cf-switch cf-switch--green">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $banner->is_active ?? true))>
                                <span class="cf-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cf-action-bar">
            <div class="cf-action-bar__left"><i class="bi bi-info-circle-fill" style="color:#f59e0b;"></i>
                {{ $editing ? 'Changes are saved immediately.' : 'Fill required fields before saving.' }}
            </div>
            <div class="cf-action-bar__right">
                <a href="{{ route('admin.promo-banners.index') }}" class="btn-cf-cancel"><i class="bi bi-x-lg"></i> Cancel</a>
                <button type="submit" class="btn-cf-save">
                    <span class="si"><i class="bi {{ $editing ? 'bi-check-lg' : 'bi-plus-lg' }}"></i></span>
                    {{ $editing ? 'Update Banner' : 'Create Banner' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Image preview
document.getElementById('imageInput').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        var img = document.getElementById('blpImg');
        var placeholder = document.getElementById('blpPlaceholder');
        img.src = e.target.result;
        img.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
        document.getElementById('newPreviewImg').src = e.target.result;
        document.getElementById('newPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// Gradient sync
function syncGradient() {
    var s = document.getElementById('startHex').value;
    var e = document.getElementById('endHex').value;
    var grad = 'linear-gradient(135deg, ' + s + ' 0%, ' + e + ' 100%)';
    document.getElementById('gradientPreview').style.background = grad;
    document.getElementById('livePreview').style.background = grad;
}
document.getElementById('startPicker').addEventListener('input', function () { document.getElementById('startHex').value = this.value; syncGradient(); });
document.getElementById('endPicker').addEventListener('input', function () { document.getElementById('endHex').value = this.value; syncGradient(); });
document.getElementById('startHex').addEventListener('input', function () { document.getElementById('startPicker').value = this.value; syncGradient(); });
document.getElementById('endHex').addEventListener('input', function () { document.getElementById('endPicker').value = this.value; syncGradient(); });

// Text color sync
document.getElementById('inp-text-color').addEventListener('change', function () {
    var color = this.value === 'light' ? '#fff' : '#191919';
    document.getElementById('blpLeft').style.color = color;
});

// Live text preview
['inp-eyebrow','inp-title','inp-subtitle','inp-btn-text'].forEach(function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('input', function () {
        var map = { 'inp-eyebrow': 'blpEyebrow', 'inp-title': 'blpTitle', 'inp-subtitle': 'blpSubtitle', 'inp-btn-text': null };
        if (id === 'inp-btn-text') {
            var btn = document.getElementById('blpBtn');
            btn.textContent = this.value;
            btn.style.display = this.value ? '' : 'none';
        } else {
            document.getElementById(map[id]).textContent = this.value;
        }
    });
});

// Button style sync
document.getElementById('inp-btn-style').addEventListener('change', function () {
    var btn = document.getElementById('blpBtn');
    btn.className = 'blp-btn blp-btn--' + this.value;
});
</script>
@endpush
