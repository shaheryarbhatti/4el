@extends('admin.layouts.app')
@section('title', 'Item Specifications — ' . $category->name)
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }

/* ── banner ── */
.sp-banner {
    background: linear-gradient(135deg,#6c63ff 0%,#5a7cff 40%,#a78bfa 100%);
    border-radius:20px; padding:24px 32px; margin-bottom:24px;
    display:flex; align-items:center; justify-content:space-between;
    box-shadow:0 8px 32px rgba(108,99,255,.28); position:relative; overflow:hidden;
}
.sp-banner::before {
    content:''; position:absolute; top:-60px; right:-40px;
    width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.07);
}
.sp-banner::after {
    content:''; position:absolute; bottom:-70px; right:100px;
    width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.04);
}
.sp-banner__left { position:relative; z-index:1; display:flex; align-items:center; gap:18px; }
.sp-banner__icon {
    width:56px; height:56px; background:rgba(255,255,255,.22); border-radius:18px;
    display:flex; align-items:center; justify-content:center; font-size:26px; color:#fff;
    backdrop-filter:blur(4px); flex-shrink:0;
}
.sp-banner__title { font-size:20px; font-weight:800; color:#fff; margin:0; }
.sp-banner__sub { font-size:12.5px; color:rgba(255,255,255,.78); margin-top:4px; display:flex; align-items:center; gap:5px; }
.sp-banner__sub a { color:rgba(255,255,255,.85); text-decoration:none; }
.sp-banner__sub a:hover { color:#fff; }
.sp-banner__right { position:relative; z-index:1; display:flex; align-items:center; gap:10px; }

/* ── buttons ── */
.btn-add-spec {
    display:inline-flex; align-items:center; gap:9px;
    padding:11px 20px; border-radius:13px;
    background:#fff; color:#6c63ff;
    font-size:13.5px; font-weight:700; text-decoration:none;
    box-shadow:0 3px 12px rgba(0,0,0,.14);
    transition:transform .2s, box-shadow .2s;
}
.btn-add-spec:hover { transform:translateY(-2px); box-shadow:0 7px 22px rgba(0,0,0,.2); color:#4f46e5; text-decoration:none; }
.btn-back {
    display:inline-flex; align-items:center; gap:8px;
    padding:11px 16px; border-radius:13px;
    background:rgba(255,255,255,.18); color:#fff;
    font-size:13px; font-weight:600; text-decoration:none;
    backdrop-filter:blur(4px); border:1px solid rgba(255,255,255,.3);
    transition:background .2s;
}
.btn-back:hover { background:rgba(255,255,255,.28); color:#fff; text-decoration:none; }

/* ── empty state ── */
.empty-state {
    text-align:center; padding:80px 40px;
    background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.06);
}
.empty-state__icon {
    width:80px; height:80px; border-radius:24px; margin:0 auto 18px;
    background:linear-gradient(135deg,#ede9fe,#dbeafe);
    display:flex; align-items:center; justify-content:center;
    font-size:36px; color:#6c63ff;
}
.empty-state h5 { font-size:18px; font-weight:700; color:#1e293b; margin-bottom:8px; }
.empty-state p { font-size:13.5px; color:#64748b; max-width:420px; margin:0 auto 22px; line-height:1.7; }

/* ── spec list ── */
.spec-list { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.spec-list-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 24px; border-bottom:1px solid #f1f5f9;
}
.spec-list-title { font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:10px; }
.spec-list-title i { color:#6c63ff; }
.spec-list-hint { font-size:12px; color:#94a3b8; display:flex; align-items:center; gap:5px; }

.spec-item {
    display:flex; align-items:center; gap:0; padding:0;
    border-bottom:1px solid #f1f5f9;
    transition:background .12s;
    cursor:grab;
}
.spec-item:last-child { border-bottom:none; }
.spec-item:hover { background:#fafbff; }
.spec-item:active { cursor:grabbing; }
.spec-item.dragging { opacity:.5; background:#ede9fe; }

.spec-drag { width:46px; display:flex; align-items:center; justify-content:center; color:#c7d2fe; font-size:18px; cursor:grab; flex-shrink:0; padding:18px 0; }
.spec-drag:hover { color:#a78bfa; }

.spec-body { flex:1; padding:16px 6px 16px 0; min-width:0; }
.spec-name { font-size:14px; font-weight:700; color:#1e293b; margin-bottom:3px; }
.spec-label { font-size:12px; color:#64748b; }

.spec-type {
    padding:4px 12px; border-radius:20px; font-size:11.5px; font-weight:700;
    margin:0 16px; flex-shrink:0; white-space:nowrap;
}
.type-text      { background:#dbeafe; color:#1d4ed8; }
.type-textarea  { background:#d1fae5; color:#065f46; }
.type-select    { background:#fef3c7; color:#92400e; }
.type-multiselect { background:#ede9fe; color:#6d28d9; }
.type-toggle    { background:#fce7f3; color:#9d174d; }
.type-range     { background:#ffedd5; color:#c2410c; }
.type-number    { background:#e0f2fe; color:#075985; }

.spec-flags { display:flex; align-items:center; gap:6px; margin:0 12px; flex-shrink:0; }
.spec-flag {
    display:inline-flex; align-items:center; gap:3px;
    padding:3px 9px; border-radius:16px; font-size:11px; font-weight:600;
}
.flag-required  { background:#fee2e2; color:#991b1b; }
.flag-essential { background:#d1fae5; color:#065f46; }
.flag-optional  { background:#f1f5f9; color:#64748b; }
.flag-inactive  { background:#f1f5f9; color:#94a3b8; }
.flag-active    { background:#d1fae5; color:#065f46; }

.spec-actions { display:flex; align-items:center; gap:6px; padding:0 18px; flex-shrink:0; }
.btn-sp { width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; cursor:pointer; border:none; text-decoration:none; transition:transform .12s, opacity .12s; }
.btn-sp:hover { transform:scale(1.12); opacity:.85; }
.btn-sp-edit { background:#dbeafe; color:#1d4ed8; }
.btn-sp-del  { background:#fee2e2; color:#b91c1c; }

/* ── alert ── */
.sp-alert { padding:14px 18px; border-radius:13px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:13.5px; font-weight:600; }
.sp-alert-success { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }

/* field type tip card */
.tip-card {
    background:#fff; border-radius:18px; padding:20px 24px;
    box-shadow:0 4px 24px rgba(0,0,0,.06); margin-bottom:24px;
}
.tip-card-title { font-size:13px; font-weight:700; color:#475569; margin-bottom:14px; text-transform:uppercase; letter-spacing:.06em; display:flex; align-items:center; gap:7px; }
.tip-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:10px; }
.tip-item { border-radius:12px; padding:10px 13px; display:flex; align-items:center; gap:9px; }
.tip-item i { font-size:18px; flex-shrink:0; }
.tip-item strong { display:block; font-size:12.5px; font-weight:700; margin-bottom:1px; }
.tip-item span { font-size:11px; color:#64748b; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    @if(session('success'))
    <div class="sp-alert sp-alert-success">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Banner --}}
    <div class="sp-banner">
        <div class="sp-banner__left">
            <div class="sp-banner__icon"><i class="bi bi-sliders"></i></div>
            <div>
                <h4 class="sp-banner__title">Item Specifications</h4>
                <div class="sp-banner__sub">
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                    <a href="{{ route('admin.categories.index') }}">Categories</a>
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                    <span style="color:#fff;font-weight:600;">{{ $category->name }}</span>
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                    <span>Specifications</span>
                </div>
            </div>
        </div>
        <div class="sp-banner__right">
            <a href="{{ route('admin.categories.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back to Categories
            </a>
            <a href="{{ route('admin.category-specifications.create', $category) }}" class="btn-add-spec">
                <i class="bi bi-plus-lg"></i> Add Specification
            </a>
        </div>
    </div>

    {{-- Field type guide --}}
    <div class="tip-card">
        <div class="tip-card-title"><i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i> Field Type Reference</div>
        <div class="tip-grid">
            <div class="tip-item" style="background:#dbeafe20;">
                <i class="bi bi-input-cursor-text" style="color:#1d4ed8;"></i>
                <div><strong style="color:#1d4ed8;">Text</strong><span>Short single-line input</span></div>
            </div>
            <div class="tip-item" style="background:#d1fae520;">
                <i class="bi bi-textarea" style="color:#065f46;"></i>
                <div><strong style="color:#065f46;">Textarea</strong><span>Multi-line description</span></div>
            </div>
            <div class="tip-item" style="background:#fef3c720;">
                <i class="bi bi-chevron-down" style="color:#92400e;"></i>
                <div><strong style="color:#92400e;">Select</strong><span>Single choice dropdown</span></div>
            </div>
            <div class="tip-item" style="background:#ede9fe20;">
                <i class="bi bi-check2-square" style="color:#6d28d9;"></i>
                <div><strong style="color:#6d28d9;">Multiselect</strong><span>Multiple choice checkboxes</span></div>
            </div>
            <div class="tip-item" style="background:#fce7f320;">
                <i class="bi bi-toggle-on" style="color:#9d174d;"></i>
                <div><strong style="color:#9d174d;">Toggle</strong><span>Yes / No pill selector</span></div>
            </div>
            <div class="tip-item" style="background:#ffedd520;">
                <i class="bi bi-arrows-expand" style="color:#c2410c;"></i>
                <div><strong style="color:#c2410c;">Range</strong><span>Min / Max value pair</span></div>
            </div>
            <div class="tip-item" style="background:#e0f2fe20;">
                <i class="bi bi-123" style="color:#075985;"></i>
                <div><strong style="color:#075985;">Number</strong><span>Numeric input with unit</span></div>
            </div>
        </div>
    </div>

    @if($specs->isEmpty())
    <div class="empty-state">
        <div class="empty-state__icon"><i class="bi bi-sliders2"></i></div>
        <h5>No specifications yet</h5>
        <p>
            Item specifications let buyers filter and understand listings in the
            <strong>{{ $category->name }}</strong> category.
            Add your first field — like <em>Brand</em>, <em>Condition</em>, or <em>Screen Size</em>.
        </p>
        <a href="{{ route('admin.category-specifications.create', $category) }}" class="btn-add-spec" style="margin:0 auto;display:inline-flex;">
            <i class="bi bi-plus-lg"></i> Add First Specification
        </a>
    </div>
    @else
    <div class="spec-list" id="specList">
        <div class="spec-list-head">
            <div class="spec-list-title">
                <i class="bi bi-list-check"></i>
                {{ $specs->count() }} Specification{{ $specs->count() !== 1 ? 's' : '' }}
            </div>
            <div class="spec-list-hint">
                <i class="bi bi-grip-vertical"></i>
                Drag rows to reorder
            </div>
        </div>

        <div id="sortableSpecs">
        @foreach($specs as $spec)
        <div class="spec-item" data-id="{{ $spec->id }}">
            <div class="spec-drag"><i class="bi bi-grip-vertical"></i></div>

            <div class="spec-body">
                <div class="spec-name">{{ $spec->name }}</div>
                @if($spec->field_label && $spec->field_label !== $spec->name)
                <div class="spec-label">Label: {{ $spec->field_label }}</div>
                @endif
                @if($spec->options)
                <div class="spec-label" style="font-size:11px;margin-top:2px;color:#94a3b8;">
                    Options: {{ implode(' · ', array_slice($spec->option_list, 0, 5)) }}{{ count($spec->option_list) > 5 ? ' + ' . (count($spec->option_list) - 5) . ' more' : '' }}
                </div>
                @endif
            </div>

            <div class="spec-type type-{{ $spec->field_type }}">
                <i class="bi bi-{{ match($spec->field_type) {
                    'text'        => 'input-cursor-text',
                    'textarea'    => 'textarea',
                    'select'      => 'chevron-down',
                    'multiselect' => 'check2-square',
                    'toggle'      => 'toggle-on',
                    'range'       => 'arrows-expand',
                    'number'      => '123',
                    default       => 'input-cursor-text'
                } }}"></i>
                {{ ucfirst($spec->field_type) }}
            </div>

            <div class="spec-flags">
                @if($spec->is_required)
                    <span class="spec-flag flag-required"><i class="bi bi-asterisk"></i> Required</span>
                @endif
                @if($spec->is_essential)
                    <span class="spec-flag flag-essential"><i class="bi bi-star-fill"></i> Essential</span>
                @else
                    <span class="spec-flag flag-optional">Optional</span>
                @endif
                @if($spec->is_active)
                    <span class="spec-flag flag-active"><i class="bi bi-eye-fill"></i> Active</span>
                @else
                    <span class="spec-flag flag-inactive"><i class="bi bi-eye-slash"></i> Hidden</span>
                @endif
            </div>

            <div class="spec-actions">
                <a href="{{ route('admin.category-specifications.edit', [$category, $spec]) }}" class="btn-sp btn-sp-edit" title="Edit">
                    <i class="bi bi-pencil-fill"></i>
                </a>
                <form action="{{ route('admin.category-specifications.destroy', [$category, $spec]) }}" method="POST"
                      onsubmit="return confirm('Delete «{{ addslashes($spec->name) }}»? This will remove all saved values for this field.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-sp btn-sp-del" title="Delete">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('sortableSpecs');
    if (!el) return;

    Sortable.create(el, {
        animation: 150,
        handle: '.spec-drag',
        ghostClass: 'dragging',
        onEnd: function () {
            var order = Array.from(el.querySelectorAll('.spec-item')).map(function (row) {
                return row.dataset.id;
            });
            fetch('{{ route('admin.category-specifications.reorder', $category) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            });
        }
    });
});
</script>
@endpush
