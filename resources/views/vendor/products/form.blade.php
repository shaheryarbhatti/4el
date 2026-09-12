{{-- Vendor product create / edit — Multi-step wizard --}}
@extends('vendor.layouts.app')
@php $editing = $product->exists; @endphp
@section('title', $editing ? 'Edit Product' : 'Add Product')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
/* Quill editor styling */
.vf-quill-wrap .ql-toolbar.ql-snow {
    border: 1.5px solid #dde3ec; border-bottom: none;
    border-radius: 10px 10px 0 0; background: #f8fafc; font-family: inherit;
}
.vf-quill-wrap .ql-container.ql-snow {
    border: 1.5px solid #dde3ec; border-top: none;
    border-radius: 0 0 10px 10px; font-size: 14px; font-family: inherit;
}
.vf-quill-wrap.short .ql-editor { min-height: 70px; }
.vf-quill-wrap.full  .ql-editor { min-height: 160px; }
.vf-quill-wrap .ql-editor { line-height: 1.6; color: #2d2d2d; }
.vf-quill-wrap .ql-container.ql-snow:focus-within { border-color: #1565c0; }
</style>
<style>
/* ============================================================
   VENDOR PRODUCT FORM — Multi-step Wizard
   ============================================================ */

/* ---------- page header ---------- */
.vf-page-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 100%);
    border-radius: 12px; padding: 28px 32px; margin-bottom: 28px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
    position: relative; overflow: hidden;
}
.vf-page-header::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.vf-page-header__icon {
    width: 64px; height: 64px; background: rgba(255,255,255,0.1);
    border-radius: 16px; display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #fff; flex-shrink: 0;
    border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(4px);
}
.vf-page-header__text h2 { color: #fff; font-size: 24px; font-weight: 700; margin-bottom: 4px; }
.vf-page-header__text p  { color: rgba(255,255,255,0.65); margin: 0; font-size: 14px; }
.vf-page-header__badge {
    background: linear-gradient(135deg, #e53935, #ff6f61);
    color: #fff; padding: 6px 16px; border-radius: 20px;
    font-size: 12px; font-weight: 700; letter-spacing: 0.5px;
    text-transform: uppercase; white-space: nowrap;
}
.vf-page-header__badge.is-edit { background: linear-gradient(135deg, #1565c0, #42a5f5); }

/* ---------- steps bar ---------- */
.vf-steps {
    display: flex; gap: 0; margin-bottom: 28px;
    background: #fff; border-radius: 10px; padding: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #eef0f5;
    overflow-x: auto;
}
.vf-step {
    flex: 1; display: flex; align-items: center; gap: 8px;
    padding: 10px 14px; border-radius: 8px;
    font-size: 12px; font-weight: 600; color: #aaa;
    white-space: nowrap; min-width: 110px; cursor: default;
    transition: all 0.2s;
}
.vf-step.done  { color: #2e7d32; }
.vf-step.active { background: #f0f4ff; color: #1565c0; }
.vf-step .vf-step__num {
    width: 22px; height: 22px; border-radius: 50%; font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    background: #e0e0e0; color: #999; flex-shrink: 0; transition: all 0.2s;
}
.vf-step.active .vf-step__num { background: linear-gradient(135deg,#1565c0,#42a5f5); color: #fff; }
.vf-step.done  .vf-step__num  { background: linear-gradient(135deg,#2e7d32,#66bb6a); color: #fff; }

/* ---------- step panels ---------- */
.vf-step-panel { display: none; animation: stepFadeIn 0.25s ease; }
.vf-step-panel.active { display: block; }
@keyframes stepFadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

/* ---------- section cards ---------- */
.vf-card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07); margin-bottom: 24px;
    overflow: hidden; border: 1px solid #eef0f5; transition: box-shadow 0.2s;
}
.vf-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.10); }
.vf-card__head {
    display: flex; align-items: center; gap: 14px; padding: 18px 24px;
    border-bottom: 2px solid #f5f7fa; position: relative;
}
.vf-card__head::after {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 4px; border-radius: 0 2px 2px 0;
}
.vf-card--blue   .vf-card__head::after { background: linear-gradient(180deg,#1565c0,#42a5f5); }
.vf-card--green  .vf-card__head::after { background: linear-gradient(180deg,#2e7d32,#66bb6a); }
.vf-card--orange .vf-card__head::after { background: linear-gradient(180deg,#e65100,#ffa726); }
.vf-card--purple .vf-card__head::after { background: linear-gradient(180deg,#6a1b9a,#ab47bc); }
.vf-card--teal   .vf-card__head::after { background: linear-gradient(180deg,#00695c,#4db6ac); }
.vf-card--red    .vf-card__head::after { background: linear-gradient(180deg,#b71c1c,#ef5350); }

.vf-card__icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: #fff; flex-shrink: 0;
}
.vf-card--blue   .vf-card__icon { background: linear-gradient(135deg,#1565c0,#42a5f5); }
.vf-card--green  .vf-card__icon { background: linear-gradient(135deg,#2e7d32,#66bb6a); }
.vf-card--orange .vf-card__icon { background: linear-gradient(135deg,#e65100,#ffa726); }
.vf-card--purple .vf-card__icon { background: linear-gradient(135deg,#6a1b9a,#ab47bc); }
.vf-card--teal   .vf-card__icon { background: linear-gradient(135deg,#00695c,#4db6ac); }
.vf-card--red    .vf-card__icon { background: linear-gradient(135deg,#b71c1c,#ef5350); }

.vf-card__title    { font-size: 16px; font-weight: 700; color: #1a1a2e; margin: 0; }
.vf-card__subtitle { font-size: 12px; color: #888; margin: 0; }
.vf-card__body     { padding: 24px; }

/* ---------- form fields ---------- */
.vf-label {
    font-size: 12px; font-weight: 700; color: #555;
    text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; display: block;
}
.vf-label .req { color: #e53935; margin-left: 2px; }
.vf-input, .vf-select, .vf-textarea {
    width: 100%; border: 2px solid #e8ecf0; border-radius: 8px;
    padding: 10px 14px; font-size: 14px; color: #2d2d2d; background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s; outline: none; -webkit-appearance: none;
}
.vf-input:focus, .vf-select:focus, .vf-textarea:focus {
    border-color: #1565c0; box-shadow: 0 0 0 3px rgba(21,101,192,0.12);
}
.vf-input.is-invalid, .vf-select.is-invalid, .vf-textarea.is-invalid {
    border-color: #e53935; box-shadow: 0 0 0 3px rgba(229,57,53,0.1);
}
.vf-input-group { position: relative; }
.vf-input-group .vf-prefix {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #777; font-weight: 600; font-size: 14px; pointer-events: none; z-index: 1;
}
.vf-input-group .vf-input { padding-left: 28px; }
.vf-input-group .vf-suffix {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #aaa; font-size: 12px; pointer-events: none;
}
.vf-textarea { resize: vertical; min-height: 90px; line-height: 1.6; }
.vf-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23555' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; cursor: pointer;
}
.vf-char-counter { font-size: 11px; color: #aaa; text-align: right; margin-top: 3px; }

/* ---------- pill selectors ---------- */
.vf-pill-group { display: flex; gap: 8px; flex-wrap: wrap; }
.vf-pill-group input[type="radio"] { display: none; }
.vf-pill-group label {
    padding: 8px 18px; border-radius: 25px; font-size: 13px; font-weight: 600;
    border: 2px solid #e0e0e0; color: #666; cursor: pointer; transition: all 0.18s; user-select: none;
}
.vf-pill-group input[type="radio"]:checked + label { background: #1a1a2e; border-color: #1a1a2e; color: #fff; }
.vf-pill-group label:hover { border-color: #1565c0; color: #1565c0; }

/* ---------- price / discount ---------- */
.vf-price-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
@media (max-width: 767px) { .vf-price-row { grid-template-columns: 1fr 1fr; } }
.vf-discount-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: linear-gradient(135deg,#e53935,#ef9a9a);
    color: #fff; font-size: 11px; font-weight: 700;
    padding: 3px 10px; border-radius: 12px; margin-top: 6px; opacity: 0; transition: opacity 0.3s;
}
.vf-discount-badge.visible { opacity: 1; }

/* ---------- shipping toggle ---------- */
.vf-toggle-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; background: #f8f9fc; border-radius: 8px; border: 2px solid #e8ecf0;
}
.vf-toggle-label { font-size: 14px; font-weight: 600; color: #333; }
.vf-toggle-sub { font-size: 12px; color: #888; }
.vf-switch { position: relative; width: 44px; height: 24px; }
.vf-switch input { opacity: 0; width: 0; height: 0; }
.vf-switch__slider { position: absolute; inset: 0; background: #ccc; border-radius: 24px; cursor: pointer; transition: 0.25s; }
.vf-switch__slider::before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: 0.25s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
.vf-switch input:checked + .vf-switch__slider { background: linear-gradient(135deg,#2e7d32,#66bb6a); }
.vf-switch input:checked + .vf-switch__slider::before { transform: translateX(20px); }

/* ---------- image upload zone ---------- */
.vf-dropzone {
    border: 3px dashed #c5cae9; border-radius: 14px; padding: 40px 20px;
    text-align: center; background: linear-gradient(135deg,#f5f7ff 0%,#eef1fb 100%);
    cursor: pointer; transition: all 0.2s; position: relative;
}
.vf-dropzone.dragover { border-color: #1565c0; background: linear-gradient(135deg,#e3f2fd,#bbdefb); transform: scale(1.01); }
.vf-dropzone__icon { font-size: 48px; color: #7986cb; margin-bottom: 12px; }
.vf-dropzone__title { font-size: 16px; font-weight: 700; color: #3949ab; margin-bottom: 6px; }
.vf-dropzone__sub { font-size: 13px; color: #7986cb; }
.vf-dropzone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.vf-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px,1fr)); gap: 12px; margin-top: 16px; }
.vf-preview-item {
    position: relative; border-radius: 10px; overflow: hidden;
    aspect-ratio: 1; background: #f0f2f8; border: 2px solid #e0e4f0; transition: border-color 0.2s, box-shadow 0.2s;
}
.vf-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.vf-preview-item__controls {
    position: absolute; inset: 0; background: rgba(10,10,30,0.6);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 6px; opacity: 0; transition: opacity 0.2s;
}
.vf-preview-item:hover .vf-preview-item__controls { opacity: 1; }
.vf-preview-item__primary, .vf-preview-item__delete {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 10px; border: none; cursor: pointer; white-space: nowrap;
}
.vf-preview-item__primary { background: #fff; color: #1565c0; }
.vf-preview-item__delete  { background: #e53935; color: #fff; }
.vf-preview-item--primary { border-color: #1565c0 !important; box-shadow: 0 0 0 3px rgba(21,101,192,0.25); }
.vf-preview-item--primary::after {
    content: '★ Primary'; position: absolute; top: 6px; left: 6px;
    background: #1565c0; color: #fff; font-size: 10px; font-weight: 700;
    padding: 2px 7px; border-radius: 8px;
}
.vf-db-imgs { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px,1fr)); gap: 12px; margin-bottom: 16px; }
.vf-db-item { position: relative; border-radius: 10px; overflow: hidden; aspect-ratio: 1; border: 2px solid #e0e4f0; }
.vf-db-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.vf-db-item__overlay {
    position: absolute; inset: 0; background: rgba(10,10,30,0.6);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 6px; opacity: 0; transition: opacity 0.2s; padding: 4px;
}
.vf-db-item:hover .vf-db-item__overlay { opacity: 1; }
.vf-db-item.is-primary { border-color: #1565c0; box-shadow: 0 0 0 3px rgba(21,101,192,0.25); }
.vf-db-item.is-marked-delete { opacity: 0.4; border-color: #e53935; }
.vf-db-item.is-marked-delete::before {
    content: '✕ Deleted'; position: absolute; inset: 0; background: rgba(229,57,53,0.8);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; z-index: 2;
}
.vf-db-btn { font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 8px; border: none; cursor: pointer; }
.vf-db-btn--primary { background: #fff; color: #1565c0; }
.vf-db-btn--delete  { background: #e53935; color: #fff; }
.vf-db-primary-star {
    position: absolute; top: 5px; left: 5px;
    background: #1565c0; color: #fff; font-size: 9px; font-weight: 700;
    padding: 2px 6px; border-radius: 8px; z-index: 1;
}

/* ---------- info box ---------- */
.vf-info-box {
    background: linear-gradient(135deg,#e3f2fd,#bbdefb);
    border-left: 4px solid #1565c0; border-radius: 8px;
    padding: 14px 18px; display: flex; align-items: flex-start; gap: 12px;
}
.vf-info-box i { color: #1565c0; font-size: 18px; margin-top: 1px; flex-shrink: 0; }
.vf-info-box p { margin: 0; font-size: 13px; color: #1565c0; font-weight: 500; line-height: 1.5; }

/* ---------- step navigation bar ---------- */
.vf-nav-bar {
    background: #fff; border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.10);
    padding: 20px 24px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    border: 1px solid #eef0f5; margin-bottom: 32px; flex-wrap: wrap;
}
.vf-nav-bar__hint { font-size: 13px; color: #888; }
.vf-nav-bar__hint strong { color: #333; }
.vf-btn-next {
    background: linear-gradient(135deg,#1565c0,#42a5f5);
    color: #fff; border: none; border-radius: 8px;
    padding: 12px 28px; font-size: 14px; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.2s; box-shadow: 0 4px 14px rgba(21,101,192,0.3);
}
.vf-btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(21,101,192,0.4); }
.vf-btn-back {
    background: #f1f5f9; color: #374151; border: 1.5px solid #e2e8f0;
    border-radius: 8px; padding: 11px 22px; font-size: 14px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;
}
.vf-btn-back:hover { background: #e2e8f0; }
.vf-btn-submit {
    background: linear-gradient(135deg,#1a1a2e,#0f3460);
    color: #fff; border: none; border-radius: 8px;
    padding: 12px 32px; font-size: 15px; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.2s; letter-spacing: 0.3px;
    box-shadow: 0 4px 15px rgba(15,52,96,0.35);
}
.vf-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15,52,96,0.45); color: #fff; }
.vf-btn-cancel {
    color: #666; font-size: 14px; font-weight: 600;
    text-decoration: none; padding: 10px 16px; border-radius: 8px; transition: background 0.15s;
}
.vf-btn-cancel:hover { background: #f5f5f5; color: #333; text-decoration: none; }

/* ---------- step error alert ---------- */
.vf-step-error {
    background: #fff0f0; border-left: 4px solid #e53935;
    border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;
    font-size: 13px; color: #c62828; font-weight: 600;
    display: none; align-items: center; gap: 8px;
}
.vf-step-error.show { display: flex; }

/* ---------- field helpers ---------- */
.vf-row { display: flex; gap: 16px; flex-wrap: wrap; }
.vf-col { flex: 1; min-width: 140px; }
.vf-col-2 { flex: 2; min-width: 220px; }
.vf-col-3 { flex: 3; min-width: 240px; }
.vf-fgroup { margin-bottom: 18px; }
.vf-error { font-size: 12px; color: #e53935; margin-top: 4px; font-weight: 500; }

/* ---------- Item Specifics ---------- */
.specs-loading { text-align:center; padding:36px; color:#94a3b8; font-size:13.5px; }
.specs-loading i { font-size:28px; display:block; margin-bottom:10px; }
.specs-empty { text-align:center; padding:28px; color:#94a3b8; font-size:13px; }
.specs-empty i { font-size:22px; margin-bottom:6px; display:block; }
.spec-section-label {
    font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
    color:#fff; padding:5px 13px; border-radius:20px; margin-bottom:14px; display:inline-block;
}
.spec-section-label--essential { background:linear-gradient(135deg,#1565c0,#42a5f5); }
.spec-section-label--optional  { background:linear-gradient(135deg,#455a64,#78909c); }
.spec-field-wrap { margin-bottom:16px; }
.spec-toggle-pills { display:flex; gap:8px; }
.spec-toggle-pill {
    padding:8px 18px; border-radius:20px; font-size:13px; font-weight:600;
    border:2px solid #e0e0e0; color:#666; cursor:pointer; transition:all .18s;
    background:#fff; user-select:none;
}
.spec-toggle-pill.active { background:#1a1a2e; border-color:#1a1a2e; color:#fff; }
.spec-range-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }

/* ---------- Auction ---------- */
.auction-explainer {
    background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%);
    border-radius:12px; padding:18px 20px; margin-bottom:20px;
    display:flex; align-items:flex-start; gap:14px;
}
.auction-explainer__icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    background:rgba(255,255,255,.15); display:flex; align-items:center;
    justify-content:center; font-size:20px; color:#fff;
}
.auction-explainer__text h6 { color:#fff; font-size:14px; font-weight:700; margin:0 0 4px; }
.auction-explainer__text p  { color:rgba(255,255,255,.75); font-size:12.5px; margin:0; line-height:1.6; }
.auction-duration-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:8px; }
.dur-opt { position:relative; }
.dur-opt input { position:absolute; opacity:0; width:0; height:0; }
.dur-opt label {
    display:block; text-align:center; padding:11px 8px; border-radius:10px;
    border:2px solid #e0e0e0; cursor:pointer; font-size:13px; font-weight:700;
    color:#555; transition:all .18s; background:#f8f8f8;
}
.dur-opt input:checked + label { background:#1a1a2e; border-color:#1a1a2e; color:#fff; }
.dur-opt label:hover { border-color:#1565c0; color:#1565c0; }
.dur-opt label span { display:block; font-size:10px; font-weight:500; margin-top:2px; opacity:.7; }
</style>
@endpush

@section('vendor_content')

<form id="vf-form"
      action="{{ $editing ? route('vendor.products.update', $product) : route('vendor.products.store') }}"
      method="POST" enctype="multipart/form-data" novalidate>
@csrf
@if ($editing) @method('PUT') @endif

{{-- ===== Page header ===== --}}
<div class="vf-page-header">
    <div class="vf-page-header__icon">
        <i class="fas {{ $editing ? 'fa-edit' : 'fa-box-open' }}"></i>
    </div>
    <div class="vf-page-header__text" style="flex:1">
        <h2>{{ $editing ? 'Edit Product' : 'List a New Product' }}</h2>
        <p>{{ $editing ? 'Update your product details — changes go back for admin review.' : 'Fill in the details below to add a new item to your store.' }}</p>
    </div>
    <span class="vf-page-header__badge {{ $editing ? 'is-edit' : '' }}">
        <i class="fas {{ $editing ? 'fa-pen' : 'fa-plus' }} me-1"></i>
        {{ $editing ? 'Editing' : 'New Listing' }}
    </span>
</div>

{{-- ===== Steps bar ===== --}}
<div class="vf-steps" id="vf-steps-bar">
    @php
        $stepDefs = [
            ['fa-tag',        'Product Info'],
            ['fa-dollar-sign','Pricing'],
            ['fa-sitemap',    'Organisation'],
            ['fa-images',     'Images'],
        ];
    @endphp
    @foreach ($stepDefs as $i => [$icon, $label])
        <div class="vf-step {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i+1 }}">
            <div class="vf-step__num">{{ $i+1 }}</div>
            <i class="fas {{ $icon }}" style="font-size:13px"></i>
            {{ $label }}
        </div>
    @endforeach
</div>

{{-- ============================================================
     STEP 1 — Product Information
     ============================================================ --}}
<div class="vf-step-panel active" data-panel="1">

    <div class="vf-step-error" id="err-1">
        <i class="fas fa-exclamation-circle"></i>
        <span id="err-1-msg">Please fill in all required fields.</span>
    </div>

    <div class="vf-card vf-card--blue">
        <div class="vf-card__head">
            <div class="vf-card__icon"><i class="fas fa-tag"></i></div>
            <div>
                <div class="vf-card__title">Product Information</div>
                <div class="vf-card__subtitle">Name, descriptions and core details</div>
            </div>
        </div>
        <div class="vf-card__body">

            <div class="vf-row">
                <div class="vf-col-3 vf-fgroup">
                    <label class="vf-label">Product Name <span class="req">*</span></label>
                    <input type="text" name="name" id="vf-name"
                           class="vf-input @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}"
                           placeholder="e.g. Samsung Galaxy S25 Ultra 256GB"
                           maxlength="255" required>
                    @error('name')<div class="vf-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">SKU / Model No.</label>
                    <input type="text" name="sku"
                           class="vf-input @error('sku') is-invalid @enderror"
                           value="{{ old('sku', $product->sku) }}"
                           placeholder="e.g. SM-S928B">
                    @error('sku')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="vf-fgroup">
                <label class="vf-label">Short Description</label>
                <input type="hidden" name="short_description" id="vf-short-desc-input"
                       value="{{ old('short_description', $product->short_description) }}">
                <div class="vf-quill-wrap short">
                    <div id="vf-short-desc-editor">{{ old('short_description', $product->short_description) }}</div>
                </div>
                <div class="vf-char-counter"><span id="sd-count">0</span> / 500</div>
                @error('short_description')<div class="vf-error">{{ $message }}</div>@enderror
            </div>

            <div class="vf-fgroup" style="margin-bottom:0">
                <label class="vf-label">Full Description</label>
                <input type="hidden" name="description" id="vf-desc-input"
                       value="{{ old('description', $product->description) }}">
                <div class="vf-quill-wrap full">
                    <div id="vf-desc-editor">{{ old('description', $product->description) }}</div>
                </div>
                @error('description')<div class="vf-error">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>

    <div class="vf-nav-bar">
        <div class="vf-nav-bar__hint">Step 1 of 4 — <strong>Product Information</strong></div>
        <div style="display:flex;align-items:center;gap:12px">
            <a href="{{ route('vendor.products.index') }}" class="vf-btn-cancel">
                <i class="fas fa-arrow-left" style="margin-right:4px"></i> Cancel
            </a>
            <button type="button" class="vf-btn-next" onclick="goToStep(2)">
                Next: Pricing <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>

{{-- ============================================================
     STEP 2 — Pricing, Stock & Shipping
     ============================================================ --}}
<div class="vf-step-panel" data-panel="2">

    <div class="vf-step-error" id="err-2">
        <i class="fas fa-exclamation-circle"></i>
        <span id="err-2-msg">Please fill in all required fields.</span>
    </div>

    <div class="vf-card vf-card--green">
        <div class="vf-card__head">
            <div class="vf-card__icon"><i class="fas fa-dollar-sign"></i></div>
            <div>
                <div class="vf-card__title">Pricing, Stock &amp; Shipping</div>
                <div class="vf-card__subtitle">Set price, stock quantity, tax and delivery options</div>
            </div>
        </div>
        <div class="vf-card__body">

            <div class="vf-row">
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">Regular Price <span class="req">*</span></label>
                    <div class="vf-input-group">
                        <span class="vf-prefix">$</span>
                        <input type="number" step="0.01" min="0" name="price" id="vf-price"
                               class="vf-input @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}"
                               placeholder="0.00" required>
                    </div>
                    @error('price')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">Sale Price <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#aaa">(optional)</span></label>
                    <div class="vf-input-group">
                        <span class="vf-prefix">$</span>
                        <input type="number" step="0.01" min="0" name="sale_price" id="vf-sale"
                               class="vf-input @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price', $product->sale_price) }}"
                               placeholder="Leave blank for no discount">
                    </div>
                    <div class="vf-discount-badge" id="vf-discount-badge">
                        <i class="fas fa-tag"></i> <span id="vf-discount-pct">0</span>% off
                    </div>
                    @error('sale_price')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">Stock Quantity <span class="req">*</span></label>
                    <div class="vf-input-group">
                        <input type="number" min="0" name="stock"
                               class="vf-input @error('stock') is-invalid @enderror"
                               value="{{ old('stock', $product->stock ?? 0) }}"
                               placeholder="0" required>
                        <span class="vf-suffix">units</span>
                    </div>
                    @error('stock')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="height:1px;background:#f0f2f5;margin:4px 0 20px"></div>

            <div class="vf-row">
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">Shipping Cost</label>
                    <div class="vf-input-group">
                        <span class="vf-prefix">$</span>
                        <input type="number" step="0.01" min="0" name="shipping_cost" id="vf-ship-cost"
                               class="vf-input @error('shipping_cost') is-invalid @enderror"
                               value="{{ old('shipping_cost', $product->shipping_cost ?? 0) }}"
                               placeholder="0.00">
                    </div>
                    @error('shipping_cost')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">Tax Class</label>
                    <select name="tax_class_id" class="vf-select @error('tax_class_id') is-invalid @enderror">
                        <option value="">— No Tax —</option>
                        @foreach ($taxClasses as $tax)
                            <option value="{{ $tax->id }}" @selected(old('tax_class_id', $product->tax_class_id) == $tax->id)>
                                {{ $tax->name }} ({{ rtrim(rtrim(number_format($tax->rate,2),'0'),'.') }}%)
                            </option>
                        @endforeach
                    </select>
                    @error('tax_class_id')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="vf-toggle-row">
                <div>
                    <div class="vf-toggle-label"><i class="fas fa-truck" style="color:#2e7d32;margin-right:6px"></i> Offer Free Shipping</div>
                    <div class="vf-toggle-sub">Overrides the shipping cost above for customers</div>
                </div>
                <label class="vf-switch">
                    <input type="checkbox" name="free_shipping" id="vf-free-ship" value="1"
                           @checked(old('free_shipping', $product->free_shipping))
                           onchange="document.getElementById('vf-ship-cost').disabled=this.checked">
                    <span class="vf-switch__slider"></span>
                </label>
            </div>

        </div>
    </div>

    <div class="vf-nav-bar">
        <div class="vf-nav-bar__hint">Step 2 of 4 — <strong>Pricing &amp; Stock</strong></div>
        <div style="display:flex;align-items:center;gap:12px">
            <button type="button" class="vf-btn-back" onclick="goToStep(1)">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <button type="button" class="vf-btn-next" onclick="goToStep(3)">
                Next: Organisation <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>

{{-- ============================================================
     STEP 3 — Organisation, Specifics & Auction
     ============================================================ --}}
<div class="vf-step-panel" data-panel="3">

    <div class="vf-step-error" id="err-3">
        <i class="fas fa-exclamation-circle"></i>
        <span id="err-3-msg">Please fill in all required fields.</span>
    </div>

    <div class="vf-card vf-card--orange">
        <div class="vf-card__head">
            <div class="vf-card__icon"><i class="fas fa-sitemap"></i></div>
            <div>
                <div class="vf-card__title">Organisation &amp; Classification</div>
                <div class="vf-card__subtitle">Category, brand, condition and listing type</div>
            </div>
        </div>
        <div class="vf-card__body">

            <div class="vf-row">
                <div class="vf-col-2 vf-fgroup">
                    <label class="vf-label">Category</label>
                    <select name="category_id" class="vf-select @error('category_id') is-invalid @enderror">
                        <option value="">— Select Category —</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                                {{ $cat->parent_id ? '↳ ' : '' }}{{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
                <div class="vf-col-2 vf-fgroup">
                    <label class="vf-label">Brand</label>
                    <select name="brand_id" class="vf-select @error('brand_id') is-invalid @enderror">
                        <option value="">— Select Brand —</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="vf-fgroup">
                <label class="vf-label">Condition <span class="req">*</span></label>
                <div class="vf-pill-group">
                    @foreach (['new' => ['New','fa-star'], 'used' => ['Used','fa-recycle'], 'refurbished' => ['Refurbished','fa-tools']] as $val => [$lbl, $ico])
                        <input type="radio" name="condition" id="cond-{{ $val }}" value="{{ $val }}"
                               @checked(old('condition', $product->condition ?? 'new') === $val)>
                        <label for="cond-{{ $val }}"><i class="fas {{ $ico }}" style="margin-right:5px"></i>{{ $lbl }}</label>
                    @endforeach
                </div>
                @error('condition')<div class="vf-error">{{ $message }}</div>@enderror
            </div>

            <div class="vf-fgroup" style="margin-bottom:0">
                <label class="vf-label">Listing Type <span class="req">*</span></label>
                <div class="vf-pill-group">
                    <input type="radio" name="listing_type" id="type-fixed" value="fixed"
                           @checked(old('listing_type', $product->listing_type ?? 'fixed') === 'fixed')>
                    <label for="type-fixed"><i class="fas fa-tag" style="margin-right:5px"></i>Fixed Price / Buy Now</label>
                    <input type="radio" name="listing_type" id="type-auction" value="auction"
                           @checked(old('listing_type', $product->listing_type) === 'auction')>
                    <label for="type-auction"><i class="fas fa-gavel" style="margin-right:5px"></i>Auction / Bidding</label>
                </div>
                @error('listing_type')<div class="vf-error">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>

    {{-- Item Specifics (AJAX) --}}
    <div class="vf-card vf-card--teal" id="vf-specs-card">
        <div class="vf-card__head">
            <div class="vf-card__icon"><i class="fas fa-list-alt"></i></div>
            <div>
                <div class="vf-card__title">Item Specifics</div>
                <div class="vf-card__subtitle">Details that help buyers find and understand your listing</div>
            </div>
        </div>
        <div class="vf-card__body" id="vf-specs-body">
            <div class="specs-empty">
                <i class="fas fa-hand-point-up"></i>
                Select a category above to load item specifics for this listing.
            </div>
        </div>
    </div>

    {{-- Auction Settings (shown when auction selected) --}}
    <div class="vf-card vf-card--red" id="vf-auction-card" style="display:none;">
        <div class="vf-card__head">
            <div class="vf-card__icon"><i class="fas fa-gavel"></i></div>
            <div>
                <div class="vf-card__title">Auction Settings</div>
                <div class="vf-card__subtitle">Set starting bid, reserve price and duration</div>
            </div>
        </div>
        <div class="vf-card__body">

            <div class="auction-explainer">
                <div class="auction-explainer__icon"><i class="fas fa-gavel"></i></div>
                <div class="auction-explainer__text">
                    <h6>How Auctions Work</h6>
                    <p>Buyers compete by placing bids. Each bid must be higher than the last. When the timer ends, the highest bidder wins. You can set a hidden <em>reserve price</em> — if bids don't reach it, no sale is made. Duration options: 1, 3, 5, 7 or 10 days.</p>
                </div>
            </div>

            <div class="vf-row">
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">Starting Bid <span class="req">*</span></label>
                    <div class="vf-input-group">
                        <span class="vf-prefix">$</span>
                        <input type="number" step="0.01" min="0.01" name="starting_bid" id="vf-starting-bid"
                               class="vf-input @error('starting_bid') is-invalid @enderror"
                               value="{{ old('starting_bid', $product->starting_bid) }}"
                               placeholder="1.00">
                    </div>
                    @error('starting_bid')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
                <div class="vf-col vf-fgroup">
                    <label class="vf-label">Reserve Price <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#aaa">(optional)</span></label>
                    <div class="vf-input-group">
                        <span class="vf-prefix">$</span>
                        <input type="number" step="0.01" min="0" name="reserve_price" id="vf-reserve"
                               class="vf-input @error('reserve_price') is-invalid @enderror"
                               value="{{ old('reserve_price', $product->reserve_price) }}"
                               placeholder="Hidden minimum — leave blank for none">
                    </div>
                    <div style="font-size:11.5px;color:#888;margin-top:4px;">
                        <i class="fas fa-eye-slash"></i> Hidden from buyers. If no bid meets this, the item won't sell.
                    </div>
                    @error('reserve_price')<div class="vf-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="vf-fgroup" style="margin-bottom:0">
                <label class="vf-label">Auction Duration <span class="req">*</span></label>
                <div class="auction-duration-grid">
                    @foreach ([1,3,5,7,10] as $days)
                    <div class="dur-opt">
                        <input type="radio" name="auction_duration" id="dur-{{ $days }}" value="{{ $days }}"
                               @checked(old('auction_duration', $product->auction_duration ?? 7) == $days)>
                        <label for="dur-{{ $days }}">
                            {{ $days }}<span>{{ $days === 1 ? 'Day' : 'Days' }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('auction_duration')<div class="vf-error">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>

    <div class="vf-nav-bar">
        <div class="vf-nav-bar__hint">Step 3 of 4 — <strong>Organisation</strong></div>
        <div style="display:flex;align-items:center;gap:12px">
            <button type="button" class="vf-btn-back" onclick="goToStep(2)">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <button type="button" class="vf-btn-next" onclick="goToStep(4)">
                Next: Images <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>

{{-- ============================================================
     STEP 4 — Images & Submit
     ============================================================ --}}
<div class="vf-step-panel" data-panel="4">

    <div class="vf-card vf-card--purple">
        <div class="vf-card__head">
            <div class="vf-card__icon"><i class="fas fa-images"></i></div>
            <div>
                <div class="vf-card__title">Product Images</div>
                <div class="vf-card__subtitle">Upload high-quality photos — first image is the cover unless you choose a primary</div>
            </div>
        </div>
        <div class="vf-card__body">

            @if ($editing && $product->images->count())
                <label class="vf-label" style="margin-bottom:10px">Current Images</label>
                <div class="vf-db-imgs" id="vf-db-imgs">
                    @foreach ($product->images as $img)
                        <div class="vf-db-item {{ $img->is_primary ? 'is-primary' : '' }}" id="db-item-{{ $img->id }}">
                            <img src="{{ str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path) }}" alt="">
                            @if ($img->is_primary)
                                <div class="vf-db-primary-star">★ Primary</div>
                            @endif
                            <input type="radio" name="primary_image" value="{{ $img->id }}"
                                   id="pri-{{ $img->id }}" class="d-none" @checked($img->is_primary)>
                            <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"
                                   id="del-{{ $img->id }}" class="d-none">
                            <div class="vf-db-item__overlay">
                                <button type="button" class="vf-db-btn vf-db-btn--primary"
                                        onclick="setPrimary({{ $img->id }})">★ Set Primary</button>
                                <button type="button" class="vf-db-btn vf-db-btn--delete"
                                        onclick="toggleDelete({{ $img->id }})">✕ Delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="height:1px;background:#f0f2f5;margin:0 0 16px"></div>
            @endif

            <div class="vf-dropzone" id="vf-dropzone">
                <input type="file" name="images[]" id="vf-file-input" accept="image/*" multiple>
                <div class="vf-dropzone__icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="vf-dropzone__title">Drag &amp; drop images here</div>
                <div class="vf-dropzone__sub">or click to browse — PNG, JPG, WEBP up to 2 MB each · Auto-resized to 800×800</div>
            </div>

            <div class="vf-preview-grid" id="vf-preview-grid"></div>

        </div>
    </div>

    <div class="vf-info-box" style="margin-bottom:24px">
        <i class="fas fa-shield-alt"></i>
        <p><strong>Admin Review Required.</strong> Your product will be reviewed before going live on the store. You will be notified once it's approved. Editing an existing product re-submits it for review.</p>
    </div>

    <div class="vf-nav-bar">
        <div class="vf-nav-bar__hint">Step 4 of 4 — <strong>Product Images</strong></div>
        <div style="display:flex;align-items:center;gap:12px">
            <button type="button" class="vf-btn-back" onclick="goToStep(3)">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <button type="submit" class="vf-btn-submit">
                <i class="fas {{ $editing ? 'fa-save' : 'fa-paper-plane' }}"></i>
                {{ $editing ? 'Save Changes' : 'Submit for Review' }}
            </button>
        </div>
    </div>

</div>

</form>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
(function () {

    /* ================================================================
       STEP WIZARD
       ================================================================ */
    var currentStep = 1;
    var totalSteps  = 4;

    // If there are server-side validation errors, jump to the step that has them
    @if ($errors->any())
    (function() {
        var step1Fields = ['name','short_description','description','sku'];
        var step2Fields = ['price','sale_price','stock','shipping_cost','tax_class_id'];
        var step3Fields = ['category_id','brand_id','condition','listing_type','starting_bid','reserve_price','auction_duration'];
        var errors = @json($errors->keys());
        if (errors.some(function(k){ return step3Fields.includes(k); })) { currentStep = 3; }
        else if (errors.some(function(k){ return step2Fields.includes(k); })) { currentStep = 2; }
        else { currentStep = 1; }
    })();
    @endif

    function showStep(n) {
        // Hide all panels
        document.querySelectorAll('.vf-step-panel').forEach(function(p) {
            p.classList.remove('active');
        });
        // Show target panel
        var panel = document.querySelector('[data-panel="' + n + '"]');
        if (panel) panel.classList.add('active');

        // Update steps bar
        document.querySelectorAll('.vf-step').forEach(function(s, idx) {
            s.classList.remove('active', 'done');
            var num = idx + 1;
            if (num < n) {
                s.classList.add('done');
                s.querySelector('.vf-step__num').innerHTML = '<i class="fas fa-check" style="font-size:9px"></i>';
            } else if (num === n) {
                s.classList.add('active');
                s.querySelector('.vf-step__num').textContent = num;
            } else {
                s.querySelector('.vf-step__num').textContent = num;
            }
        });

        // Scroll to top of form
        var header = document.querySelector('.vf-page-header');
        if (header) header.scrollIntoView({ behavior: 'smooth', block: 'start' });

        currentStep = n;
    }

    function validateStep(n) {
        var errEl  = document.getElementById('err-' + n);
        var errMsg = document.getElementById('err-' + n + '-msg');
        var panel  = document.querySelector('[data-panel="' + n + '"]');
        if (!panel) return true;

        var invalid = false;
        var msg     = '';

        if (n === 1) {
            var name = panel.querySelector('[name="name"]');
            if (!name || !name.value.trim()) {
                invalid = true; msg = 'Product Name is required.';
                if (name) name.classList.add('is-invalid');
            } else {
                if (name) name.classList.remove('is-invalid');
            }
        }

        if (n === 2) {
            var isAuction = document.querySelector('[name="listing_type"]:checked')?.value === 'auction';
            if (!isAuction) {
                var price = panel.querySelector('[name="price"]');
                var stock = panel.querySelector('[name="stock"]');
                var ok = true;
                if (!price || !price.value || parseFloat(price.value) <= 0) {
                    invalid = true; msg = 'Regular Price is required.';
                    if (price) price.classList.add('is-invalid');
                    ok = false;
                } else { if (price) price.classList.remove('is-invalid'); }
                if (stock && stock.value === '') {
                    if (ok) { invalid = true; msg = 'Stock Quantity is required.'; }
                    stock.classList.add('is-invalid');
                } else { if (stock) stock.classList.remove('is-invalid'); }
            }
        }

        if (errEl && errMsg) {
            if (invalid) {
                errMsg.textContent = msg || 'Please fill in all required fields.';
                errEl.classList.add('show');
            } else {
                errEl.classList.remove('show');
            }
        }

        return !invalid;
    }

    window.goToStep = function(n) {
        // Validate current step before moving forward
        if (n > currentStep) {
            for (var s = currentStep; s < n; s++) {
                if (!validateStep(s)) return;
            }
        }
        showStep(n);
    };

    // Init
    showStep(currentStep);

    /* ================================================================
       QUILL — Short Description
       ================================================================ */
    var shortInput = document.getElementById('vf-short-desc-input');
    var sdCount    = document.getElementById('sd-count');
    var shortQuill = new Quill('#vf-short-desc-editor', {
        theme: 'snow',
        placeholder: 'A one-line summary shown in search results and product cards…',
        modules: { toolbar: [['bold', 'italic', 'underline'], ['clean']] }
    });
    function syncShort() {
        shortInput.value = shortQuill.root.innerHTML;
        if (sdCount) sdCount.textContent = shortQuill.getText().trim().length;
    }
    shortQuill.on('text-change', syncShort);
    syncShort();

    /* ================================================================
       QUILL — Full Description
       ================================================================ */
    var descInput = document.getElementById('vf-desc-input');
    var fullQuill = new Quill('#vf-desc-editor', {
        theme: 'snow',
        placeholder: 'Detailed product description — features, specifications, what\'s in the box…',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['blockquote', 'link'],
                ['clean']
            ]
        }
    });
    fullQuill.on('text-change', function() { descInput.value = fullQuill.root.innerHTML; });

    // Sync on submit
    document.getElementById('vf-form').addEventListener('submit', function() {
        shortInput.value = shortQuill.root.innerHTML;
        descInput.value  = fullQuill.root.innerHTML;
    });

    /* ================================================================
       DISCOUNT BADGE
       ================================================================ */
    var priceInput = document.getElementById('vf-price');
    var saleInput  = document.getElementById('vf-sale');
    var badge      = document.getElementById('vf-discount-badge');
    var pctSpan    = document.getElementById('vf-discount-pct');
    function calcDiscount() {
        var p = parseFloat(priceInput.value), s = parseFloat(saleInput.value);
        if (p > 0 && s > 0 && s < p) {
            pctSpan.textContent = Math.round((1 - s/p)*100);
            badge.classList.add('visible');
        } else { badge.classList.remove('visible'); }
    }
    if (priceInput && saleInput) {
        priceInput.addEventListener('input', calcDiscount);
        saleInput.addEventListener('input', calcDiscount);
        calcDiscount();
    }

    /* ================================================================
       FREE SHIPPING INIT
       ================================================================ */
    var freeShip = document.getElementById('vf-free-ship');
    var shipCost = document.getElementById('vf-ship-cost');
    if (freeShip && freeShip.checked && shipCost) shipCost.disabled = true;

    /* ================================================================
       IMAGE UPLOAD & PREVIEWS
       ================================================================ */
    var dropzone  = document.getElementById('vf-dropzone');
    var fileInput = document.getElementById('vf-file-input');
    var grid      = document.getElementById('vf-preview-grid');
    var newFiles  = [];

    function rebuildInput() {
        var dt = new DataTransfer();
        newFiles.forEach(function(f) { dt.items.add(f); });
        fileInput.files = dt.files;
    }

    function renderPreviews() {
        grid.innerHTML = '';
        newFiles.forEach(function (file, idx) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var item = document.createElement('div');
                item.className = 'vf-preview-item' + (idx === 0 && !hasPrimary() ? ' vf-preview-item--primary' : '');
                item.innerHTML =
                    '<img src="' + e.target.result + '" alt="">' +
                    '<div class="vf-preview-item__controls">' +
                    '<button type="button" class="vf-preview-item__primary" data-idx="' + idx + '">★ Primary</button>' +
                    '<button type="button" class="vf-preview-item__delete" data-idx="' + idx + '">✕ Remove</button>' +
                    '</div>';
                grid.appendChild(item);
                item.querySelector('.vf-preview-item__primary').addEventListener('click', function() {
                    grid.querySelectorAll('.vf-preview-item').forEach(function(el) { el.classList.remove('vf-preview-item--primary'); });
                    item.classList.add('vf-preview-item--primary');
                });
                item.querySelector('.vf-preview-item__delete').addEventListener('click', function() {
                    newFiles.splice(idx, 1); rebuildInput(); renderPreviews();
                });
            };
            reader.readAsDataURL(file);
        });
    }

    function hasPrimary() {
        var pri = document.getElementById('vf-db-imgs');
        return pri && pri.querySelector('.is-primary') !== null;
    }

    function addFiles(files) {
        Array.from(files).forEach(function(f) {
            if (f.type.startsWith('image/') && f.size <= 2*1024*1024) newFiles.push(f);
        });
        rebuildInput(); renderPreviews();
    }

    if (dropzone) {
        dropzone.addEventListener('dragover', function(e) { e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', function()  { dropzone.classList.remove('dragover'); });
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault(); dropzone.classList.remove('dragover'); addFiles(e.dataTransfer.files);
        });
        fileInput.addEventListener('change', function() { addFiles(fileInput.files); });
    }

    /* ================================================================
       EXISTING IMAGE CONTROLS
       ================================================================ */
    window.setPrimary = function(id) {
        document.querySelectorAll('[name="primary_image"]').forEach(function(r) { r.checked = false; });
        var r = document.getElementById('pri-' + id);
        if (r) r.checked = true;
        document.querySelectorAll('.vf-db-item').forEach(function(el) { el.classList.remove('is-primary'); });
        var item = document.getElementById('db-item-' + id);
        if (item) {
            item.classList.add('is-primary');
            if (!item.querySelector('.vf-db-primary-star')) {
                var star = document.createElement('div');
                star.className = 'vf-db-primary-star';
                star.textContent = '★ Primary';
                item.prepend(star);
            }
        }
    };

    window.toggleDelete = function(id) {
        var cb   = document.getElementById('del-' + id);
        var item = document.getElementById('db-item-' + id);
        if (!cb || !item) return;
        cb.checked = !cb.checked;
        item.classList.toggle('is-marked-delete', cb.checked);
        if (cb.checked && item.classList.contains('is-primary')) {
            item.classList.remove('is-primary');
            document.getElementById('pri-' + id).checked = false;
        }
    };

    /* ================================================================
       AUCTION TOGGLE
       ================================================================ */
    var auctionCard   = document.getElementById('vf-auction-card');
    var listingInputs = document.querySelectorAll('[name="listing_type"]');

    function toggleAuction() {
        var isAuction = document.querySelector('[name="listing_type"]:checked')?.value === 'auction';
        if (auctionCard) auctionCard.style.display = isAuction ? '' : 'none';
        var priceInput = document.getElementById('vf-price');
        if (priceInput) priceInput.required = !isAuction;
    }
    listingInputs.forEach(function(r) { r.addEventListener('change', toggleAuction); });
    toggleAuction();

    /* ================================================================
       ITEM SPECIFICS — AJAX
       ================================================================ */
    var categorySelect = document.querySelector('[name="category_id"]');
    var specsBody      = document.getElementById('vf-specs-body');
    var existingValues = @json($existingSpecs->map(fn($v) => $v->value));

    function buildSpecField(spec) {
        var name    = 'specs[' + spec.id + ']';
        var val     = existingValues[spec.id] ?? '';
        var ph      = spec.placeholder || '';
        var req     = spec.is_required ? 'required' : '';
        var reqStar = spec.is_required ? ' <span style="color:#e53935">*</span>' : '';
        var html    = '<div class="spec-field-wrap">';
        html       += '<label class="vf-label">' + spec.field_label + reqStar + '</label>';

        if (spec.field_type === 'text') {
            html += '<input type="text" name="' + name + '" class="vf-input" placeholder="' + ph + '" value="' + escHtml(val) + '" ' + req + '>';
        } else if (spec.field_type === 'textarea') {
            html += '<textarea name="' + name + '" class="vf-textarea" placeholder="' + ph + '" ' + req + '>' + escHtml(val) + '</textarea>';
        } else if (spec.field_type === 'number') {
            html += '<div class="vf-input-group">';
            html += '<input type="number" name="' + name + '" class="vf-input" placeholder="' + ph + '" value="' + escHtml(val) + '" ' + req + '>';
            if (spec.unit) html += '<span class="vf-suffix">' + escHtml(spec.unit) + '</span>';
            html += '</div>';
        } else if (spec.field_type === 'select') {
            html += '<select name="' + name + '" class="vf-select" ' + req + '><option value="">— Select —</option>';
            (spec.options || []).forEach(function(o) {
                html += '<option value="' + escHtml(o) + '"' + (val === o ? ' selected' : '') + '>' + escHtml(o) + '</option>';
            });
            html += '</select>';
        } else if (spec.field_type === 'multiselect') {
            var selectedArr = [];
            try { selectedArr = JSON.parse(val); } catch(e) {}
            (spec.options || []).forEach(function(o) {
                var chk = selectedArr.includes(o) ? 'checked' : '';
                html += '<div style="margin-bottom:6px;"><label style="display:flex;align-items:center;gap:8px;font-size:13.5px;cursor:pointer;">';
                html += '<input type="checkbox" name="specs[' + spec.id + '][]" value="' + escHtml(o) + '" ' + chk + ' style="width:16px;height:16px;accent-color:#1565c0;">';
                html += escHtml(o) + '</label></div>';
            });
        } else if (spec.field_type === 'toggle') {
            ['Yes','No'].forEach(function(opt) {
                var active = val === opt ? ' active' : '';
                html += '<span class="spec-toggle-pill' + active + '" onclick="specTogglePill(this,\'' + name + '\',\'' + opt + '\')">' + opt + '</span>';
            });
            html = '<div class="spec-toggle-pills">' + html + '</div>';
            html += '<input type="hidden" name="' + name + '" id="stv-' + spec.id + '" value="' + escHtml(val || 'No') + '">';
        } else if (spec.field_type === 'range') {
            var minVal = '', maxVal = '';
            if (val && val.indexOf('–') !== -1) { var p=val.split('–'); minVal=p[0].trim(); maxVal=p[1].trim(); }
            html += '<div class="spec-range-row">';
            html += '<input type="text" name="specs_range_min[' + spec.id + ']" class="vf-input" placeholder="Min' + (spec.unit ? ' ('+spec.unit+')' : '') + '" value="' + escHtml(minVal) + '">';
            html += '<input type="text" name="specs_range_max[' + spec.id + ']" class="vf-input" placeholder="Max' + (spec.unit ? ' ('+spec.unit+')' : '') + '" value="' + escHtml(maxVal) + '">';
            html += '</div>';
            html += '<input type="hidden" name="' + name + '" class="spec-range-hidden" data-spec-id="' + spec.id + '" value="' + escHtml(val) + '">';
        }
        html += '</div>';
        return html;
    }

    window.specTogglePill = function(el, name, val) {
        el.closest('.spec-field-wrap').querySelectorAll('.spec-toggle-pill').forEach(function(p) { p.classList.remove('active'); });
        el.classList.add('active');
        var specId = name.match(/\[(\d+)\]/)[1];
        var h = document.getElementById('stv-' + specId);
        if (h) h.value = val;
    };

    function escHtml(s) {
        if (!s) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function loadSpecs(categoryId) {
        if (!specsBody) return;
        if (!categoryId) {
            specsBody.innerHTML = '<div class="specs-empty"><i class="fas fa-hand-point-up"></i>Select a category above to load item specifics.</div>';
            return;
        }
        specsBody.innerHTML = '<div class="specs-loading"><i class="fas fa-spinner fa-spin"></i>Loading item specifics…</div>';
        fetch('{{ url('admin/api/categories') }}/' + categoryId + '/specifications')
            .then(function(r) { return r.json(); })
            .then(function(specs) {
                if (!specs.length) {
                    specsBody.innerHTML = '<div class="specs-empty"><i class="fas fa-info-circle"></i>No specifications defined for this category.</div>';
                    return;
                }
                var essential = specs.filter(function(s) { return s.is_essential; });
                var optional  = specs.filter(function(s) { return !s.is_essential; });
                var html = '';
                if (essential.length) {
                    html += '<span class="spec-section-label spec-section-label--essential"><i class="fas fa-star" style="margin-right:5px;font-size:9px;"></i>Item Essentials</span>';
                    html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:0 20px;">';
                    essential.forEach(function(s) { html += buildSpecField(s); });
                    html += '</div>';
                }
                if (optional.length) {
                    html += '<span class="spec-section-label spec-section-label--optional" style="margin-top:12px;"><i class="fas fa-ellipsis-h" style="margin-right:5px;font-size:9px;"></i>Additional Details</span>';
                    html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:0 20px;">';
                    optional.forEach(function(s) { html += buildSpecField(s); });
                    html += '</div>';
                }
                specsBody.innerHTML = html;
                specsBody.querySelectorAll('[name^="specs_range_"]').forEach(function(input) {
                    input.addEventListener('input', function() {
                        var specId = input.name.match(/\[(\d+)\]/)[1];
                        var min = specsBody.querySelector('[name="specs_range_min[' + specId + ']"]')?.value || '';
                        var max = specsBody.querySelector('[name="specs_range_max[' + specId + ']"]')?.value || '';
                        var h = specsBody.querySelector('.spec-range-hidden[data-spec-id="' + specId + '"]');
                        if (h) h.value = min + ' – ' + max;
                    });
                });
            })
            .catch(function() {
                specsBody.innerHTML = '<div class="specs-empty" style="color:#e53935;"><i class="fas fa-exclamation-triangle"></i>Could not load specifications. Please refresh and try again.</div>';
            });
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', function() { loadSpecs(this.value); });
        if (categorySelect.value) loadSpecs(categorySelect.value);
    }

})();
</script>
@endpush
