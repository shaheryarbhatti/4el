@extends('admin.layouts.app')
@php $editing = $category->exists; @endphp
@section('title', $editing ? 'Edit Category' : 'Add Category')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
.page-header-breadcrumb { display:none !important; }

/* ── banner ── */
.cf-banner {
    background: linear-gradient(135deg,#6c63ff 0%,#5a7cff 40%,#a78bfa 100%);
    border-radius: 20px; padding: 26px 32px; margin-bottom: 26px;
    display: flex; align-items: center; justify-content: space-between;
    box-shadow: 0 8px 32px rgba(108,99,255,.32); position: relative; overflow: hidden;
}
.cf-banner::before {
    content:''; position:absolute; top:-50px; right:-50px;
    width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.07);
}
.cf-banner::after {
    content:''; position:absolute; bottom:-70px; right:80px;
    width:170px; height:170px; border-radius:50%; background:rgba(255,255,255,.04);
}
.cf-banner__left { position:relative; z-index:1; }
.cf-banner__icon {
    width:54px; height:54px; background:rgba(255,255,255,.22); border-radius:16px;
    display:flex; align-items:center; justify-content:center; font-size:24px; color:#fff;
    margin-bottom:10px; backdrop-filter:blur(4px);
}
.cf-banner__title { font-size:22px; font-weight:800; color:#fff; margin:0; letter-spacing:-.3px; }
.cf-banner__sub {
    font-size:13px; color:rgba(255,255,255,.78); margin-top:5px;
    display:flex; align-items:center; gap:6px;
}
.cf-banner__sub a { color:rgba(255,255,255,.85); text-decoration:none; }
.cf-banner__sub a:hover { color:#fff; }
.cf-banner__badge {
    background:rgba(255,255,255,.2); color:#fff; border-radius:20px;
    padding:8px 18px; font-size:12.5px; font-weight:700;
    backdrop-filter:blur(4px); position:relative; z-index:1;
    display:flex; align-items:center; gap:8px;
}

/* ── grid ── */
.cf-grid { display:grid; grid-template-columns:1fr 390px; gap:22px; align-items:start; }

/* ── card ── */
.cf-card {
    background:#fff; border-radius:18px;
    box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; margin-bottom:22px;
}
.cf-card:last-child { margin-bottom:0; }
.cf-card__head {
    padding:15px 22px; border-bottom:1px solid #f1f5f9;
    display:flex; align-items:center; gap:12px;
}
.cf-card__head-icon {
    width:38px; height:38px; border-radius:11px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:17px;
}
.cf-card__head-title { font-size:14px; font-weight:700; color:#1e293b; }
.cf-card__head-sub { font-size:11.5px; color:#94a3b8; margin-top:1px; }
.cf-card__body { padding:22px; }

/* card colour variants */
.cf-card--blue  .cf-card__head { background:linear-gradient(135deg,#ede9fe,#dbeafe); }
.cf-card--blue  .cf-card__head-icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.cf-card--green .cf-card__head { background:linear-gradient(135deg,#d1fae5,#dcfce7); }
.cf-card--green .cf-card__head-icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.cf-card--amber .cf-card__head { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.cf-card--amber .cf-card__head-icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.cf-card--slate .cf-card__head { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); }
.cf-card--slate .cf-card__head-icon { background:linear-gradient(135deg,#475569,#64748b); color:#fff; }

/* ── field ── */
.cf-label {
    display:flex; align-items:center; gap:6px;
    font-size:11.5px; font-weight:700; color:#475569;
    text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px;
}
.cf-label i { font-size:12px; color:#6c63ff; }
.cf-label .req { color:#f43f5e; font-size:14px; line-height:1; }
.cf-input {
    width:100%; padding:11px 14px; border:1.5px solid #e2e8f0; border-radius:11px;
    font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit;
    transition:border-color .18s, box-shadow .18s, background .18s; box-sizing:border-box;
}
.cf-input:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.cf-input::placeholder { color:#c4cdd6; }
textarea.cf-input { resize:vertical; min-height:110px; }

/* ── Select2 ── */
.select2-container { width:100% !important; }
.select2-container--default .select2-selection--single {
    height:44px !important; border:1.5px solid #e2e8f0 !important;
    border-radius:11px !important; background:#f8fafc !important;
    display:flex !important; align-items:center !important; transition:all .18s !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding:0 36px 0 14px !important; color:#1e293b !important;
    font-size:14px !important; line-height:42px !important;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder { color:#c4cdd6 !important; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height:42px !important; right:10px !important; }
.select2-container--default.select2-container--open .select2-selection--single,
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color:#6c63ff !important; background:#fff !important; box-shadow:0 0 0 3px rgba(108,99,255,.12) !important;
}
.select2-dropdown { border:1.5px solid #e2e8f0 !important; border-radius:12px !important; box-shadow:0 8px 28px rgba(0,0,0,.12) !important; overflow:hidden; }
.select2-search--dropdown input { border:1.5px solid #e2e8f0 !important; border-radius:8px !important; padding:8px 12px !important; font-size:13px !important; }
.select2-results__option { padding:10px 14px !important; font-size:13.5px !important; }
.select2-container--default .select2-results__option--highlighted[aria-selected] { background:linear-gradient(135deg,#6c63ff,#a78bfa) !important; }

/* ── image upload ── */
.img-upload-zone {
    border:2px dashed #c7d2fe; border-radius:14px; padding:26px 20px;
    text-align:center; cursor:pointer; background:#f5f3ff;
    transition:border-color .2s, background .2s; position:relative; overflow:hidden;
}
.img-upload-zone:hover { border-color:#6c63ff; background:#ede9fe; }
.img-upload-zone input[type=file] {
    position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%;
}
.img-upload-zone i { font-size:34px; color:#a78bfa; margin-bottom:8px; display:block; }
.img-upload-zone .upz-title { font-size:13.5px; font-weight:700; color:#4f46e5; }
.img-upload-zone .upz-sub { font-size:11.5px; color:#94a3b8; margin-top:3px; }
.img-preview-current {
    display:flex; align-items:center; gap:14px; margin-bottom:14px;
    background:#f8fafc; border-radius:12px; padding:12px 14px; border:1.5px solid #e2e8f0;
}
.img-preview-current img { width:58px; height:58px; border-radius:10px; object-fit:cover; border:2px solid #ddd6fe; }
.img-preview-current .ipc-text strong { display:block; font-size:13px; color:#1e293b; font-weight:700; }
.img-preview-current .ipc-text span { font-size:12px; color:#94a3b8; }
.img-new-preview { margin-top:14px; text-align:center; display:none; }
.img-new-preview img { max-height:130px; border-radius:12px; border:2px solid #ddd6fe; }
.img-new-preview .fnm { font-size:12px; color:#6c63ff; font-weight:600; margin-top:6px; }

/* ── icon preview ── */
.icon-preview-row {
    display:flex; align-items:center; gap:12px; margin-top:10px;
    background:#f8fafc; border-radius:11px; padding:10px 14px; border:1.5px solid #e2e8f0;
}
.icon-preview-box {
    width:44px; height:44px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#ede9fe,#dbeafe);
    display:flex; align-items:center; justify-content:center; font-size:22px; color:#6c63ff;
}
.icon-preview-label strong { display:block; font-size:13px; color:#1e293b; font-weight:700; }
.icon-preview-label span { font-size:12px; color:#94a3b8; }

/* ── toggles ── */
.toggle-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 0; border-bottom:1px solid #f1f5f9;
}
.toggle-row:first-child { padding-top:0; }
.toggle-row:last-child { border-bottom:none; padding-bottom:0; }
.toggle-info strong { display:block; font-size:13.5px; font-weight:700; color:#1e293b; margin-bottom:2px; }
.toggle-info span { font-size:12px; color:#94a3b8; }
.cf-switch { position:relative; display:inline-block; width:50px; height:27px; flex-shrink:0; }
.cf-switch input { opacity:0; width:0; height:0; }
.cf-slider {
    position:absolute; inset:0; border-radius:50px;
    background:#e2e8f0; transition:background .25s; cursor:pointer;
}
.cf-slider::before {
    content:''; position:absolute; width:21px; height:21px; border-radius:50%;
    left:3px; top:3px; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.2);
    transition:transform .25s;
}
.cf-switch input:checked + .cf-slider::before { transform:translateX(23px); }
.cf-switch--green input:checked + .cf-slider { background:#10b981; }
.cf-switch--amber input:checked + .cf-slider { background:#f59e0b; }
.cf-switch--purple input:checked + .cf-slider { background:#6c63ff; }

/* ── sort ── */
.sort-wrap { display:flex; align-items:center; gap:14px; }
.sort-wrap .cf-input { max-width:120px; text-align:center; font-size:20px; font-weight:700; padding:10px; }
.sort-hint { font-size:12.5px; color:#94a3b8; line-height:1.6; }

/* ── action bar ── */
.cf-action-bar {
    background:#fff; border-radius:18px; padding:20px 26px;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    display:flex; align-items:center; justify-content:space-between; margin-top:22px;
}
.cf-action-bar__left { font-size:13px; color:#94a3b8; display:flex; align-items:center; gap:8px; }
.cf-action-bar__left i { color:#f59e0b; }
.cf-action-bar__right { display:flex; align-items:center; gap:12px; }

.btn-cf-save {
    display:inline-flex; align-items:center; gap:10px;
    padding:12px 28px; border:none; border-radius:14px;
    font-size:14.5px; font-weight:700; color:#fff; cursor:pointer;
    background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 45%,#a78bfa 100%);
    box-shadow:0 4px 16px rgba(108,99,255,.4);
    transition:transform .22s, box-shadow .22s;
    position:relative; overflow:hidden; letter-spacing:.02em;
}
.btn-cf-save::after {
    content:''; position:absolute; inset:0;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent);
    transform:translateX(-100%); transition:transform .55s ease;
}
.btn-cf-save:hover::after { transform:translateX(100%); }
.btn-cf-save:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(108,99,255,.55); }
.btn-cf-save:active { transform:translateY(-1px); }
.btn-cf-save .si {
    width:28px; height:28px; background:rgba(255,255,255,.25); border-radius:9px;
    display:flex; align-items:center; justify-content:center; font-size:14px;
    transition:transform .3s; position:relative; z-index:1; flex-shrink:0;
}
.btn-cf-save:hover .si { transform:scale(1.15); }
.btn-cf-save .sl { position:relative; z-index:1; }

.btn-cf-cancel {
    display:inline-flex; align-items:center; gap:8px;
    padding:12px 20px; border-radius:14px; font-size:14px; font-weight:600;
    color:#475569; text-decoration:none; background:#f1f5f9; border:1.5px solid #e2e8f0;
    transition:all .2s;
}
.btn-cf-cancel:hover { background:#e2e8f0; color:#1e293b; text-decoration:none; }

/* ── icon trigger button ── */
.icon-trigger {
    display:flex; align-items:center; gap:14px; padding:12px 16px;
    border:1.5px solid #e2e8f0; border-radius:11px; background:#f8fafc;
    cursor:pointer; transition:border-color .18s, box-shadow .18s;
}
.icon-trigger:hover, .icon-trigger.open {
    border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12);
}
.icon-trigger__preview {
    width:44px; height:44px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#ede9fe,#dbeafe);
    display:flex; align-items:center; justify-content:center;
    font-size:22px; color:#6c63ff;
}
.icon-trigger__info { flex:1; }
.icon-trigger__info strong { display:block; font-size:13px; color:#1e293b; font-weight:700; font-family:monospace; }
.icon-trigger__info span { font-size:11.5px; color:#94a3b8; }
.icon-trigger__btn { color:#94a3b8; font-size:14px; transition:transform .2s; }
.icon-trigger.open .icon-trigger__btn { transform:rotate(180deg); }

/* ── icon picker panel ── */
.icon-picker {
    border:1.5px solid #e2e8f0; border-radius:14px; overflow:hidden;
    margin-top:10px; box-shadow:0 8px 28px rgba(0,0,0,.1); background:#fff;
}
.icon-picker__search-wrap {
    display:flex; align-items:center; gap:10px;
    padding:12px 16px; border-bottom:1px solid #f1f5f9; background:#f8fafc;
}
.icon-picker__search-icon { color:#94a3b8; font-size:14px; flex-shrink:0; }
.icon-picker__search {
    flex:1; border:none; background:transparent; outline:none;
    font-size:14px; color:#1e293b;
}
.icon-picker__search::placeholder { color:#c4cdd6; }
.icon-picker__clear {
    background:none; border:none; color:#94a3b8; cursor:pointer; font-size:14px;
    padding:2px 6px; border-radius:6px; transition:background .15s;
}
.icon-picker__clear:hover { background:#f1f5f9; color:#475569; }
.icon-picker__grid {
    display:grid; grid-template-columns:repeat(auto-fill, minmax(52px,1fr));
    gap:4px; padding:12px; max-height:320px; overflow-y:auto;
}
.icon-picker__grid::-webkit-scrollbar { width:6px; }
.icon-picker__grid::-webkit-scrollbar-track { background:#f1f5f9; border-radius:3px; }
.icon-picker__grid::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:3px; }
.ip-item {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:4px; padding:8px 4px; border-radius:9px; cursor:pointer;
    transition:background .12s, transform .12s; border:1.5px solid transparent;
}
.ip-item:hover { background:#ede9fe; border-color:#c4b5fd; transform:scale(1.08); }
.ip-item.selected { background:linear-gradient(135deg,#6c63ff,#a78bfa); border-color:transparent; }
.ip-item i { font-size:22px; color:#475569; }
.ip-item.selected i { color:#fff; }
.ip-item span { font-size:9px; color:#94a3b8; text-align:center; line-height:1.2; display:none; }
.icon-picker__empty { text-align:center; padding:32px; color:#94a3b8; font-size:13px; }

/* ── error banner ── */
.cf-errors {
    background:#fff1f2; border:1.5px solid #fecdd3; border-radius:14px;
    padding:16px 20px; margin-bottom:22px;
}
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
            <h4 class="cf-banner__title">{{ $editing ? 'Edit Category' : 'Add New Category' }}</h4>
            <div class="cf-banner__sub">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <a href="{{ route('admin.categories.index') }}">Categories</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <span>{{ $editing ? 'Edit' : 'Create' }}</span>
            </div>
        </div>
        <div class="cf-banner__badge">
            @if ($editing)
                <i class="bi bi-pencil"></i>
                Editing: {{ mb_substr($category->name, 0, 32) }}{{ mb_strlen($category->name) > 32 ? '…' : '' }}
            @else
                <i class="bi bi-stars"></i> New Category
            @endif
        </div>
    </div>

    <form action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          method="POST" enctype="multipart/form-data" id="catForm">
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
                            <div class="cf-card__head-sub">Name and description shown to shoppers</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div style="margin-bottom:20px;">
                            <label class="cf-label">
                                <i class="bi bi-tag-fill"></i> Category Name <span class="req">*</span>
                            </label>
                            <input type="text" name="name" id="catName"
                                   class="cf-input @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}"
                                   placeholder="e.g. Electronics, Clothing, Motors…" required>
                            @error('name')
                                <div style="font-size:12px;color:#f43f5e;margin-top:5px;display:flex;align-items:center;gap:5px;">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label class="cf-label"><i class="bi bi-text-paragraph"></i> Description</label>
                            <textarea name="description" class="cf-input"
                                      placeholder="Optional: briefly describe what this category covers…">{{ old('description', $category->description) }}</textarea>
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
                                   value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" max="9999">
                            <div class="sort-hint">
                                <i class="bi bi-info-circle" style="color:#6c63ff;"></i>
                                Categories are sorted ascending by this value.<br>
                                Use <strong>0</strong> for automatic / default ordering.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div>

                {{-- Hierarchy --}}
                <div class="cf-card cf-card--blue">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-diagram-3-fill"></i></div>
                        <div>
                            <div class="cf-card__head-title">Category Hierarchy</div>
                            <div class="cf-card__head-sub">Assign a parent to make this a sub-category</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <label class="cf-label"><i class="bi bi-folder2-open"></i> Parent Category</label>
                        <select name="parent_id" id="parentSelect">
                            <option value="">— Top Level (no parent) —</option>
                            @foreach ($parents as $p)
                                <option value="{{ $p->id }}" @selected(old('parent_id', $category->parent_id) == $p->id)>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        <div style="margin-top:10px;font-size:12px;color:#94a3b8;display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i>
                            Leave empty to create a root / top-level category.
                        </div>
                    </div>
                </div>

                {{-- Media --}}
                <div class="cf-card cf-card--amber">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-images"></i></div>
                        <div>
                            <div class="cf-card__head-title">Media &amp; Icon</div>
                            <div class="cf-card__head-sub">Category image and display icon</div>
                        </div>
                    </div>
                    <div class="cf-card__body">

                        @if ($category->image)
                        <div class="img-preview-current">
                            <img src="{{ asset('storage/'.$category->image) }}" alt="">
                            <div class="ipc-text">
                                <strong>Current Image</strong>
                                <span>Upload a new file to replace it</span>
                            </div>
                        </div>
                        @endif

                        <div class="img-upload-zone" id="dropZone">
                            <input type="file" name="image" accept="image/*" id="imgInput">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <div class="upz-title">{{ $category->image ? 'Replace image' : 'Upload image' }}</div>
                            <div class="upz-sub">Click or drag &amp; drop · PNG, JPG, WEBP · max 2 MB</div>
                        </div>

                        <div class="img-new-preview" id="newPreview">
                            <img id="newPreviewImg" src="" alt="">
                            <div class="fnm" id="newPreviewName"></div>
                        </div>

                        <div style="margin-top:20px;">
                            <label class="cf-label"><i class="bi bi-grid-3x3-gap-fill"></i> Icon <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;font-size:11px;">(optional)</span></label>

                            {{-- Hidden input that stores the chosen icon class --}}
                            <input type="hidden" name="icon" id="iconInput" value="{{ old('icon', $category->icon) }}">

                            {{-- Selected icon display + open picker button --}}
                            <div class="icon-trigger" id="iconTrigger">
                                <div class="icon-trigger__preview">
                                    <i id="iconPreviewEl" class="{{ $category->icon ? 'bi '.$category->icon : 'bi bi-tag' }}"></i>
                                </div>
                                <div class="icon-trigger__info">
                                    <strong id="iconPreviewName">{{ $category->icon ?: 'bi-tag' }}</strong>
                                    <span>Click to change icon</span>
                                </div>
                                <div class="icon-trigger__btn"><i class="bi bi-chevron-down"></i></div>
                            </div>

                            {{-- Icon picker panel --}}
                            <div class="icon-picker" id="iconPicker" style="display:none;">
                                <div class="icon-picker__search-wrap">
                                    <i class="bi bi-search icon-picker__search-icon"></i>
                                    <input type="text" id="iconSearch" class="icon-picker__search" placeholder="Search icons… e.g. laptop, cart, star">
                                    <button type="button" class="icon-picker__clear" id="iconPickerClose" title="Close"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="icon-picker__grid" id="iconGrid"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Visibility --}}
                <div class="cf-card cf-card--green">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-toggles"></i></div>
                        <div>
                            <div class="cf-card__head-title">Visibility &amp; Settings</div>
                            <div class="cf-card__head-sub">Control where this category appears</div>
                        </div>
                    </div>
                    <div class="cf-card__body" style="padding-top:16px;">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-house-fill" style="color:#6c63ff;font-size:12px;margin-right:4px;"></i>Show on Homepage</strong>
                                <span>Appears in the homepage category grid</span>
                            </div>
                            <label class="cf-switch cf-switch--purple">
                                <input type="checkbox" name="show_on_home" value="1"
                                       @checked(old('show_on_home', $category->show_on_home))>
                                <span class="cf-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-star-fill" style="color:#f59e0b;font-size:12px;margin-right:4px;"></i>Featured</strong>
                                <span>Highlighted in promotional sections</span>
                            </div>
                            <label class="cf-switch cf-switch--amber">
                                <input type="checkbox" name="is_featured" value="1"
                                       @checked(old('is_featured', $category->is_featured))>
                                <span class="cf-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-eye-fill" style="color:#10b981;font-size:12px;margin-right:4px;"></i>Active</strong>
                                <span>Category is visible to all shoppers</span>
                            </div>
                            <label class="cf-switch cf-switch--green">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $category->is_active ?? true))>
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
                <a href="{{ route('admin.categories.index') }}" class="btn-cf-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn-cf-save">
                    <span class="si"><i class="bi {{ $editing ? 'bi-check-lg' : 'bi-plus-lg' }}"></i></span>
                    <span class="sl">{{ $editing ? 'Update Category' : 'Create Category' }}</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {

    // ── Select2 ──
    $('#parentSelect').select2({
        placeholder: '— Top Level (no parent) —',
        allowClear: true,
        width: '100%',
    });

    // ── Image preview ──
    $('#imgInput').on('change', function () {
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

    // ══════════════════════════════════════════════
    // ICON PICKER
    // ══════════════════════════════════════════════

    // Complete Bootstrap Icons list (600+ icons)
    var ALL_ICONS = [
        'bi-123','bi-activity','bi-alarm','bi-alarm-fill','bi-align-bottom','bi-align-center','bi-align-end','bi-align-middle','bi-align-start','bi-align-top',
        'bi-alt','bi-app','bi-app-indicator','bi-apple','bi-archive','bi-archive-fill','bi-arrow-90deg-down','bi-arrow-90deg-left','bi-arrow-90deg-right','bi-arrow-90deg-up',
        'bi-arrow-bar-down','bi-arrow-bar-left','bi-arrow-bar-right','bi-arrow-bar-up','bi-arrow-clockwise','bi-arrow-counterclockwise','bi-arrow-down','bi-arrow-down-circle','bi-arrow-down-circle-fill','bi-arrow-down-left',
        'bi-arrow-down-left-circle','bi-arrow-down-left-circle-fill','bi-arrow-down-left-square','bi-arrow-down-left-square-fill','bi-arrow-down-right','bi-arrow-down-right-circle','bi-arrow-down-right-circle-fill','bi-arrow-down-right-square','bi-arrow-down-right-square-fill','bi-arrow-down-short',
        'bi-arrow-down-square','bi-arrow-down-square-fill','bi-arrow-down-up','bi-arrow-left','bi-arrow-left-circle','bi-arrow-left-circle-fill','bi-arrow-left-right','bi-arrow-left-short','bi-arrow-left-square','bi-arrow-left-square-fill',
        'bi-arrow-repeat','bi-arrow-return-left','bi-arrow-return-right','bi-arrow-right','bi-arrow-right-circle','bi-arrow-right-circle-fill','bi-arrow-right-short','bi-arrow-right-square','bi-arrow-right-square-fill','bi-arrow-through-heart',
        'bi-arrow-through-heart-fill','bi-arrow-up','bi-arrow-up-circle','bi-arrow-up-circle-fill','bi-arrow-up-left','bi-arrow-up-left-circle','bi-arrow-up-left-circle-fill','bi-arrow-up-left-square','bi-arrow-up-left-square-fill','bi-arrow-up-right',
        'bi-arrow-up-right-circle','bi-arrow-up-right-circle-fill','bi-arrow-up-right-square','bi-arrow-up-right-square-fill','bi-arrow-up-short','bi-arrow-up-square','bi-arrow-up-square-fill','bi-arrows-angle-contract','bi-arrows-angle-expand','bi-arrows-collapse',
        'bi-arrows-expand','bi-arrows-fullscreen','bi-arrows-move','bi-aspect-ratio','bi-aspect-ratio-fill','bi-asterisk','bi-at','bi-award','bi-award-fill','bi-back',
        'bi-backspace','bi-backspace-fill','bi-backspace-reverse','bi-backspace-reverse-fill','bi-badge-3d','bi-badge-3d-fill','bi-badge-4k','bi-badge-4k-fill','bi-badge-8k','bi-badge-8k-fill',
        'bi-badge-ad','bi-badge-ad-fill','bi-badge-ar','bi-badge-ar-fill','bi-badge-cc','bi-badge-cc-fill','bi-badge-hd','bi-badge-hd-fill','bi-badge-sd','bi-badge-sd-fill',
        'bi-badge-tm','bi-badge-tm-fill','bi-badge-vo','bi-badge-vo-fill','bi-badge-vr','bi-badge-vr-fill','bi-badge-wc','bi-badge-wc-fill','bi-bag','bi-bag-check',
        'bi-bag-check-fill','bi-bag-dash','bi-bag-dash-fill','bi-bag-fill','bi-bag-heart','bi-bag-heart-fill','bi-bag-plus','bi-bag-plus-fill','bi-bag-x','bi-bag-x-fill',
        'bi-balloon','bi-balloon-fill','bi-balloon-heart','bi-balloon-heart-fill','bi-bandaid','bi-bandaid-fill','bi-bank','bi-bank2','bi-bar-chart','bi-bar-chart-fill',
        'bi-bar-chart-line','bi-bar-chart-line-fill','bi-bar-chart-steps','bi-basket','bi-basket-fill','bi-basket2','bi-basket2-fill','bi-basket3','bi-basket3-fill','bi-battery',
        'bi-battery-charging','bi-battery-full','bi-battery-half','bi-behance','bi-bell','bi-bell-fill','bi-bell-slash','bi-bell-slash-fill','bi-bezier','bi-bezier2',
        'bi-bicycle','bi-binoculars','bi-binoculars-fill','bi-blockquote-left','bi-blockquote-right','bi-bluetooth','bi-body-text','bi-book','bi-book-fill','bi-book-half',
        'bi-bookmark','bi-bookmark-check','bi-bookmark-check-fill','bi-bookmark-dash','bi-bookmark-dash-fill','bi-bookmark-fill','bi-bookmark-heart','bi-bookmark-heart-fill','bi-bookmark-plus','bi-bookmark-plus-fill',
        'bi-bookmark-star','bi-bookmark-star-fill','bi-bookmark-x','bi-bookmark-x-fill','bi-bookmarks','bi-bookmarks-fill','bi-bookshelf','bi-boombox','bi-boombox-fill','bi-bootstrap',
        'bi-bootstrap-fill','bi-bootstrap-reboot','bi-border','bi-border-all','bi-border-bottom','bi-border-center','bi-border-inner','bi-border-left','bi-border-middle','bi-border-outer',
        'bi-border-right','bi-border-style','bi-border-top','bi-border-width','bi-bounding-box','bi-bounding-box-circles','bi-box','bi-box-arrow-down','bi-box-arrow-down-left','bi-box-arrow-down-right',
        'bi-box-arrow-in-down','bi-box-arrow-in-down-left','bi-box-arrow-in-down-right','bi-box-arrow-in-left','bi-box-arrow-in-right','bi-box-arrow-in-up','bi-box-arrow-in-up-left','bi-box-arrow-in-up-right','bi-box-arrow-left','bi-box-arrow-right',
        'bi-box-arrow-up','bi-box-arrow-up-left','bi-box-arrow-up-right','bi-box-seam','bi-box2','bi-box2-fill','bi-box2-heart','bi-box2-heart-fill','bi-boxes','bi-braces',
        'bi-braces-asterisk','bi-bricks','bi-briefcase','bi-briefcase-fill','bi-brightness-alt-high','bi-brightness-alt-high-fill','bi-brightness-alt-low','bi-brightness-alt-low-fill','bi-brightness-high','bi-brightness-high-fill',
        'bi-brightness-low','bi-brightness-low-fill','bi-broadcast','bi-broadcast-pin','bi-brush','bi-brush-fill','bi-bucket','bi-bucket-fill','bi-bug','bi-bug-fill',
        'bi-building','bi-bullseye','bi-calculator','bi-calculator-fill','bi-calendar','bi-calendar-check','bi-calendar-check-fill','bi-calendar-date','bi-calendar-date-fill','bi-calendar-day',
        'bi-calendar-day-fill','bi-calendar-event','bi-calendar-event-fill','bi-calendar-fill','bi-calendar-heart','bi-calendar-heart-fill','bi-calendar-minus','bi-calendar-minus-fill','bi-calendar-month','bi-calendar-month-fill',
        'bi-calendar-plus','bi-calendar-plus-fill','bi-calendar-range','bi-calendar-range-fill','bi-calendar-week','bi-calendar-week-fill','bi-calendar-x','bi-calendar-x-fill','bi-calendar2','bi-calendar2-check',
        'bi-calendar2-check-fill','bi-calendar2-date','bi-calendar2-date-fill','bi-calendar2-day','bi-calendar2-day-fill','bi-calendar2-event','bi-calendar2-event-fill','bi-calendar2-fill','bi-calendar2-heart','bi-calendar2-heart-fill',
        'bi-calendar2-minus','bi-calendar2-minus-fill','bi-calendar2-month','bi-calendar2-month-fill','bi-calendar2-plus','bi-calendar2-plus-fill','bi-calendar2-range','bi-calendar2-range-fill','bi-calendar2-week','bi-calendar2-week-fill',
        'bi-calendar2-x','bi-calendar2-x-fill','bi-calendar3','bi-calendar3-event','bi-calendar3-event-fill','bi-calendar3-fill','bi-calendar3-range','bi-calendar3-range-fill','bi-calendar3-week','bi-calendar3-week-fill',
        'bi-calendar4','bi-calendar4-event','bi-calendar4-range','bi-calendar4-week','bi-camera','bi-camera-fill','bi-camera-reels','bi-camera-reels-fill','bi-camera-video','bi-camera-video-fill',
        'bi-camera-video-off','bi-camera-video-off-fill','bi-camera2','bi-capslock','bi-capslock-fill','bi-card-checklist','bi-card-heading','bi-card-image','bi-card-list','bi-card-text',
        'bi-caret-down','bi-caret-down-fill','bi-caret-down-square','bi-caret-down-square-fill','bi-caret-left','bi-caret-left-fill','bi-caret-left-square','bi-caret-left-square-fill','bi-caret-right','bi-caret-right-fill',
        'bi-caret-right-square','bi-caret-right-square-fill','bi-caret-up','bi-caret-up-fill','bi-caret-up-square','bi-caret-up-square-fill','bi-cart','bi-cart-check','bi-cart-check-fill','bi-cart-dash',
        'bi-cart-dash-fill','bi-cart-fill','bi-cart-plus','bi-cart-plus-fill','bi-cart-x','bi-cart-x-fill','bi-cart2','bi-cart3','bi-cart4','bi-cash',
        'bi-cash-coin','bi-cash-stack','bi-cast','bi-chat','bi-chat-dots','bi-chat-dots-fill','bi-chat-fill','bi-chat-heart','bi-chat-heart-fill','bi-chat-left',
        'bi-chat-left-dots','bi-chat-left-dots-fill','bi-chat-left-fill','bi-chat-left-heart','bi-chat-left-heart-fill','bi-chat-left-quote','bi-chat-left-quote-fill','bi-chat-left-text','bi-chat-left-text-fill','bi-chat-quote',
        'bi-chat-quote-fill','bi-chat-right','bi-chat-right-dots','bi-chat-right-dots-fill','bi-chat-right-fill','bi-chat-right-heart','bi-chat-right-heart-fill','bi-chat-right-quote','bi-chat-right-quote-fill','bi-chat-right-text',
        'bi-chat-right-text-fill','bi-chat-square','bi-chat-square-dots','bi-chat-square-dots-fill','bi-chat-square-fill','bi-chat-square-heart','bi-chat-square-heart-fill','bi-chat-square-quote','bi-chat-square-quote-fill','bi-chat-square-text',
        'bi-chat-square-text-fill','bi-chat-text','bi-chat-text-fill','bi-check','bi-check-all','bi-check-circle','bi-check-circle-fill','bi-check-lg','bi-check-square','bi-check-square-fill',
        'bi-check2','bi-check2-all','bi-check2-circle','bi-check2-square','bi-chevron-bar-contract','bi-chevron-bar-down','bi-chevron-bar-expand','bi-chevron-bar-left','bi-chevron-bar-right','bi-chevron-bar-up',
        'bi-chevron-compact-down','bi-chevron-compact-left','bi-chevron-compact-right','bi-chevron-compact-up','bi-chevron-contract','bi-chevron-double-down','bi-chevron-double-left','bi-chevron-double-right','bi-chevron-double-up','bi-chevron-down',
        'bi-chevron-expand','bi-chevron-left','bi-chevron-right','bi-chevron-up','bi-circle','bi-circle-fill','bi-circle-half','bi-circle-square','bi-clipboard','bi-clipboard-check',
        'bi-clipboard-check-fill','bi-clipboard-data','bi-clipboard-data-fill','bi-clipboard-fill','bi-clipboard-heart','bi-clipboard-heart-fill','bi-clipboard-minus','bi-clipboard-minus-fill','bi-clipboard-plus','bi-clipboard-plus-fill',
        'bi-clipboard-pulse','bi-clipboard-x','bi-clipboard-x-fill','bi-clipboard2','bi-clipboard2-check','bi-clipboard2-check-fill','bi-clipboard2-data','bi-clipboard2-data-fill','bi-clipboard2-fill','bi-clipboard2-heart',
        'bi-clipboard2-heart-fill','bi-clipboard2-minus','bi-clipboard2-minus-fill','bi-clipboard2-plus','bi-clipboard2-plus-fill','bi-clipboard2-pulse','bi-clipboard2-pulse-fill','bi-clipboard2-x','bi-clipboard2-x-fill','bi-clock',
        'bi-clock-fill','bi-clock-history','bi-cloud','bi-cloud-arrow-down','bi-cloud-arrow-down-fill','bi-cloud-arrow-up','bi-cloud-arrow-up-fill','bi-cloud-check','bi-cloud-check-fill','bi-cloud-download',
        'bi-cloud-download-fill','bi-cloud-drizzle','bi-cloud-drizzle-fill','bi-cloud-fill','bi-cloud-fog','bi-cloud-fog-fill','bi-cloud-fog2','bi-cloud-fog2-fill','bi-cloud-hail','bi-cloud-hail-fill',
        'bi-cloud-haze','bi-cloud-haze-1','bi-cloud-haze-fill','bi-cloud-haze2','bi-cloud-haze2-fill','bi-cloud-lightning','bi-cloud-lightning-fill','bi-cloud-lightning-rain','bi-cloud-lightning-rain-fill','bi-cloud-minus',
        'bi-cloud-minus-fill','bi-cloud-moon','bi-cloud-moon-fill','bi-cloud-plus','bi-cloud-plus-fill','bi-cloud-rain','bi-cloud-rain-fill','bi-cloud-rain-heavy','bi-cloud-rain-heavy-fill','bi-cloud-slash',
        'bi-cloud-slash-fill','bi-cloud-sleet','bi-cloud-sleet-fill','bi-cloud-snow','bi-cloud-snow-fill','bi-cloud-sun','bi-cloud-sun-fill','bi-cloud-upload','bi-cloud-upload-fill','bi-clouds',
        'bi-clouds-fill','bi-cloudy','bi-cloudy-fill','bi-code','bi-code-slash','bi-code-square','bi-coin','bi-collection','bi-collection-fill','bi-collection-play',
        'bi-collection-play-fill','bi-columns','bi-columns-gap','bi-command','bi-compass','bi-compass-fill','bi-cone','bi-cone-striped','bi-controller','bi-cpu',
        'bi-cpu-fill','bi-credit-card','bi-credit-card-2-back','bi-credit-card-2-back-fill','bi-credit-card-2-front','bi-credit-card-2-front-fill','bi-credit-card-fill','bi-crop','bi-cup','bi-cup-fill',
        'bi-cup-straw','bi-currency-bitcoin','bi-currency-dollar','bi-currency-euro','bi-currency-exchange','bi-currency-pound','bi-currency-yen','bi-cursor','bi-cursor-fill','bi-cursor-text',
        'bi-dash','bi-dash-circle','bi-dash-circle-dotted','bi-dash-circle-fill','bi-dash-lg','bi-dash-square','bi-dash-square-dotted','bi-dash-square-fill','bi-device-hdd','bi-device-hdd-fill',
        'bi-device-ssd','bi-device-ssd-fill','bi-diagram-2','bi-diagram-2-fill','bi-diagram-3','bi-diagram-3-fill','bi-diamond','bi-diamond-fill','bi-diamond-half','bi-dice-1',
        'bi-dice-1-fill','bi-dice-2','bi-dice-2-fill','bi-dice-3','bi-dice-3-fill','bi-dice-4','bi-dice-4-fill','bi-dice-5','bi-dice-5-fill','bi-dice-6',
        'bi-dice-6-fill','bi-disc','bi-disc-fill','bi-discord','bi-display','bi-display-fill','bi-displayport','bi-displayport-1','bi-displayport-fill','bi-distribute-horizontal',
        'bi-distribute-vertical','bi-door-closed','bi-door-closed-fill','bi-door-open','bi-door-open-fill','bi-dot','bi-download','bi-dpad','bi-dpad-fill','bi-dribbble',
        'bi-droplet','bi-droplet-fill','bi-droplet-half','bi-ear','bi-ear-fill','bi-earbuds','bi-easel','bi-easel-fill','bi-easel2','bi-easel2-fill',
        'bi-easel3','bi-easel3-fill','bi-egg','bi-egg-fill','bi-egg-fried','bi-eject','bi-eject-fill','bi-emoji-angry','bi-emoji-angry-fill','bi-emoji-dizzy',
        'bi-emoji-dizzy-fill','bi-emoji-expressionless','bi-emoji-expressionless-fill','bi-emoji-frown','bi-emoji-frown-fill','bi-emoji-heart-eyes','bi-emoji-heart-eyes-fill','bi-emoji-kiss','bi-emoji-kiss-fill','bi-emoji-laughing',
        'bi-emoji-laughing-fill','bi-emoji-neutral','bi-emoji-neutral-fill','bi-emoji-smile','bi-emoji-smile-fill','bi-emoji-smile-upside-down','bi-emoji-smile-upside-down-fill','bi-emoji-sunglasses','bi-emoji-sunglasses-fill','bi-emoji-wink',
        'bi-emoji-wink-fill','bi-envelope','bi-envelope-check','bi-envelope-check-1','bi-envelope-check-fill','bi-envelope-dash','bi-envelope-dash-1','bi-envelope-dash-fill','bi-envelope-exclamation','bi-envelope-exclamation-1',
        'bi-envelope-exclamation-fill','bi-envelope-fill','bi-envelope-heart','bi-envelope-heart-fill','bi-envelope-open','bi-envelope-open-fill','bi-envelope-open-heart','bi-envelope-open-heart-fill','bi-envelope-paper','bi-envelope-paper-fill',
        'bi-envelope-paper-heart','bi-envelope-paper-heart-fill','bi-envelope-plus','bi-envelope-plus-fill','bi-envelope-slash','bi-envelope-slash-1','bi-envelope-slash-fill','bi-envelope-x','bi-envelope-x-1','bi-envelope-x-fill',
        'bi-eraser','bi-eraser-fill','bi-ethernet','bi-exclamation','bi-exclamation-circle','bi-exclamation-circle-fill','bi-exclamation-diamond','bi-exclamation-diamond-fill','bi-exclamation-lg','bi-exclamation-octagon',
        'bi-exclamation-octagon-fill','bi-exclamation-square','bi-exclamation-square-fill','bi-exclamation-triangle','bi-exclamation-triangle-fill','bi-exclude','bi-explicit','bi-explicit-fill','bi-eye','bi-eye-fill',
        'bi-eye-slash','bi-eye-slash-fill','bi-eyedropper','bi-eyeglasses','bi-facebook','bi-fan','bi-file','bi-file-arrow-down','bi-file-arrow-down-fill','bi-file-arrow-up',
        'bi-file-arrow-up-fill','bi-file-bar-graph','bi-file-bar-graph-fill','bi-file-binary','bi-file-binary-fill','bi-file-break','bi-file-break-fill','bi-file-check','bi-file-check-fill','bi-file-code',
        'bi-file-code-fill','bi-file-diff','bi-file-diff-fill','bi-file-earmark','bi-file-earmark-arrow-down','bi-file-earmark-arrow-down-fill','bi-file-earmark-arrow-up','bi-file-earmark-arrow-up-fill','bi-file-earmark-bar-graph','bi-file-earmark-bar-graph-fill',
        'bi-file-earmark-binary','bi-file-earmark-binary-fill','bi-file-earmark-break','bi-file-earmark-break-fill','bi-file-earmark-check','bi-file-earmark-check-fill','bi-file-earmark-code','bi-file-earmark-code-fill','bi-file-earmark-diff','bi-file-earmark-diff-fill',
        'bi-file-earmark-easel','bi-file-earmark-easel-fill','bi-file-earmark-excel','bi-file-earmark-excel-fill','bi-file-earmark-fill','bi-file-earmark-font','bi-file-earmark-font-fill','bi-file-earmark-image','bi-file-earmark-image-fill','bi-file-earmark-lock',
        'bi-file-earmark-lock-fill','bi-file-earmark-lock2','bi-file-earmark-lock2-fill','bi-file-earmark-medical','bi-file-earmark-medical-fill','bi-file-earmark-minus','bi-file-earmark-minus-fill','bi-file-earmark-music','bi-file-earmark-music-fill','bi-file-earmark-pdf',
        'bi-file-earmark-pdf-fill','bi-file-earmark-person','bi-file-earmark-person-fill','bi-file-earmark-play','bi-file-earmark-play-fill','bi-file-earmark-plus','bi-file-earmark-plus-fill','bi-file-earmark-post','bi-file-earmark-post-fill','bi-file-earmark-ppt',
        'bi-file-earmark-ppt-fill','bi-file-earmark-richtext','bi-file-earmark-richtext-fill','bi-file-earmark-ruled','bi-file-earmark-ruled-fill','bi-file-earmark-slides','bi-file-earmark-slides-fill','bi-file-earmark-spreadsheet','bi-file-earmark-spreadsheet-fill','bi-file-earmark-text',
        'bi-file-earmark-text-fill','bi-file-earmark-word','bi-file-earmark-word-fill','bi-file-earmark-x','bi-file-earmark-x-fill','bi-file-earmark-zip','bi-file-earmark-zip-fill','bi-file-easel','bi-file-easel-fill','bi-file-excel',
        'bi-file-excel-fill','bi-file-fill','bi-file-font','bi-file-font-fill','bi-file-image','bi-file-image-fill','bi-file-lock','bi-file-lock-fill','bi-file-lock2','bi-file-lock2-fill',
        'bi-file-medical','bi-file-medical-fill','bi-file-minus','bi-file-minus-fill','bi-file-music','bi-file-music-fill','bi-file-pdf','bi-file-pdf-fill','bi-file-person','bi-file-person-fill',
        'bi-file-play','bi-file-play-fill','bi-file-plus','bi-file-plus-fill','bi-file-post','bi-file-post-fill','bi-file-ppt','bi-file-ppt-fill','bi-file-richtext','bi-file-richtext-fill',
        'bi-file-ruled','bi-file-ruled-fill','bi-file-slides','bi-file-slides-fill','bi-file-spreadsheet','bi-file-spreadsheet-fill','bi-file-text','bi-file-text-fill','bi-file-word','bi-file-word-fill',
        'bi-file-x','bi-file-x-fill','bi-file-zip','bi-file-zip-fill','bi-files','bi-files-alt','bi-filetype-aac','bi-filetype-ai','bi-filetype-bmp','bi-filetype-cs',
        'bi-filetype-css','bi-filetype-csv','bi-filetype-doc','bi-filetype-docx','bi-filetype-exe','bi-filetype-gif','bi-filetype-heic','bi-filetype-html','bi-filetype-java','bi-filetype-jpg',
        'bi-filetype-js','bi-filetype-json','bi-filetype-jsx','bi-filetype-key','bi-filetype-m4p','bi-filetype-md','bi-filetype-mdx','bi-filetype-mov','bi-filetype-mp3','bi-filetype-mp4',
        'bi-filetype-otf','bi-filetype-pdf','bi-filetype-php','bi-filetype-png','bi-filetype-ppt','bi-filetype-ppt-1','bi-filetype-pptx','bi-filetype-psd','bi-filetype-py','bi-filetype-raw',
        'bi-filetype-rb','bi-filetype-sass','bi-filetype-scss','bi-filetype-sh','bi-filetype-svg','bi-filetype-tiff','bi-filetype-tsx','bi-filetype-ttf','bi-filetype-txt','bi-filetype-wav',
        'bi-filetype-woff','bi-filetype-xls','bi-filetype-xls-1','bi-filetype-xlsx','bi-filetype-xml','bi-filetype-yml','bi-film','bi-filter','bi-filter-circle','bi-filter-circle-fill',
        'bi-filter-left','bi-filter-right','bi-filter-square','bi-filter-square-fill','bi-fingerprint','bi-flag','bi-flag-fill','bi-flower1','bi-flower2','bi-flower3',
        'bi-folder','bi-folder-check','bi-folder-fill','bi-folder-minus','bi-folder-plus','bi-folder-symlink','bi-folder-symlink-fill','bi-folder-x','bi-folder2','bi-folder2-open',
        'bi-fonts','bi-forward','bi-forward-fill','bi-front','bi-fullscreen','bi-fullscreen-exit','bi-funnel','bi-funnel-fill','bi-gear','bi-gear-fill',
        'bi-gear-wide','bi-gear-wide-connected','bi-gem','bi-gender-ambiguous','bi-gender-female','bi-gender-male','bi-gender-trans','bi-geo','bi-geo-alt','bi-geo-alt-fill',
        'bi-geo-fill','bi-gift','bi-gift-fill','bi-git','bi-github','bi-globe','bi-globe2','bi-google','bi-gpu-card','bi-graph-down',
        'bi-graph-down-arrow','bi-graph-up','bi-graph-up-arrow','bi-grid','bi-grid-1x2','bi-grid-1x2-fill','bi-grid-3x2','bi-grid-3x2-gap','bi-grid-3x2-gap-fill','bi-grid-3x3',
        'bi-grid-3x3-gap','bi-grid-3x3-gap-fill','bi-grid-fill','bi-grip-horizontal','bi-grip-vertical','bi-hammer','bi-hand-index','bi-hand-index-fill','bi-hand-index-thumb','bi-hand-index-thumb-fill',
        'bi-hand-thumbs-down','bi-hand-thumbs-down-fill','bi-hand-thumbs-up','bi-hand-thumbs-up-fill','bi-handbag','bi-handbag-fill','bi-hash','bi-hdd','bi-hdd-fill','bi-hdd-network',
        'bi-hdd-network-fill','bi-hdd-rack','bi-hdd-rack-fill','bi-hdd-stack','bi-hdd-stack-fill','bi-hdmi','bi-hdmi-fill','bi-headphones','bi-headset','bi-headset-vr',
        'bi-heart','bi-heart-arrow','bi-heart-fill','bi-heart-half','bi-heart-pulse','bi-heart-pulse-fill','bi-heartbreak','bi-heartbreak-fill','bi-hearts','bi-heptagon',
        'bi-heptagon-fill','bi-heptagon-half','bi-hexagon','bi-hexagon-fill','bi-hexagon-half','bi-hospital','bi-hospital-fill','bi-hourglass','bi-hourglass-bottom','bi-hourglass-split',
        'bi-hourglass-top','bi-house','bi-house-door','bi-house-door-fill','bi-house-fill','bi-house-heart','bi-house-heart-fill','bi-hr','bi-hurricane','bi-hypnotize',
        'bi-image','bi-image-alt','bi-image-fill','bi-images','bi-inbox','bi-inbox-fill','bi-inboxes','bi-inboxes-fill','bi-incognito','bi-infinity',
        'bi-info','bi-info-circle','bi-info-circle-fill','bi-info-lg','bi-info-square','bi-info-square-fill','bi-input-cursor','bi-input-cursor-text','bi-instagram','bi-intersect',
        'bi-journal','bi-journal-album','bi-journal-arrow-down','bi-journal-arrow-up','bi-journal-bookmark','bi-journal-bookmark-fill','bi-journal-check','bi-journal-code','bi-journal-medical','bi-journal-minus',
        'bi-journal-plus','bi-journal-richtext','bi-journal-text','bi-journal-x','bi-journals','bi-joystick','bi-justify','bi-justify-left','bi-justify-right','bi-kanban',
        'bi-kanban-fill','bi-key','bi-key-fill','bi-keyboard','bi-keyboard-fill','bi-ladder','bi-lamp','bi-lamp-fill','bi-laptop','bi-laptop-fill',
        'bi-layer-backward','bi-layer-forward','bi-layers','bi-layers-fill','bi-layers-half','bi-layout-sidebar','bi-layout-sidebar-inset','bi-layout-sidebar-inset-reverse','bi-layout-sidebar-reverse','bi-layout-split',
        'bi-layout-text-sidebar','bi-layout-text-sidebar-reverse','bi-layout-text-window','bi-layout-text-window-reverse','bi-layout-three-columns','bi-layout-wtf','bi-life-preserver','bi-lightbulb','bi-lightbulb-fill','bi-lightbulb-off',
        'bi-lightbulb-off-fill','bi-lightning','bi-lightning-charge','bi-lightning-charge-fill','bi-lightning-fill','bi-line','bi-link','bi-link-45deg','bi-linkedin','bi-list',
        'bi-list-check','bi-list-columns','bi-list-columns-reverse','bi-list-nested','bi-list-ol','bi-list-stars','bi-list-task','bi-list-ul','bi-lock','bi-lock-fill',
        'bi-magic','bi-magnet','bi-magnet-fill','bi-mailbox','bi-mailbox2','bi-map','bi-map-fill','bi-markdown','bi-markdown-fill','bi-mask',
        'bi-mastodon','bi-medium','bi-megaphone','bi-megaphone-fill','bi-memory','bi-menu-app','bi-menu-app-fill','bi-menu-button','bi-menu-button-fill','bi-menu-button-wide',
        'bi-menu-button-wide-fill','bi-menu-down','bi-menu-up','bi-messenger','bi-meta','bi-mic','bi-mic-fill','bi-mic-mute','bi-mic-mute-fill','bi-microsoft',
        'bi-minecart','bi-minecart-loaded','bi-modem','bi-modem-fill','bi-moisture','bi-moon','bi-moon-fill','bi-moon-stars','bi-moon-stars-fill','bi-mortarboard',
        'bi-mortarboard-fill','bi-mortorboard','bi-mortorboard-fill','bi-motherboard','bi-motherboard-fill','bi-mouse','bi-mouse-fill','bi-mouse2','bi-mouse2-fill','bi-mouse3',
        'bi-mouse3-fill','bi-music-note','bi-music-note-beamed','bi-music-note-list','bi-music-player','bi-music-player-fill','bi-newspaper','bi-nintendo-switch','bi-node-minus','bi-node-minus-fill',
        'bi-node-plus','bi-node-plus-fill','bi-nut','bi-nut-fill','bi-octagon','bi-octagon-fill','bi-octagon-half','bi-optical-audio','bi-optical-audio-fill','bi-option',
        'bi-outlet','bi-paint-bucket','bi-palette','bi-palette-fill','bi-palette2','bi-paperclip','bi-paragraph','bi-patch-check','bi-patch-check-fill','bi-patch-exclamation',
        'bi-patch-exclamation-fill','bi-patch-minus','bi-patch-minus-fill','bi-patch-plus','bi-patch-plus-fill','bi-patch-question','bi-patch-question-fill','bi-pause','bi-pause-btn','bi-pause-btn-fill',
        'bi-pause-circle','bi-pause-circle-fill','bi-pause-fill','bi-paypal','bi-pc','bi-pc-display','bi-pc-display-horizontal','bi-pc-horizontal','bi-pci-card','bi-peace',
        'bi-peace-fill','bi-pen','bi-pen-fill','bi-pencil','bi-pencil-fill','bi-pencil-square','bi-pentagon','bi-pentagon-fill','bi-pentagon-half','bi-people',
        'bi-people-fill','bi-percent','bi-person','bi-person-badge','bi-person-badge-fill','bi-person-bounding-box','bi-person-check','bi-person-check-fill','bi-person-circle','bi-person-dash',
        'bi-person-dash-fill','bi-person-fill','bi-person-heart','bi-person-hearts','bi-person-lines-fill','bi-person-plus','bi-person-plus-fill','bi-person-rolodex','bi-person-square','bi-person-video',
        'bi-person-video2','bi-person-video3','bi-person-workspace','bi-person-x','bi-person-x-fill','bi-phone','bi-phone-fill','bi-phone-flip','bi-phone-landscape','bi-phone-landscape-fill',
        'bi-phone-vibrate','bi-phone-vibrate-fill','bi-pie-chart','bi-pie-chart-fill','bi-piggy-bank','bi-piggy-bank-fill','bi-pin','bi-pin-angle','bi-pin-angle-fill','bi-pin-fill',
        'bi-pin-map','bi-pin-map-fill','bi-pinterest','bi-pip','bi-pip-fill','bi-play','bi-play-btn','bi-play-btn-fill','bi-play-circle','bi-play-circle-fill',
        'bi-play-fill','bi-playstation','bi-plug','bi-plug-fill','bi-plugin','bi-plus','bi-plus-circle','bi-plus-circle-dotted','bi-plus-circle-fill','bi-plus-lg',
        'bi-plus-slash-minus','bi-plus-square','bi-plus-square-dotted','bi-plus-square-fill','bi-postage','bi-postage-fill','bi-postage-heart','bi-postage-heart-fill','bi-postcard','bi-postcard-fill',
        'bi-postcard-heart','bi-postcard-heart-fill','bi-power','bi-printer','bi-printer-fill','bi-projector','bi-projector-fill','bi-puzzle','bi-puzzle-fill','bi-qr-code',
        'bi-qr-code-scan','bi-question','bi-question-circle','bi-question-circle-fill','bi-question-diamond','bi-question-diamond-fill','bi-question-lg','bi-question-octagon','bi-question-octagon-fill','bi-question-square',
        'bi-question-square-fill','bi-quora','bi-quote','bi-radioactive','bi-rainbow','bi-receipt','bi-receipt-cutoff','bi-reception-0','bi-reception-1','bi-reception-2',
        'bi-reception-3','bi-reception-4','bi-record','bi-record-btn','bi-record-btn-fill','bi-record-circle','bi-record-circle-fill','bi-record-fill','bi-record2','bi-record2-fill',
        'bi-recycle','bi-reddit','bi-reply','bi-reply-all','bi-reply-all-fill','bi-reply-fill','bi-robot','bi-router','bi-router-fill','bi-rss',
        'bi-rss-fill','bi-rulers','bi-safe','bi-safe-fill','bi-safe2','bi-safe2-fill','bi-save','bi-save-fill','bi-save2','bi-save2-fill',
        'bi-scissors','bi-screwdriver','bi-sd-card','bi-sd-card-fill','bi-search','bi-search-heart','bi-search-heart-fill','bi-segmented-nav','bi-send','bi-send-check',
        'bi-send-check-fill','bi-send-dash','bi-send-dash-fill','bi-send-exclamation','bi-send-exclamation-1','bi-send-exclamation-fill','bi-send-fill','bi-send-plus','bi-send-plus-fill','bi-send-slash',
        'bi-send-slash-fill','bi-send-x','bi-send-x-fill','bi-server','bi-share','bi-share-fill','bi-shield','bi-shield-check','bi-shield-exclamation','bi-shield-fill',
        'bi-shield-fill-check','bi-shield-fill-exclamation','bi-shield-fill-minus','bi-shield-fill-plus','bi-shield-fill-x','bi-shield-lock','bi-shield-lock-fill','bi-shield-minus','bi-shield-plus','bi-shield-shaded',
        'bi-shield-slash','bi-shield-slash-fill','bi-shield-x','bi-shift','bi-shift-fill','bi-shop','bi-shop-window','bi-shuffle','bi-signal','bi-signpost',
        'bi-signpost-2','bi-signpost-2-fill','bi-signpost-fill','bi-signpost-split','bi-signpost-split-fill','bi-sim','bi-sim-fill','bi-skip-backward','bi-skip-backward-btn','bi-skip-backward-btn-fill',
        'bi-skip-backward-circle','bi-skip-backward-circle-fill','bi-skip-backward-fill','bi-skip-end','bi-skip-end-btn','bi-skip-end-btn-fill','bi-skip-end-circle','bi-skip-end-circle-fill','bi-skip-end-fill','bi-skip-forward',
        'bi-skip-forward-btn','bi-skip-forward-btn-fill','bi-skip-forward-circle','bi-skip-forward-circle-fill','bi-skip-forward-fill','bi-skip-start','bi-skip-start-btn','bi-skip-start-btn-fill','bi-skip-start-circle','bi-skip-start-circle-fill',
        'bi-skip-start-fill','bi-skype','bi-slack','bi-slash','bi-slash-circle','bi-slash-circle-fill','bi-slash-lg','bi-slash-square','bi-slash-square-fill','bi-sliders',
        'bi-sliders2','bi-sliders2-vertical','bi-smartwatch','bi-snapchat','bi-snow','bi-snow2','bi-snow3','bi-sort-alpha-down','bi-sort-alpha-down-alt','bi-sort-alpha-up',
        'bi-sort-alpha-up-alt','bi-sort-down','bi-sort-down-alt','bi-sort-numeric-down','bi-sort-numeric-down-alt','bi-sort-numeric-up','bi-sort-numeric-up-alt','bi-sort-up','bi-sort-up-alt','bi-soundwave',
        'bi-speaker','bi-speaker-fill','bi-speedometer','bi-speedometer2','bi-spellcheck','bi-spotify','bi-square','bi-square-fill','bi-square-half','bi-ssd',
        'bi-ssd-fill','bi-stack','bi-stack-overflow','bi-star','bi-star-fill','bi-star-half','bi-stars','bi-steam','bi-stickies','bi-stickies-fill',
        'bi-sticky','bi-sticky-fill','bi-stop','bi-stop-btn','bi-stop-btn-fill','bi-stop-circle','bi-stop-circle-fill','bi-stop-fill','bi-stoplights','bi-stoplights-fill',
        'bi-stopwatch','bi-stopwatch-fill','bi-strava','bi-subtract','bi-suit-club','bi-suit-club-fill','bi-suit-diamond','bi-suit-diamond-fill','bi-suit-heart','bi-suit-heart-fill',
        'bi-suit-spade','bi-suit-spade-fill','bi-sun','bi-sun-fill','bi-sunglasses','bi-sunrise','bi-sunrise-fill','bi-sunset','bi-sunset-fill','bi-symmetry-horizontal',
        'bi-symmetry-vertical','bi-table','bi-tablet','bi-tablet-fill','bi-tablet-landscape','bi-tablet-landscape-fill','bi-tag','bi-tag-fill','bi-tags','bi-tags-fill',
        'bi-telegram','bi-telephone','bi-telephone-fill','bi-telephone-forward','bi-telephone-forward-fill','bi-telephone-inbound','bi-telephone-inbound-fill','bi-telephone-minus','bi-telephone-minus-fill','bi-telephone-outbound',
        'bi-telephone-outbound-fill','bi-telephone-plus','bi-telephone-plus-fill','bi-telephone-x','bi-telephone-x-fill','bi-terminal','bi-terminal-dash','bi-terminal-dash-1','bi-terminal-fill','bi-terminal-plus',
        'bi-terminal-split','bi-terminal-x','bi-text-center','bi-text-indent-left','bi-text-indent-right','bi-text-left','bi-text-paragraph','bi-text-right','bi-textarea','bi-textarea-resize',
        'bi-textarea-t','bi-thermometer','bi-thermometer-half','bi-thermometer-high','bi-thermometer-low','bi-thermometer-snow','bi-thermometer-sun','bi-three-dots','bi-three-dots-vertical','bi-thunderbolt',
        'bi-thunderbolt-fill','bi-ticket','bi-ticket-detailed','bi-ticket-detailed-fill','bi-ticket-fill','bi-ticket-perforated','bi-ticket-perforated-fill','bi-tiktok','bi-toggle-off','bi-toggle-on',
        'bi-toggle2-off','bi-toggle2-on','bi-toggles','bi-toggles2','bi-tools','bi-tornado','bi-translate','bi-trash','bi-trash-fill','bi-trash2',
        'bi-trash2-fill','bi-trash3','bi-trash3-fill','bi-tree','bi-tree-fill','bi-triangle','bi-triangle-fill','bi-triangle-half','bi-trophy','bi-trophy-fill',
        'bi-tropical-storm','bi-truck','bi-truck-flatbed','bi-tsunami','bi-tv','bi-tv-fill','bi-twitch','bi-twitter','bi-type','bi-type-bold',
        'bi-type-h1','bi-type-h2','bi-type-h3','bi-type-italic','bi-type-strikethrough','bi-type-underline','bi-ui-checks','bi-ui-checks-grid','bi-ui-radios','bi-ui-radios-grid',
        'bi-umbrella','bi-umbrella-fill','bi-union','bi-unlock','bi-unlock-fill','bi-upc','bi-upc-scan','bi-upload','bi-usb','bi-usb-c',
        'bi-usb-c-fill','bi-usb-drive','bi-usb-drive-fill','bi-usb-fill','bi-usb-micro','bi-usb-micro-fill','bi-usb-mini','bi-usb-mini-fill','bi-usb-plug','bi-usb-plug-fill',
        'bi-usb-symbol','bi-valentine','bi-valentine2','bi-vector-pen','bi-view-list','bi-view-stacked','bi-vimeo','bi-vinyl','bi-vinyl-fill','bi-voicemail',
        'bi-volume-down','bi-volume-down-fill','bi-volume-mute','bi-volume-mute-fill','bi-volume-off','bi-volume-off-fill','bi-volume-up','bi-volume-up-fill','bi-vr','bi-wallet',
        'bi-wallet-fill','bi-wallet2','bi-watch','bi-water','bi-webcam','bi-webcam-fill','bi-whatsapp','bi-wifi','bi-wifi-1','bi-wifi-2',
        'bi-wifi-off','bi-wind','bi-window','bi-window-dash','bi-window-desktop','bi-window-dock','bi-window-fullscreen','bi-window-plus','bi-window-sidebar','bi-window-split',
        'bi-window-stack','bi-window-x','bi-windows','bi-wordpress','bi-wrench','bi-wrench-adjustable','bi-wrench-adjustable-circle','bi-wrench-adjustable-circle-fill','bi-x','bi-x-circle',
        'bi-x-circle-fill','bi-x-diamond','bi-x-diamond-fill','bi-x-lg','bi-x-octagon','bi-x-octagon-fill','bi-x-square','bi-x-square-fill','bi-xbox','bi-yin-yang',
        'bi-youtube','bi-zoom-in','bi-zoom-out',
    ];

    var currentIcon = $('#iconInput').val() || 'bi-tag';

    function updateTrigger(icon) {
        currentIcon = icon;
        $('#iconInput').val(icon);
        $('#iconPreviewEl').attr('class', 'bi ' + icon);
        $('#iconPreviewName').text(icon);
        // highlight selected in grid
        $('.ip-item').removeClass('selected');
        $('.ip-item').filter(function(){ return $(this).data('icon') === icon; }).addClass('selected');
    }

    function buildGrid(filter) {
        var grid = $('#iconGrid');
        grid.empty();
        var list = filter
            ? ALL_ICONS.filter(function(ic){ return ic.indexOf(filter) !== -1; })
            : ALL_ICONS;

        if (list.length === 0) {
            grid.html('<div class="icon-picker__empty"><i class="bi bi-search" style="font-size:28px;display:block;margin-bottom:8px;"></i>No icons match "' + filter + '"</div>');
            return;
        }

        var html = '';
        list.forEach(function(ic) {
            html += '<div class="ip-item' + (ic === currentIcon ? ' selected' : '') + '" data-icon="' + ic + '" title="' + ic + '">'
                  + '<i class="bi ' + ic + '"></i>'
                  + '</div>';
        });
        grid.html(html);
    }

    // Open / close picker
    $('#iconTrigger').on('click', function () {
        var picker = $('#iconPicker');
        var open   = picker.is(':visible');
        if (open) {
            picker.slideUp(150);
            $(this).removeClass('open');
        } else {
            picker.slideDown(180);
            $(this).addClass('open');
            buildGrid($('#iconSearch').val().trim().toLowerCase());
            // scroll to selected
            setTimeout(function(){
                var sel = $('#iconGrid .ip-item.selected')[0];
                if (sel) sel.scrollIntoView({ block: 'center' });
            }, 200);
            $('#iconSearch').focus();
        }
    });

    $('#iconPickerClose').on('click', function (e) {
        e.stopPropagation();
        $('#iconPicker').slideUp(150);
        $('#iconTrigger').removeClass('open');
    });

    // Search
    var searchTimer;
    $('#iconSearch').on('input', function () {
        clearTimeout(searchTimer);
        var q = $(this).val().trim().toLowerCase();
        searchTimer = setTimeout(function () { buildGrid(q); }, 150);
    });

    // Click icon
    $('#iconGrid').on('click', '.ip-item', function () {
        var icon = $(this).data('icon');
        updateTrigger(icon);
        $('#iconPicker').slideUp(150);
        $('#iconTrigger').removeClass('open');
    });

    // Close on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#iconTrigger, #iconPicker').length) {
            $('#iconPicker').slideUp(150);
            $('#iconTrigger').removeClass('open');
        }
    });

    // Init trigger with current value
    updateTrigger(currentIcon);

});
</script>
@endpush
