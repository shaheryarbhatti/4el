@extends('admin.layouts.app')
@php $editing = $spec->exists; @endphp
@section('title', ($editing ? 'Edit' : 'Add') . ' Specification — ' . $category->name)
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }

/* ── banner ── */
.sf-banner {
    background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 40%,#a78bfa 100%);
    border-radius:20px; padding:24px 32px; margin-bottom:26px;
    display:flex; align-items:center; justify-content:space-between;
    box-shadow:0 8px 32px rgba(108,99,255,.28); position:relative; overflow:hidden;
}
.sf-banner::before {
    content:''; position:absolute; top:-50px; right:-50px;
    width:200px; height:200px; border-radius:50%; background:rgba(255,255,255,.07);
}
.sf-banner__left  { position:relative; z-index:1; }
.sf-banner__icon  {
    width:52px; height:52px; background:rgba(255,255,255,.22); border-radius:16px;
    display:flex; align-items:center; justify-content:center; font-size:23px; color:#fff;
    margin-bottom:10px; backdrop-filter:blur(4px);
}
.sf-banner__title { font-size:20px; font-weight:800; color:#fff; margin:0; }
.sf-banner__sub   { font-size:12.5px; color:rgba(255,255,255,.78); margin-top:4px; display:flex; align-items:center; gap:5px; }
.sf-banner__sub a { color:rgba(255,255,255,.85); text-decoration:none; }
.sf-banner__sub a:hover { color:#fff; }
.sf-banner__badge {
    position:relative; z-index:1;
    background:rgba(255,255,255,.2); color:#fff; border-radius:20px;
    padding:8px 18px; font-size:12.5px; font-weight:700;
    backdrop-filter:blur(4px); display:flex; align-items:center; gap:8px;
}

/* ── layout ── */
.sf-grid { display:grid; grid-template-columns:1fr 360px; gap:22px; align-items:start; }

/* ── card ── */
.sf-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; margin-bottom:22px; }
.sf-card:last-child { margin-bottom:0; }
.sf-card__head { padding:14px 22px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:12px; }
.sf-card__head-icon { width:38px; height:38px; border-radius:11px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
.sf-card__head-title { font-size:14px; font-weight:700; color:#1e293b; }
.sf-card__head-sub { font-size:11.5px; color:#94a3b8; margin-top:1px; }
.sf-card__body { padding:22px; }

.sf-card--blue  .sf-card__head { background:linear-gradient(135deg,#ede9fe,#dbeafe); }
.sf-card--blue  .sf-card__head-icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.sf-card--green .sf-card__head { background:linear-gradient(135deg,#d1fae5,#dcfce7); }
.sf-card--green .sf-card__head-icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.sf-card--amber .sf-card__head { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.sf-card--amber .sf-card__head-icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.sf-card--slate .sf-card__head { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); }
.sf-card--slate .sf-card__head-icon { background:linear-gradient(135deg,#475569,#64748b); color:#fff; }

/* ── field ── */
.sf-label { display:flex; align-items:center; gap:6px; font-size:11.5px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px; }
.sf-label i { font-size:12px; color:#6c63ff; }
.sf-label .req { color:#f43f5e; font-size:14px; }
.sf-input {
    width:100%; padding:11px 14px; border:1.5px solid #e2e8f0; border-radius:11px;
    font-size:14px; color:#1e293b; background:#f8fafc; outline:none; font-family:inherit;
    transition:border-color .18s, box-shadow .18s; box-sizing:border-box;
}
.sf-input:focus { border-color:#6c63ff; background:#fff; box-shadow:0 0 0 3px rgba(108,99,255,.12); }
.sf-input::placeholder { color:#c4cdd6; }
textarea.sf-input { resize:vertical; min-height:120px; }

/* ── field type selector ── */
.type-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(130px,1fr)); gap:10px; }
.type-opt {
    border:2px solid #e2e8f0; border-radius:13px; padding:12px 14px;
    cursor:pointer; transition:all .18s; text-align:center; background:#f8fafc;
    position:relative;
}
.type-opt:hover { border-color:#a78bfa; background:#f5f3ff; }
.type-opt input { position:absolute; opacity:0; width:0; height:0; }
.type-opt input:checked + .type-opt-inner { color:#fff; }
.type-opt:has(input:checked) {
    border-color:#6c63ff; background:linear-gradient(135deg,#6c63ff,#a78bfa);
    box-shadow:0 4px 16px rgba(108,99,255,.4);
}
.type-opt-inner { pointer-events:none; }
.type-opt-icon { font-size:22px; margin-bottom:6px; display:block; }
.type-opt-label { font-size:12px; font-weight:700; display:block; }
.type-opt-hint { font-size:10.5px; opacity:.72; display:block; margin-top:2px; }

/* selected state — colors */
.type-opt:has(input:checked) .type-opt-icon,
.type-opt:has(input:checked) .type-opt-label,
.type-opt:has(input:checked) .type-opt-hint { color:#fff; }

/* ── preview box ── */
.field-preview {
    border:1.5px dashed #c7d2fe; border-radius:14px; padding:20px 18px;
    background:#f5f3ff; margin-top:16px; transition:all .3s;
}
.field-preview__label { font-size:11px; font-weight:700; color:#a78bfa; text-transform:uppercase; letter-spacing:.07em; margin-bottom:10px; display:flex; align-items:center; gap:6px; }
.preview-input {
    width:100%; padding:10px 13px; border:1.5px solid #c7d2fe; border-radius:10px;
    font-size:13.5px; color:#4c1d95; background:#fff; outline:none;
    font-family:inherit; pointer-events:none;
}
textarea.preview-input { resize:none; height:80px; }
.preview-pills { display:flex; gap:8px; flex-wrap:wrap; }
.preview-pill {
    padding:7px 16px; border-radius:20px; font-size:13px; font-weight:600; cursor:pointer;
    border:2px solid #c7d2fe; background:#fff; color:#6d28d9; transition:all .15s;
}
.preview-pill:hover { background:#ede9fe; }
.preview-pill.active { background:linear-gradient(135deg,#6c63ff,#a78bfa); border-color:transparent; color:#fff; }
.preview-select-mock {
    width:100%; padding:10px 14px; border:1.5px solid #c7d2fe; border-radius:10px;
    font-size:13.5px; background:#fff; color:#4c1d95; pointer-events:none;
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23a78bfa' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 12px center;
}
.preview-checkboxes { display:flex; flex-direction:column; gap:8px; }
.preview-cb { display:flex; align-items:center; gap:8px; font-size:13px; color:#4c1d95; }
.preview-cb .cb-box { width:18px; height:18px; border:2px solid #a78bfa; border-radius:5px; flex-shrink:0; background:#fff; }
.preview-range-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.preview-number-wrap { display:flex; align-items:center; gap:8px; }
.preview-unit { font-size:13px; color:#6d28d9; font-weight:700; }

/* ── options textarea ── */
.options-hint { font-size:11.5px; color:#64748b; margin-top:6px; display:flex; align-items:flex-start; gap:5px; line-height:1.6; }
.options-hint i { color:#6c63ff; margin-top:2px; flex-shrink:0; }

/* ── toggle row ── */
.toggle-row { display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid #f1f5f9; }
.toggle-row:first-child { padding-top:0; }
.toggle-row:last-child { border-bottom:none; padding-bottom:0; }
.toggle-info strong { display:block; font-size:13.5px; font-weight:700; color:#1e293b; margin-bottom:2px; }
.toggle-info span { font-size:12px; color:#94a3b8; }
.sf-switch { position:relative; display:inline-block; width:50px; height:27px; flex-shrink:0; }
.sf-switch input { opacity:0; width:0; height:0; }
.sf-slider { position:absolute; inset:0; border-radius:50px; background:#e2e8f0; transition:background .25s; cursor:pointer; }
.sf-slider::before { content:''; position:absolute; width:21px; height:21px; border-radius:50%; left:3px; top:3px; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.2); transition:transform .25s; }
.sf-switch input:checked + .sf-slider::before { transform:translateX(23px); }
.sf-switch--green input:checked + .sf-slider { background:#10b981; }
.sf-switch--amber input:checked + .sf-slider { background:#f59e0b; }
.sf-switch--purple input:checked + .sf-slider { background:#6c63ff; }

/* ── action bar ── */
.sf-action-bar {
    background:#fff; border-radius:18px; padding:18px 26px;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    display:flex; align-items:center; justify-content:space-between; margin-top:22px;
}
.sf-action-bar__right { display:flex; align-items:center; gap:12px; }
.btn-sf-save {
    display:inline-flex; align-items:center; gap:10px; padding:12px 28px;
    border:none; border-radius:14px; font-size:14.5px; font-weight:700; color:#fff; cursor:pointer;
    background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 45%,#a78bfa 100%);
    box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .22s, box-shadow .22s;
    position:relative; overflow:hidden;
}
.btn-sf-save::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent); transform:translateX(-100%); transition:transform .55s; }
.btn-sf-save:hover::after { transform:translateX(100%); }
.btn-sf-save:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(108,99,255,.55); }
.si { width:28px; height:28px; background:rgba(255,255,255,.25); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; position:relative; z-index:1; flex-shrink:0; }
.sl { position:relative; z-index:1; }
.btn-sf-cancel { display:inline-flex; align-items:center; gap:8px; padding:12px 20px; border-radius:14px; font-size:14px; font-weight:600; color:#475569; text-decoration:none; background:#f1f5f9; border:1.5px solid #e2e8f0; transition:all .2s; }
.btn-sf-cancel:hover { background:#e2e8f0; color:#1e293b; text-decoration:none; }

/* errors */
.sf-errors { background:#fff1f2; border:1.5px solid #fecdd3; border-radius:14px; padding:16px 20px; margin-bottom:22px; }
.sf-errors__title { font-size:13px; font-weight:700; color:#be123c; margin-bottom:8px; display:flex; align-items:center; gap:8px; }
.sf-errors ul { margin:0; padding-left:18px; }
.sf-errors li { font-size:13px; color:#be123c; margin-bottom:3px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    @if($errors->any())
    <div class="sf-errors">
        <div class="sf-errors__title"><i class="bi bi-exclamation-circle-fill"></i> Please fix the errors below:</div>
        <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Banner --}}
    <div class="sf-banner">
        <div class="sf-banner__left">
            <div class="sf-banner__icon"><i class="bi bi-{{ $editing ? 'pencil-square' : 'plus-circle-fill' }}"></i></div>
            <h4 class="sf-banner__title">{{ $editing ? 'Edit Specification' : 'Add Specification' }}</h4>
            <div class="sf-banner__sub">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <a href="{{ route('admin.categories.index') }}">Categories</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <a href="{{ route('admin.category-specifications.index', $category) }}">{{ $category->name }}</a>
                <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                <span>{{ $editing ? 'Edit' : 'Create' }}</span>
            </div>
        </div>
        <div class="sf-banner__badge">
            @if($editing)
                <i class="bi bi-pencil"></i> Editing: {{ mb_substr($spec->name, 0, 30) }}{{ mb_strlen($spec->name) > 30 ? '…' : '' }}
            @else
                <i class="bi bi-stars"></i> New Specification
            @endif
        </div>
    </div>

    <form action="{{ $editing
            ? route('admin.category-specifications.update', [$category, $spec])
            : route('admin.category-specifications.store', $category) }}"
          method="POST" id="specForm">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="sf-grid">

            {{-- ══ LEFT ══ --}}
            <div>

                {{-- Names --}}
                <div class="sf-card sf-card--blue">
                    <div class="sf-card__head">
                        <div class="sf-card__head-icon"><i class="bi bi-fonts"></i></div>
                        <div>
                            <div class="sf-card__head-title">Field Identity</div>
                            <div class="sf-card__head-sub">Internal name and label shown to sellers</div>
                        </div>
                    </div>
                    <div class="sf-card__body">
                        {{-- Display Label FIRST — drives the auto-generated internal name --}}
                        <div style="margin-bottom:18px;">
                            <label class="sf-label"><i class="bi bi-card-text"></i> Display Label <span class="req">*</span></label>
                            <input type="text" name="field_label" id="specLabel" class="sf-input"
                                   value="{{ old('field_label', $spec->field_label) }}"
                                   placeholder="e.g. Screen Size, Brand Name…"
                                   autocomplete="off" required>
                            <div style="font-size:11.5px;color:#94a3b8;margin-top:5px;">What sellers see on the listing form.</div>
                        </div>

                        {{-- Internal Name — auto-generated, readonly unless unlocked --}}
                        <div style="margin-bottom:18px;">
                            <label class="sf-label"><i class="bi bi-tag-fill"></i> Internal Name <span class="req">*</span></label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="text" name="name" id="specName" class="sf-input"
                                       value="{{ old('name', $spec->name) }}"
                                       placeholder="auto_generated_from_label"
                                       style="font-family:monospace;flex:1;background:#f8fafc;" required
                                       @if(!$editing) readonly @endif>
                                <button type="button" id="unlockNameBtn" title="Edit manually"
                                        style="flex-shrink:0;padding:0 14px;height:42px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;cursor:pointer;color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;transition:all .2s;">
                                    <i class="bi bi-pencil" id="unlockIcon"></i>
                                    <span id="unlockText">Edit</span>
                                </button>
                            </div>
                            <div style="font-size:11.5px;color:#94a3b8;margin-top:5px;" id="nameHint">
                                @if($editing)
                                    <i class="bi bi-info-circle me-1"></i>Auto-generated from Display Label. Click <strong>Edit</strong> to change manually.
                                @else
                                    <i class="bi bi-magic me-1"></i>Auto-generated from Display Label. Stored in the database — must be unique per category.
                                @endif
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                            <div>
                                <label class="sf-label"><i class="bi bi-chat-left-text"></i> Placeholder</label>
                                <input type="text" name="placeholder" class="sf-input"
                                       value="{{ old('placeholder', $spec->placeholder) }}"
                                       placeholder="e.g. Enter screen size…">
                            </div>
                            <div>
                                <label class="sf-label"><i class="bi bi-rulers"></i> Unit</label>
                                <input type="text" name="unit" class="sf-input"
                                       value="{{ old('unit', $spec->unit) }}"
                                       placeholder="e.g. inches, kg, GB…">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Field Type --}}
                <div class="sf-card sf-card--amber">
                    <div class="sf-card__head">
                        <div class="sf-card__head-icon"><i class="bi bi-ui-radios-grid"></i></div>
                        <div>
                            <div class="sf-card__head-title">Field Type</div>
                            <div class="sf-card__head-sub">Determines what input the seller sees</div>
                        </div>
                    </div>
                    <div class="sf-card__body">
                        <div class="type-grid">
                            @php
                            $types = [
                                'text'        => ['icon'=>'bi-input-cursor-text',   'label'=>'Text',        'hint'=>'Single line'],
                                'textarea'    => ['icon'=>'bi-textarea',             'label'=>'Textarea',    'hint'=>'Multi line'],
                                'select'      => ['icon'=>'bi-chevron-down',         'label'=>'Select',      'hint'=>'One choice'],
                                'multiselect' => ['icon'=>'bi-check2-square',        'label'=>'Multiselect', 'hint'=>'Many choices'],
                                'toggle'      => ['icon'=>'bi-toggle-on',            'label'=>'Toggle',      'hint'=>'Yes / No'],
                                'range'       => ['icon'=>'bi-arrows-expand',        'label'=>'Range',       'hint'=>'Min & Max'],
                                'number'      => ['icon'=>'bi-123',                  'label'=>'Number',      'hint'=>'Numeric'],
                            ];
                            $currentType = old('field_type', $spec->field_type ?? 'text');
                            @endphp
                            @foreach($types as $val => $t)
                            <label class="type-opt">
                                <input type="radio" name="field_type" value="{{ $val }}"
                                       {{ $currentType === $val ? 'checked' : '' }}
                                       onchange="updatePreview()">
                                <div class="type-opt-inner">
                                    <i class="bi {{ $t['icon'] }} type-opt-icon"></i>
                                    <span class="type-opt-label">{{ $t['label'] }}</span>
                                    <span class="type-opt-hint">{{ $t['hint'] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        {{-- Live preview --}}
                        <div class="field-preview" id="fieldPreview">
                            <div class="field-preview__label"><i class="bi bi-eye"></i> Live Preview</div>
                            <div id="previewContent"></div>
                        </div>
                    </div>
                </div>

                {{-- Options --}}
                <div class="sf-card sf-card--green" id="optionsCard" style="display:none;">
                    <div class="sf-card__head">
                        <div class="sf-card__head-icon"><i class="bi bi-list-ul"></i></div>
                        <div>
                            <div class="sf-card__head-title">Options</div>
                            <div class="sf-card__head-sub">One option per line (for Select / Multiselect)</div>
                        </div>
                    </div>
                    <div class="sf-card__body">
                        <label class="sf-label"><i class="bi bi-list-check"></i> Option Values</label>
                        <textarea name="options" class="sf-input" id="optionsTextarea" rows="8"
                                  placeholder="Red&#10;Blue&#10;Green&#10;Black&#10;White">{{ old('options', $spec->options ? implode("\n", $spec->option_list) : '') }}</textarea>
                        <div class="options-hint">
                            <i class="bi bi-info-circle-fill"></i>
                            Enter one option per line. These will appear as choices in the dropdown or checkbox list on the listing form.
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div>

                {{-- Settings --}}
                <div class="sf-card sf-card--green">
                    <div class="sf-card__head">
                        <div class="sf-card__head-icon"><i class="bi bi-toggles"></i></div>
                        <div>
                            <div class="sf-card__head-title">Settings</div>
                            <div class="sf-card__head-sub">Visibility and validation rules</div>
                        </div>
                    </div>
                    <div class="sf-card__body" style="padding-top:16px;">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-eye-fill" style="color:#10b981;font-size:12px;margin-right:3px;"></i> Active</strong>
                                <span>Shown on the listing form</span>
                            </div>
                            <label class="sf-switch sf-switch--green">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $spec->is_active ?? true))>
                                <span class="sf-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-asterisk" style="color:#f43f5e;font-size:12px;margin-right:3px;"></i> Required</strong>
                                <span>Seller must fill this field</span>
                            </div>
                            <label class="sf-switch sf-switch--amber">
                                <input type="checkbox" name="is_required" value="1"
                                       @checked(old('is_required', $spec->is_required ?? false))>
                                <span class="sf-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong><i class="bi bi-star-fill" style="color:#6c63ff;font-size:12px;margin-right:3px;"></i> Essential</strong>
                                <span>Shown in "Item Essentials" section first</span>
                            </div>
                            <label class="sf-switch sf-switch--purple">
                                <input type="checkbox" name="is_essential" value="1"
                                       @checked(old('is_essential', $spec->is_essential ?? true))>
                                <span class="sf-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="sf-card sf-card--slate">
                    <div class="sf-card__head">
                        <div class="sf-card__head-icon"><i class="bi bi-sort-numeric-up-alt"></i></div>
                        <div>
                            <div class="sf-card__head-title">Sort Order</div>
                            <div class="sf-card__head-sub">Lower numbers appear first</div>
                        </div>
                    </div>
                    <div class="sf-card__body">
                        <input type="number" name="sort_order" class="sf-input"
                               value="{{ old('sort_order', $spec->sort_order ?? 0) }}" min="0" max="9999"
                               style="text-align:center;font-size:24px;font-weight:700;padding:12px;">
                        <div style="font-size:12px;color:#94a3b8;margin-top:8px;display:flex;align-items:center;gap:5px;">
                            <i class="bi bi-info-circle" style="color:#6c63ff;"></i>
                            You can also drag rows on the list page to reorder.
                        </div>
                    </div>
                </div>

                {{-- Help --}}
                <div class="sf-card sf-card--blue">
                    <div class="sf-card__head">
                        <div class="sf-card__head-icon"><i class="bi bi-question-circle-fill"></i></div>
                        <div>
                            <div class="sf-card__head-title">How It Works</div>
                            <div class="sf-card__head-sub">How specifications map to the listing form</div>
                        </div>
                    </div>
                    <div class="sf-card__body">
                        <div style="font-size:13px;color:#475569;line-height:1.8;">
                            <p style="margin:0 0 10px;">When a seller picks <strong>{{ $category->name }}</strong> as their category, your specifications appear in the <em>Item Specifics</em> section of the listing form.</p>
                            <ul style="margin:0;padding-left:18px;">
                                <li><strong>Essential</strong> specs appear first, in a highlighted section.</li>
                                <li><strong>Optional</strong> specs collapse under "More details".</li>
                                <li><strong>Required</strong> specs must be filled before listing.</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Action bar --}}
        <div class="sf-action-bar">
            <div style="font-size:13px;color:#94a3b8;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-info-circle-fill" style="color:#f59e0b;"></i>
                {{ $editing ? 'Changes apply immediately.' : 'All required fields must be filled.' }}
            </div>
            <div class="sf-action-bar__right">
                <a href="{{ route('admin.category-specifications.index', $category) }}" class="btn-sf-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn-sf-save">
                    <span class="si"><i class="bi {{ $editing ? 'bi-check-lg' : 'bi-plus-lg' }}"></i></span>
                    <span class="sl">{{ $editing ? 'Update Specification' : 'Create Specification' }}</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
/* ── Auto-generate internal name from Display Label ── */
(function () {
    var labelInput  = document.getElementById('specLabel');
    var nameInput   = document.getElementById('specName');
    var unlockBtn   = document.getElementById('unlockNameBtn');
    var unlockIcon  = document.getElementById('unlockIcon');
    var unlockText  = document.getElementById('unlockText');
    var isEditing   = {{ $editing ? 'true' : 'false' }};
    var manuallyEdited = isEditing; // on edit page treat existing value as manual

    function toSlug(str) {
        return str
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s_]/g, '')   // strip special chars
            .replace(/\s+/g, '_')            // spaces → underscore
            .replace(/_+/g, '_')             // collapse multiple underscores
            .replace(/^_|_$/g, '');          // trim leading/trailing
    }

    // Auto-fill name when label is typed (only if not manually edited)
    if (labelInput && nameInput) {
        labelInput.addEventListener('input', function () {
            if (!manuallyEdited) {
                nameInput.value = toSlug(this.value);
            }
        });
    }

    // Unlock / lock button
    if (unlockBtn && nameInput) {
        function setLocked(locked) {
            nameInput.readOnly = locked;
            nameInput.style.background = locked ? '#f8fafc' : '#fff';
            unlockIcon.className = locked ? 'bi bi-pencil' : 'bi bi-lock';
            unlockText.textContent = locked ? 'Edit' : 'Lock';
            unlockBtn.style.color = locked ? '#64748b' : '#1565c0';
            unlockBtn.style.borderColor = locked ? '#e2e8f0' : '#1565c0';
            if (!locked) nameInput.focus();
        }

        // Start locked on create, keep editable on edit
        setLocked(!isEditing ? true : false);

        unlockBtn.addEventListener('click', function () {
            var locked = !nameInput.readOnly;
            manuallyEdited = !locked; // if we're locking, re-enable auto-sync
            setLocked(locked);
            if (!locked) {
                // If they unlock, stop auto-generating
                manuallyEdited = true;
            } else {
                // If they re-lock, re-sync from label
                manuallyEdited = false;
                if (labelInput) nameInput.value = toSlug(labelInput.value);
            }
        });

        // If user types directly in the name field while unlocked → mark manual
        nameInput.addEventListener('input', function () {
            manuallyEdited = true;
            // sanitize as they type
            var pos = this.selectionStart;
            this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '_');
            this.setSelectionRange(pos, pos);
        });
    }
})();

function getType() {
    var r = document.querySelector('input[name="field_type"]:checked');
    return r ? r.value : 'text';
}

function getOptions() {
    var ta = document.getElementById('optionsTextarea');
    if (!ta) return [];
    return ta.value.split('\n').map(s => s.trim()).filter(s => s.length > 0);
}

function updatePreview() {
    var type    = getType();
    var opts    = getOptions();
    var ph      = document.querySelector('input[name="placeholder"]')?.value || 'Enter value…';
    var unit    = document.querySelector('input[name="unit"]')?.value || '';
    var content = '';

    var optionsCard = document.getElementById('optionsCard');

    if (type === 'text') {
        content = '<input class="preview-input" placeholder="' + (ph || 'e.g. Samsung') + '" readonly>';
        optionsCard.style.display = 'none';
    } else if (type === 'textarea') {
        content = '<textarea class="preview-input" placeholder="' + (ph || 'Describe the item…') + '" readonly></textarea>';
        optionsCard.style.display = 'none';
    } else if (type === 'select') {
        var o = opts.length ? opts : ['Option A', 'Option B', 'Option C'];
        content = '<select class="preview-select-mock" disabled><option>-- Select one --</option>'
                + o.map(x => '<option>' + x + '</option>').join('') + '</select>';
        optionsCard.style.display = '';
    } else if (type === 'multiselect') {
        var o = opts.length ? opts : ['Option A', 'Option B', 'Option C'];
        content = '<div class="preview-checkboxes">'
                + o.slice(0, 5).map(x => '<div class="preview-cb"><div class="cb-box"></div>' + x + '</div>').join('')
                + (o.length > 5 ? '<div style="font-size:12px;color:#94a3b8;margin-top:4px;">+ ' + (o.length - 5) + ' more…</div>' : '')
                + '</div>';
        optionsCard.style.display = '';
    } else if (type === 'toggle') {
        content = '<div class="preview-pills"><span class="preview-pill active">Yes</span><span class="preview-pill">No</span></div>';
        optionsCard.style.display = 'none';
    } else if (type === 'range') {
        content = '<div class="preview-range-row">'
                + '<input class="preview-input" placeholder="Min' + (unit ? ' (' + unit + ')' : '') + '" readonly>'
                + '<input class="preview-input" placeholder="Max' + (unit ? ' (' + unit + ')' : '') + '" readonly>'
                + '</div>';
        optionsCard.style.display = 'none';
    } else if (type === 'number') {
        content = '<div class="preview-number-wrap">'
                + '<input class="preview-input" placeholder="' + (ph || '0') + '" type="number" style="max-width:180px;" readonly>'
                + (unit ? '<span class="preview-unit">' + unit + '</span>' : '')
                + '</div>';
        optionsCard.style.display = 'none';
    }

    document.getElementById('previewContent').innerHTML = content;
}

// Also update preview when options or unit change
document.getElementById('optionsTextarea')?.addEventListener('input', updatePreview);
document.querySelector('input[name="unit"]')?.addEventListener('input', updatePreview);
document.querySelector('input[name="placeholder"]')?.addEventListener('input', updatePreview);

// Run on load
updatePreview();
</script>
@endpush
