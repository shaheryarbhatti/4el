@extends('admin.layouts.app')
@php $editing = $brand->exists; @endphp
@section('title', $editing ? 'Edit Brand' : 'Add Brand')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }

/* ── banner ── */
.cf-banner {
    background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 40%,#a78bfa 100%);
    border-radius:20px; padding:26px 32px; margin-bottom:26px;
    display:flex; align-items:center; justify-content:space-between;
    box-shadow:0 8px 32px rgba(108,99,255,.32); position:relative; overflow:hidden;
}
.cf-banner::before { content:''; position:absolute; top:-50px; right:-50px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.07); }
.cf-banner::after  { content:''; position:absolute; bottom:-70px; right:80px; width:170px; height:170px; border-radius:50%; background:rgba(255,255,255,.04); }
.cf-banner__left { position:relative; z-index:1; }
.cf-banner__icon { width:54px; height:54px; background:rgba(255,255,255,.22); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; color:#fff; margin-bottom:10px; }
.cf-banner__title { font-size:22px; font-weight:800; color:#fff; margin:0; letter-spacing:-.3px; }
.cf-banner__sub { font-size:13px; color:rgba(255,255,255,.78); margin-top:5px; display:flex; align-items:center; gap:6px; }
.cf-banner__sub a { color:rgba(255,255,255,.85); text-decoration:none; }
.cf-banner__sub a:hover { color:#fff; }
.cf-banner__badge { background:rgba(255,255,255,.2); color:#fff; border-radius:20px; padding:8px 18px; font-size:12.5px; font-weight:700; position:relative; z-index:1; display:flex; align-items:center; gap:8px; }

/* ── grid ── */
.cf-grid { display:grid; grid-template-columns:1fr 390px; gap:22px; align-items:start; }

/* ── cards ── */
.cf-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; margin-bottom:22px; }
.cf-card:last-child { margin-bottom:0; }
.cf-card__head { padding:15px 22px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:12px; }
.cf-card__head-icon { width:38px; height:38px; border-radius:11px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
.cf-card__head-title { font-size:14px; font-weight:700; color:#1e293b; }
.cf-card__head-sub   { font-size:11.5px; color:#94a3b8; margin-top:1px; }
.cf-card__body { padding:22px; }

.cf-card--blue  .cf-card__head { background:linear-gradient(135deg,#ede9fe,#dbeafe); }
.cf-card--blue  .cf-card__head-icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.cf-card--green .cf-card__head { background:linear-gradient(135deg,#d1fae5,#dcfce7); }
.cf-card--green .cf-card__head-icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.cf-card--amber .cf-card__head { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.cf-card--amber .cf-card__head-icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.cf-card--slate .cf-card__head { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); }
.cf-card--slate .cf-card__head-icon { background:linear-gradient(135deg,#475569,#64748b); color:#fff; }

/* ── fields ── */
.cf-label { display:flex; align-items:center; gap:6px; font-size:11.5px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px; }
.cf-label i { font-size:12px; color:#6c63ff; }
.cf-label .req { color:#f43f5e; font-size:14px; line-height:1; }
.cf-input { width:100%; padding:11px 14px; border:1.5px solid #e2e8f0; border-radius:11px; font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit; transition:border-color .18s, box-shadow .18s, background .18s; box-sizing:border-box; }
.cf-input:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.cf-input::placeholder { color:#c4cdd6; }
.cf-input.is-invalid { border-color:#f43f5e; }

/* ── image upload ── */
.img-upload-zone { border:2px dashed #c7d2fe; border-radius:14px; padding:26px 20px; text-align:center; cursor:pointer; background:#f5f3ff; transition:border-color .2s, background .2s; position:relative; overflow:hidden; }
.img-upload-zone:hover { border-color:#6c63ff; background:#ede9fe; }
.img-upload-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.img-upload-zone i { font-size:34px; color:#a78bfa; margin-bottom:8px; display:block; }
.img-upload-zone .upz-title { font-size:13.5px; font-weight:700; color:#4f46e5; }
.img-upload-zone .upz-sub   { font-size:11.5px; color:#94a3b8; margin-top:3px; }

.img-preview-current { display:flex; align-items:center; gap:14px; margin-bottom:14px; background:#f8fafc; border-radius:12px; padding:12px 14px; border:1.5px solid #e2e8f0; }
.img-preview-current img { width:64px; height:64px; border-radius:10px; object-fit:contain; border:2px solid #ddd6fe; background:#fff; padding:4px; }
.img-preview-current .ipc-text strong { display:block; font-size:13px; color:#1e293b; font-weight:700; }
.img-preview-current .ipc-text span   { font-size:12px; color:#94a3b8; }
.img-new-preview { margin-top:14px; text-align:center; display:none; }
.img-new-preview img { max-height:120px; border-radius:12px; border:2px solid #ddd6fe; background:#fff; padding:4px; }
.img-new-preview .fnm { font-size:12px; color:#6c63ff; font-weight:600; margin-top:6px; }

/* ── toggles ── */
.toggle-row { display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid #f1f5f9; }
.toggle-row:first-child { padding-top:0; }
.toggle-row:last-child  { border-bottom:none; padding-bottom:0; }
.toggle-info strong { display:block; font-size:13.5px; font-weight:700; color:#1e293b; margin-bottom:2px; }
.toggle-info span   { font-size:12px; color:#94a3b8; }
.cf-switch { position:relative; display:inline-block; width:50px; height:27px; flex-shrink:0; }
.cf-switch input { opacity:0; width:0; height:0; }
.cf-slider { position:absolute; inset:0; border-radius:50px; background:#e2e8f0; transition:background .25s; cursor:pointer; }
.cf-slider::before { content:''; position:absolute; width:21px; height:21px; border-radius:50%; left:3px; top:3px; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.2); transition:transform .25s; }
.cf-switch input:checked + .cf-slider::before { transform:translateX(23px); }
.cf-switch--amber  input:checked + .cf-slider { background:#f59e0b; }
.cf-switch--green  input:checked + .cf-slider { background:#10b981; }

/* ── sort ── */
.sort-wrap { display:flex; align-items:center; gap:14px; }
.sort-wrap .cf-input { max-width:120px; text-align:center; font-size:20px; font-weight:700; padding:10px; }
.sort-hint { font-size:12.5px; color:#94a3b8; line-height:1.6; }

/* ── action bar ── */
.cf-action-bar { background:#fff; border-radius:18px; padding:20px 26px; box-shadow:0 4px 24px rgba(0,0,0,.07); display:flex; align-items:center; justify-content:space-between; margin-top:22px; }
.cf-action-bar__left { font-size:13px; color:#94a3b8; display:flex; align-items:center; gap:8px; }
.cf-action-bar__left i { color:#f59e0b; }
.cf-action-bar__right { display:flex; align-items:center; gap:12px; }

.btn-cf-save {
    display:inline-flex; align-items:center; gap:10px; padding:12px 28px; border:none; border-radius:14px;
    font-size:14.5px; font-weight:700; color:#fff; cursor:pointer;
    background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 45%,#a78bfa 100%);
    box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .22s, box-shadow .22s;
    position:relative; overflow:hidden; letter-spacing:.02em;
}
.btn-cf-save::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent); transform:translateX(-100%); transition:transform .55s ease; }
.btn-cf-save:hover::after { transform:translateX(100%); }
.btn-cf-save:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(108,99,255,.55); }
.btn-cf-save:active { transform:translateY(-1px); }
.btn-cf-save .si { width:28px; height:28px; background:rgba(255,255,255,.25); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; transition:transform .3s; position:relative; z-index:1; flex-shrink:0; }
.btn-cf-save:hover .si { transform:scale(1.15); }
.btn-cf-save .sl { position:relative; z-index:1; }

.btn-cf-cancel { display:inline-flex; align-items:center; gap:8px; padding:12px 20px; border-radius:14px; font-size:14px; font-weight:600; color:#475569; text-decoration:none; background:#f1f5f9; border:1.5px solid #e2e8f0; transition:all .2s; }
.btn-cf-cancel:hover { background:#e2e8f0; color:#1e293b; text-decoration:none; }

/* ── errors ── */
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
            <h4 class="cf-banner__title">{{ $editing ? 'Edit Brand' : 'Add New Brand' }}</h4>
            <div class="cf-banner__sub">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <a href="{{ route('admin.brands.index') }}">Brands</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <span>{{ $editing ? 'Edit' : 'Create' }}</span>
            </div>
        </div>
        <div class="cf-banner__badge">
            @if($editing)
                <i class="bi bi-pencil"></i>
                Editing: {{ mb_substr($brand->name, 0, 32) }}{{ mb_strlen($brand->name) > 32 ? '…' : '' }}
            @else
                <i class="bi bi-stars"></i> New Brand
            @endif
        </div>
    </div>

    <form action="{{ $editing ? route('admin.brands.update', $brand) : route('admin.brands.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="cf-grid">

            {{-- ══ LEFT ══ --}}
            <div>

                {{-- Basic Info --}}
                <div class="cf-card cf-card--blue">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-info-circle-fill"></i></div>
                        <div>
                            <div class="cf-card__head-title">Basic Information</div>
                            <div class="cf-card__head-sub">Brand name and website URL</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div style="margin-bottom:20px;">
                            <label class="cf-label">
                                <i class="bi bi-award-fill"></i> Brand Name <span class="req">*</span>
                            </label>
                            <input type="text" name="name"
                                   class="cf-input @error('name') is-invalid @enderror"
                                   value="{{ old('name', $brand->name) }}"
                                   placeholder="e.g. Samsung, Apple, Nike…" required>
                            @error('name')
                                <div style="font-size:12px;color:#f43f5e;margin-top:5px;display:flex;align-items:center;gap:5px;">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label class="cf-label"><i class="bi bi-link-45deg"></i> Website URL</label>
                            <input type="url" name="url"
                                   class="cf-input @error('url') is-invalid @enderror"
                                   value="{{ old('url', $brand->url) }}"
                                   placeholder="https://brand-website.com">
                            @error('url')
                                <div style="font-size:12px;color:#f43f5e;margin-top:5px;display:flex;align-items:center;gap:5px;">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="cf-card cf-card--slate">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-sort-numeric-up-alt"></i></div>
                        <div>
                            <div class="cf-card__head-title">Sort Order</div>
                            <div class="cf-card__head-sub">Lower numbers appear first in listings</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="sort-wrap">
                            <input type="number" name="sort_order" class="cf-input"
                                   value="{{ old('sort_order', $brand->sort_order ?? 0) }}" min="0" max="9999">
                            <div class="sort-hint">
                                <i class="bi bi-info-circle" style="color:#6c63ff;"></i>
                                Brands are sorted ascending by this value.<br>
                                Use <strong>0</strong> for automatic / default ordering.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div>

                {{-- Logo --}}
                <div class="cf-card cf-card--amber">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-image-fill"></i></div>
                        <div>
                            <div class="cf-card__head-title">Brand Logo</div>
                            <div class="cf-card__head-sub">Upload the brand's logo image</div>
                        </div>
                    </div>
                    <div class="cf-card__body">

                        @if ($brand->logo)
                        <div class="img-preview-current">
                            <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}">
                            <div class="ipc-text">
                                <strong>Current Logo</strong>
                                <span>Upload a new file to replace it</span>
                            </div>
                        </div>
                        @endif

                        <div class="img-upload-zone" id="dropZone">
                            <input type="file" name="logo" accept="image/*" id="logoInput">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <div class="upz-title">{{ $brand->logo ? 'Replace logo' : 'Upload logo' }}</div>
                            <div class="upz-sub">Click or drag &amp; drop · PNG, JPG, SVG · max 2 MB</div>
                        </div>

                        <div class="img-new-preview" id="newPreview">
                            <img id="newPreviewImg" src="" alt="">
                            <div class="fnm" id="newPreviewName"></div>
                        </div>

                    </div>
                </div>

                {{-- Visibility --}}
                <div class="cf-card cf-card--green">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-toggles"></i></div>
                        <div>
                            <div class="cf-card__head-title">Visibility &amp; Settings</div>
                            <div class="cf-card__head-sub">Control where this brand appears</div>
                        </div>
                    </div>
                    <div class="cf-card__body" style="padding-top:16px;">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-star-fill" style="color:#f59e0b;font-size:12px;margin-right:4px;"></i>Featured</strong>
                                <span>Shown in the homepage Brands widget</span>
                            </div>
                            <label class="cf-switch cf-switch--amber">
                                <input type="checkbox" name="is_featured" value="1"
                                       @checked(old('is_featured', $brand->is_featured))>
                                <span class="cf-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-eye-fill" style="color:#10b981;font-size:12px;margin-right:4px;"></i>Active</strong>
                                <span>Brand is visible to all shoppers</span>
                            </div>
                            <label class="cf-switch cf-switch--green">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $brand->is_active ?? true))>
                                <span class="cf-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Action bar --}}
        <div class="cf-action-bar">
            <div class="cf-action-bar__left">
                <i class="bi bi-info-circle-fill"></i>
                {{ $editing ? 'Changes are saved immediately after submission.' : 'Fill in all required fields before saving.' }}
            </div>
            <div class="cf-action-bar__right">
                <a href="{{ route('admin.brands.index') }}" class="btn-cf-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn-cf-save">
                    <span class="si"><i class="bi {{ $editing ? 'bi-check-lg' : 'bi-plus-lg' }}"></i></span>
                    <span class="sl">{{ $editing ? 'Update Brand' : 'Create Brand' }}</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    $('#logoInput').on('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#newPreviewImg').attr('src', e.target.result);
            $('#newPreviewName').text(file.name + '  (' + (file.size / 1024).toFixed(1) + ' KB)');
            $('#newPreview').show();
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
