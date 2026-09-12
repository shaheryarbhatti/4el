@extends('admin.layouts.app')
@php $editing = $product->exists; @endphp
@section('title', $editing ? 'Edit Product' : 'Add Product')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
.page-header-breadcrumb { display:none !important; }

/* ── banner ── */
.cf-banner { background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 40%,#a78bfa 100%); border-radius:20px; padding:26px 32px; margin-bottom:26px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 8px 32px rgba(108,99,255,.32); position:relative; overflow:hidden; }
.cf-banner::before { content:''; position:absolute; top:-50px; right:-50px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.07); }
.cf-banner::after  { content:''; position:absolute; bottom:-70px; right:80px; width:170px; height:170px; border-radius:50%; background:rgba(255,255,255,.04); }
.cf-banner__left { position:relative; z-index:1; }
.cf-banner__icon { width:54px; height:54px; background:rgba(255,255,255,.22); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; color:#fff; margin-bottom:10px; }
.cf-banner__title { font-size:22px; font-weight:800; color:#fff; margin:0; }
.cf-banner__sub { font-size:13px; color:rgba(255,255,255,.78); margin-top:5px; display:flex; align-items:center; gap:6px; }
.cf-banner__sub a { color:rgba(255,255,255,.85); text-decoration:none; }
.cf-banner__badge { background:rgba(255,255,255,.2); color:#fff; border-radius:20px; padding:8px 18px; font-size:12.5px; font-weight:700; position:relative; z-index:1; display:flex; align-items:center; gap:8px; }

/* ── grid ── */
.cf-grid { display:grid; grid-template-columns:1fr 400px; gap:22px; align-items:start; }

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
.cf-card--rose  .cf-card__head { background:linear-gradient(135deg,#ffe4e6,#fecdd3); }
.cf-card--rose  .cf-card__head-icon { background:linear-gradient(135deg,#f43f5e,#fb7185); color:#fff; }
.cf-card--slate .cf-card__head { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); }
.cf-card--slate .cf-card__head-icon { background:linear-gradient(135deg,#475569,#64748b); color:#fff; }

/* ── fields ── */
.cf-label { display:flex; align-items:center; gap:6px; font-size:11.5px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px; }
.cf-label i { font-size:12px; color:#6c63ff; }
.cf-label .req { color:#f43f5e; font-size:14px; line-height:1; }
.cf-input { width:100%; padding:11px 14px; border:1.5px solid #e2e8f0; border-radius:11px; font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit; transition:border-color .18s,box-shadow .18s,background .18s; box-sizing:border-box; }
.cf-input:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.cf-input::placeholder { color:#c4cdd6; }
.cf-input.is-invalid { border-color:#f43f5e; }
textarea.cf-input { resize:vertical; min-height:80px; }

/* price input with prefix */
.price-wrap { display:flex; align-items:center; border:1.5px solid #e2e8f0; border-radius:11px; background:#f8fafc; overflow:hidden; transition:border-color .18s,box-shadow .18s; }
.price-wrap:focus-within { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.price-prefix { padding:0 14px; font-size:15px; font-weight:700; color:#6c63ff; background:transparent; white-space:nowrap; flex-shrink:0; }
.price-wrap input { border:none; border-radius:0; background:transparent; padding:11px 14px 11px 0; flex:1; outline:none; font-size:14px; color:#1e293b; min-width:0; font-family:inherit; }

/* hint text */
.cf-hint { font-size:12px; color:#94a3b8; margin-top:5px; display:flex; align-items:center; gap:5px; }
.cf-err  { font-size:12px; color:#f43f5e; margin-top:5px; display:flex; align-items:center; gap:5px; }

/* ── Select2 ── */
.select2-container { width:100% !important; }
.select2-container--default .select2-selection--single { height:44px !important; border:1.5px solid #e2e8f0 !important; border-radius:11px !important; background:#f8fafc !important; display:flex !important; align-items:center !important; transition:all .18s !important; }
.select2-container--default .select2-selection--single .select2-selection__rendered { padding:0 36px 0 14px !important; color:#1e293b !important; font-size:14px !important; line-height:42px !important; }
.select2-container--default .select2-selection--single .select2-selection__placeholder { color:#c4cdd6 !important; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height:42px !important; right:10px !important; }
.select2-container--default.select2-container--open .select2-selection--single,
.select2-container--default.select2-container--focus .select2-selection--single { border-color:#6c63ff !important; background:#fff !important; box-shadow:0 0 0 3px rgba(108,99,255,.12) !important; }
.select2-dropdown { border:1.5px solid #e2e8f0 !important; border-radius:12px !important; box-shadow:0 8px 28px rgba(0,0,0,.12) !important; overflow:hidden; }
.select2-search--dropdown input { border:1.5px solid #e2e8f0 !important; border-radius:8px !important; padding:8px 12px !important; font-size:13px !important; }
.select2-results__option { padding:10px 14px !important; font-size:13.5px !important; }
.select2-container--default .select2-results__option--highlighted[aria-selected] { background:linear-gradient(135deg,#6c63ff,#a78bfa) !important; }

/* ── toggles ── */
.toggle-row { display:flex; align-items:center; justify-content:space-between; padding:13px 0; border-bottom:1px solid #f1f5f9; }
.toggle-row:first-child { padding-top:0; }
.toggle-row:last-child  { border-bottom:none; padding-bottom:0; }
.toggle-info strong { display:block; font-size:13.5px; font-weight:700; color:#1e293b; margin-bottom:1px; }
.toggle-info span   { font-size:12px; color:#94a3b8; }
.cf-switch { position:relative; display:inline-block; width:50px; height:27px; flex-shrink:0; }
.cf-switch input { opacity:0; width:0; height:0; }
.cf-slider { position:absolute; inset:0; border-radius:50px; background:#e2e8f0; transition:background .25s; cursor:pointer; }
.cf-slider::before { content:''; position:absolute; width:21px; height:21px; border-radius:50%; left:3px; top:3px; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.2); transition:transform .25s; }
.cf-switch input:checked + .cf-slider::before { transform:translateX(23px); }
.cf-switch--purple input:checked + .cf-slider { background:#6c63ff; }
.cf-switch--amber  input:checked + .cf-slider { background:#f59e0b; }
.cf-switch--rose   input:checked + .cf-slider { background:#f43f5e; }
.cf-switch--green  input:checked + .cf-slider { background:#10b981; }

/* ── status select custom styling ── */
.status-select-wrap { position:relative; }
.status-select-wrap select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; }

/* ── existing images grid ── */
.img-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(90px,1fr)); gap:10px; margin-bottom:16px; }
.img-card { border-radius:12px; border:2px solid #e2e8f0; overflow:hidden; background:#f8fafc; text-align:center; padding:8px; position:relative; transition:border-color .15s; }
.img-card:hover { border-color:#a78bfa; }
.img-card img { width:100%; height:80px; object-fit:cover; border-radius:8px; display:block; margin-bottom:6px; }
.img-card .img-controls { display:flex; flex-direction:column; gap:4px; }
.img-card label { font-size:11px; color:#64748b; display:flex; align-items:center; justify-content:center; gap:4px; cursor:pointer; }
.img-card label input { margin:0; }
.img-card label.del { color:#f43f5e; }
.img-primary-badge { position:absolute; top:6px; right:6px; background:#6c63ff; color:#fff; border-radius:6px; font-size:9px; font-weight:700; padding:2px 5px; }

/* ── upload zone ── */
.img-upload-zone { border:2px dashed #c7d2fe; border-radius:14px; padding:22px 20px; text-align:center; cursor:pointer; background:#f5f3ff; transition:border-color .2s,background .2s; position:relative; overflow:hidden; }
.img-upload-zone:hover { border-color:#6c63ff; background:#ede9fe; }
.img-upload-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.img-upload-zone i { font-size:30px; color:#a78bfa; margin-bottom:6px; display:block; }
.img-upload-zone .upz-title { font-size:13px; font-weight:700; color:#4f46e5; }
.img-upload-zone .upz-sub   { font-size:11.5px; color:#94a3b8; margin-top:2px; }

/* ── row helpers ── */
.cf-row { display:grid; gap:16px; margin-bottom:16px; }
.cf-row:last-child { margin-bottom:0; }
.cf-row.cols-3 { grid-template-columns:1fr 1fr 1fr; }
.cf-row.cols-2 { grid-template-columns:1fr 1fr; }
.cf-field { margin-bottom:16px; }
.cf-field:last-child { margin-bottom:0; }

/* ── action bar ── */
.cf-action-bar { background:#fff; border-radius:18px; padding:20px 26px; box-shadow:0 4px 24px rgba(0,0,0,.07); display:flex; align-items:center; justify-content:space-between; margin-top:22px; }
.cf-action-bar__left { font-size:13px; color:#94a3b8; display:flex; align-items:center; gap:8px; }
.cf-action-bar__left i { color:#f59e0b; }
.cf-action-bar__right { display:flex; align-items:center; gap:12px; }
.btn-cf-save { display:inline-flex; align-items:center; gap:10px; padding:12px 28px; border:none; border-radius:14px; font-size:14.5px; font-weight:700; color:#fff; cursor:pointer; background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 45%,#a78bfa 100%); box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .22s,box-shadow .22s; position:relative; overflow:hidden; letter-spacing:.02em; }
.btn-cf-save::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent); transform:translateX(-100%); transition:transform .55s ease; }
.btn-cf-save:hover::after { transform:translateX(100%); }
.btn-cf-save:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(108,99,255,.55); }
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

    <div class="cf-banner">
        <div class="cf-banner__left">
            <div class="cf-banner__icon"><i class="bi {{ $editing ? 'bi-pencil-square' : 'bi-plus-circle-fill' }}"></i></div>
            <h4 class="cf-banner__title">{{ $editing ? 'Edit Product' : 'Add New Product' }}</h4>
            <div class="cf-banner__sub">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <a href="{{ route('admin.products.index') }}">Products</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <span>{{ $editing ? 'Edit' : 'Create' }}</span>
            </div>
        </div>
        <div class="cf-banner__badge">
            @if($editing)
                <i class="bi bi-pencil"></i> {{ mb_substr($product->name, 0, 30) }}{{ mb_strlen($product->name) > 30 ? '…' : '' }}
            @else
                <i class="bi bi-stars"></i> New Product
            @endif
        </div>
    </div>

    <form action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}"
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
                            <div class="cf-card__head-sub">Product name, SKU and description</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="cf-row cols-2">
                            <div>
                                <label class="cf-label"><i class="bi bi-box-seam-fill"></i> Product Name <span class="req">*</span></label>
                                <input type="text" name="name" class="cf-input @error('name') is-invalid @enderror"
                                       value="{{ old('name', $product->name) }}" placeholder="Enter product name…" required>
                                @error('name') <div class="cf-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="cf-label"><i class="bi bi-upc-scan"></i> SKU</label>
                                <input type="text" name="sku" class="cf-input"
                                       value="{{ old('sku', $product->sku) }}" placeholder="e.g. PROD-001">
                            </div>
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-text-left"></i> Short Description</label>
                            <textarea name="short_description" class="cf-input" rows="2"
                                      placeholder="Brief summary (max 500 chars)…">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>
                        <div>
                            <label class="cf-label"><i class="bi bi-card-text"></i> Full Description</label>
                            <textarea name="description" class="cf-input" rows="6"
                                      placeholder="Detailed product description…">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pricing & Inventory --}}
                <div class="cf-card cf-card--green">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="cf-card__head-title">Pricing &amp; Inventory</div>
                            <div class="cf-card__head-sub">Prices, stock, tax and shipping</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="cf-row cols-3">
                            <div>
                                <label class="cf-label"><i class="bi bi-tag-fill"></i> Price <span class="req">*</span></label>
                                <div class="price-wrap">
                                    <span class="price-prefix">{{ setting('currency_symbol', '$') }}</span>
                                    <input type="number" step="0.01" min="0" name="price"
                                           value="{{ old('price', $product->price) }}" required placeholder="0.00">
                                </div>
                                @error('price') <div class="cf-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="cf-label"><i class="bi bi-percent"></i> Sale Price</label>
                                <div class="price-wrap">
                                    <span class="price-prefix">{{ setting('currency_symbol', '$') }}</span>
                                    <input type="number" step="0.01" min="0" name="sale_price"
                                           value="{{ old('sale_price', $product->sale_price) }}" placeholder="0.00">
                                </div>
                                <div class="cf-hint"><i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i> Leave blank if not on sale.</div>
                            </div>
                            <div>
                                <label class="cf-label"><i class="bi bi-boxes"></i> Stock Qty <span class="req">*</span></label>
                                <input type="number" min="0" name="stock" class="cf-input"
                                       value="{{ old('stock', $product->stock ?? 0) }}" required>
                                @error('stock') <div class="cf-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="cf-row cols-2" style="margin-top:16px;">
                            <div>
                                <label class="cf-label"><i class="bi bi-receipt-cutoff"></i> Tax Class</label>
                                <select name="tax_class_id" id="sel-tax">
                                    <option value="">— None —</option>
                                    @foreach ($taxClasses as $tax)
                                        <option value="{{ $tax->id }}" @selected(old('tax_class_id', $product->tax_class_id) == $tax->id)>
                                            {{ $tax->name }} ({{ rtrim(rtrim(number_format($tax->rate,2),'0'),'.') }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="cf-label"><i class="bi bi-truck"></i> Shipping Cost</label>
                                <div class="price-wrap">
                                    <span class="price-prefix">{{ setting('currency_symbol', '$') }}</span>
                                    <input type="number" step="0.01" min="0" name="shipping_cost"
                                           value="{{ old('shipping_cost', $product->shipping_cost ?? 0) }}" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div style="margin-top:14px;">
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <strong><i class="bi bi-truck" style="color:#10b981;font-size:12px;margin-right:4px;"></i>Free Shipping</strong>
                                    <span>Override shipping cost with free delivery</span>
                                </div>
                                <label class="cf-switch cf-switch--green">
                                    <input type="checkbox" name="free_shipping" value="1" @checked(old('free_shipping', $product->free_shipping))>
                                    <span class="cf-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="cf-card cf-card--amber">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-images"></i></div>
                        <div>
                            <div class="cf-card__head-title">Product Images</div>
                            <div class="cf-card__head-sub">Upload multiple images; set one as primary</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        @if ($editing && $product->images->count())
                        <div class="img-grid">
                            @foreach ($product->images as $img)
                            <div class="img-card">
                                @if ($img->is_primary)<span class="img-primary-badge">Primary</span>@endif
                                <img src="{{ str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path) }}" alt="">
                                <div class="img-controls">
                                    <label>
                                        <input type="radio" name="primary_image" value="{{ $img->id }}" @checked($img->is_primary)> Primary
                                    </label>
                                    <label class="del">
                                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"> Delete
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <div class="img-upload-zone">
                            <input type="file" name="images[]" accept="image/*" multiple>
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <div class="upz-title">Upload Images</div>
                            <div class="upz-sub">Click or drag &amp; drop · PNG, JPG, WEBP · max 2 MB each · Auto-resized to 800×800</div>
                        </div>
                        <div class="cf-hint" style="margin-top:10px;"><i class="bi bi-info-circle-fill" style="color:#6c63ff;"></i> Images are automatically resized to 800×800 px. First uploaded image becomes primary if none selected.</div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div>

                {{-- Publish --}}
                <div class="cf-card cf-card--rose">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-send-fill"></i></div>
                        <div>
                            <div class="cf-card__head-title">Publish &amp; Visibility</div>
                            <div class="cf-card__head-sub">Status, active state and promotions</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-clipboard-check"></i> Approval Status</label>
                            <div class="status-select-wrap">
                                <select name="status" class="cf-input">
                                    <option value="approved" @selected(old('status', $product->status) === 'approved')>✅ Approved (Live)</option>
                                    <option value="pending"  @selected(old('status', $product->status) === 'pending') >⏳ Pending Review</option>
                                    <option value="rejected" @selected(old('status', $product->status) === 'rejected')>❌ Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div style="padding-top:4px;">
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <strong><i class="bi bi-eye-fill" style="color:#10b981;font-size:12px;margin-right:4px;"></i>Active</strong>
                                    <span>Visible to shoppers on the storefront</span>
                                </div>
                                <label class="cf-switch cf-switch--green">
                                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
                                    <span class="cf-slider"></span>
                                </label>
                            </div>
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <strong><i class="bi bi-star-fill" style="color:#f59e0b;font-size:12px;margin-right:4px;"></i>Featured</strong>
                                    <span>Shown in the home featured widget</span>
                                </div>
                                <label class="cf-switch cf-switch--amber">
                                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                                    <span class="cf-slider"></span>
                                </label>
                            </div>
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <strong><i class="bi bi-fire" style="color:#f43f5e;font-size:12px;margin-right:4px;"></i>Deal of the Day</strong>
                                    <span>Highlighted in the deals section</span>
                                </div>
                                <label class="cf-switch cf-switch--rose">
                                    <input type="checkbox" name="is_deal" value="1" @checked(old('is_deal', $product->is_deal))>
                                    <span class="cf-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div style="margin-top:16px;">
                            <label class="cf-label"><i class="bi bi-calendar-event"></i> Deal Ends At</label>
                            <input type="datetime-local" name="deal_ends_at" class="cf-input"
                                   value="{{ old('deal_ends_at', optional($product->deal_ends_at)->format('Y-m-d\TH:i')) }}">
                            <div class="cf-hint"><i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i> Only relevant when Deal of the Day is active.</div>
                        </div>
                    </div>
                </div>

                {{-- Organization --}}
                <div class="cf-card cf-card--slate">
                    <div class="cf-card__head">
                        <div class="cf-card__head-icon"><i class="bi bi-diagram-3-fill"></i></div>
                        <div>
                            <div class="cf-card__head-title">Organisation</div>
                            <div class="cf-card__head-sub">Category, brand, vendor and type</div>
                        </div>
                    </div>
                    <div class="cf-card__body">
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-person-badge-fill"></i> Store / Vendor</label>
                            <select name="vendor_id" id="sel-vendor">
                                <option value="">Platform (no vendor)</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" @selected(old('vendor_id', $product->vendor_id) == $vendor->id)>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-folder2-open"></i> Category</label>
                            <select name="category_id" id="sel-category">
                                <option value="">— None —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                                        {{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cf-field">
                            <label class="cf-label"><i class="bi bi-award-fill"></i> Brand</label>
                            <select name="brand_id" id="sel-brand">
                                <option value="">— None —</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cf-row cols-2">
                            <div>
                                <label class="cf-label"><i class="bi bi-patch-check-fill"></i> Condition</label>
                                <select name="condition" id="sel-condition" class="cf-input">
                                    <option value="new"         @selected(old('condition', $product->condition) === 'new')>New</option>
                                    <option value="used"        @selected(old('condition', $product->condition) === 'used')>Used</option>
                                    <option value="refurbished" @selected(old('condition', $product->condition) === 'refurbished')>Refurbished</option>
                                </select>
                            </div>
                            <div>
                                <label class="cf-label"><i class="bi bi-hammer"></i> Listing Type</label>
                                <select name="listing_type" id="sel-listing" class="cf-input">
                                    <option value="fixed"   @selected(old('listing_type', $product->listing_type) === 'fixed')>Fixed Price</option>
                                    <option value="auction" @selected(old('listing_type', $product->listing_type) === 'auction')>Auction</option>
                                </select>
                            </div>
                        </div>
                        <div class="cf-hint" style="margin-top:8px;"><i class="bi bi-info-circle-fill" style="color:#6c63ff;"></i> Auction bidding settings are managed in Phase 3.</div>
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
                <a href="{{ route('admin.products.index') }}" class="btn-cf-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn-cf-save">
                    <span class="si"><i class="bi {{ $editing ? 'bi-check-lg' : 'bi-plus-lg' }}"></i></span>
                    <span class="sl">{{ $editing ? 'Update Product' : 'Create Product' }}</span>
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
    // Select2 for all major dropdowns
    $('#sel-vendor').select2({ placeholder: 'Platform (no vendor)', allowClear: true, width: '100%' });
    $('#sel-category').select2({ placeholder: '— None —', allowClear: true, width: '100%' });
    $('#sel-brand').select2({ placeholder: '— None —', allowClear: true, width: '100%' });
    $('#sel-tax').select2({ placeholder: '— None —', allowClear: true, width: '100%' });
});
</script>
@endpush
