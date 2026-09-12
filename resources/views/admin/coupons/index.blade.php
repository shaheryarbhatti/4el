@extends('admin.layouts.app')
@section('title', 'Coupons')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }
.page-heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; }
.page-heading h4 { font-size:22px; font-weight:800; color:#1e293b; margin:0; display:flex; align-items:center; gap:10px; }

/* stat cards */
.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; margin-bottom:28px; }
.stat-card { border-radius:20px; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,.08); overflow:hidden; transition:transform .2s,box-shadow .2s; }
.stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.14); }
.stat-card__bar { height:6px; }
.stat-card__top { padding:22px 22px 14px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
.stat-card__icon { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
.stat-card__value { font-size:38px; font-weight:900; line-height:1; color:#1e293b; }
.stat-card__label { font-size:13px; font-weight:600; color:#64748b; margin-top:5px; }
.stat-card__footer { padding:10px 22px 14px; display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; border-top:1px solid #f1f5f9; }
.stat-card--total .stat-card__icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.stat-card--total .stat-card__bar  { background:linear-gradient(90deg,#6c63ff,#a78bfa); }
.stat-card--active .stat-card__icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.stat-card--active .stat-card__bar  { background:linear-gradient(90deg,#10b981,#34d399); }
.stat-card--used .stat-card__icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.stat-card--used .stat-card__bar  { background:linear-gradient(90deg,#f59e0b,#fbbf24); }
.stat-card--exp .stat-card__icon { background:linear-gradient(135deg,#ef4444,#f87171); color:#fff; }
.stat-card--exp .stat-card__bar  { background:linear-gradient(90deg,#ef4444,#f87171); }

/* table card */
.table-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.table-card-head { display:flex; align-items:center; justify-content:space-between; padding:20px 24px; border-bottom:1px solid #f1f5f9; }
.table-card-title { font-size:16px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:10px; }
.table-card-body { padding:16px 24px 24px; }

/* buttons */
.btn-add { display:inline-flex; align-items:center; gap:10px; padding:12px 22px; background:linear-gradient(135deg,#6c63ff,#5a7cff,#a78bfa); color:#fff; border-radius:14px; font-size:14px; font-weight:700; text-decoration:none; box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .25s,box-shadow .25s,color 0s; }
.btn-add:hover { transform:translateY(-3px) scale(1.03); box-shadow:0 10px 28px rgba(108,99,255,.55); color:#fff; text-decoration:none; }

.badge-active   { background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
.badge-inactive { background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
.badge-pct  { background:#ede9fe; color:#6d28d9; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700; }
.badge-fixed{ background:#dbeafe; color:#1d4ed8; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700; }

.act-wrap { display:flex; align-items:center; gap:6px; }
.btn-act { width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; cursor:pointer; border:none; text-decoration:none; transition:transform .12s,opacity .12s; }
.btn-act:hover { transform:scale(1.12); opacity:.88; }
.btn-edit { background:#dbeafe; color:#1d4ed8; }
.btn-del  { background:#fee2e2; color:#b91c1c; }
.btn-toggle-on  { background:#d1fae5; color:#065f46; }
.btn-toggle-off { background:#fef3c7; color:#92400e; }

table { width:100%; border-collapse:collapse; }
thead th { background:#f8fafc; color:#475569; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:13px 16px; border-bottom:2px solid #e2e8f0; }
tbody td { padding:14px 16px; vertical-align:middle; border-bottom:1px solid #f1f5f9; font-size:13.5px; color:#374151; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover td { background:#fafbff; }
.code-badge { font-family:monospace; background:#f1f5f9; padding:4px 10px; border-radius:8px; font-size:13px; font-weight:700; color:#1e293b; letter-spacing:.06em; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="page-heading">
        <div>
            <h4><i class="bi bi-ticket-perforated-fill" style="color:#6c63ff;"></i> Coupons</h4>
            <nav style="margin-top:4px;">
                <ol class="breadcrumb mb-0" style="font-size:12.5px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Coupons</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn-add">
            <i class="bi bi-plus-lg"></i> Add Coupon
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $total   = \App\Models\Coupon::count();
        $active  = \App\Models\Coupon::where('is_active', true)->count();
        $expired = \App\Models\Coupon::where('expires_at', '<', now())->count();
        $totalUses = \App\Models\Coupon::sum('used_count');
    @endphp

    <div class="stat-grid">
        <div class="stat-card stat-card--total">
            <div class="stat-card__bar"></div>
            <div class="stat-card__top">
                <div><div class="stat-card__value">{{ $total }}</div><div class="stat-card__label">Total Coupons</div></div>
                <div class="stat-card__icon"><i class="bi bi-ticket-perforated-fill"></i></div>
            </div>
            <div class="stat-card__footer"><i class="bi bi-info-circle"></i> All coupons in system</div>
        </div>
        <div class="stat-card stat-card--active">
            <div class="stat-card__bar"></div>
            <div class="stat-card__top">
                <div><div class="stat-card__value">{{ $active }}</div><div class="stat-card__label">Active</div></div>
                <div class="stat-card__icon"><i class="bi bi-check-circle-fill"></i></div>
            </div>
            <div class="stat-card__footer"><i class="bi bi-info-circle"></i> Currently usable</div>
        </div>
        <div class="stat-card stat-card--used">
            <div class="stat-card__bar"></div>
            <div class="stat-card__top">
                <div><div class="stat-card__value">{{ $totalUses }}</div><div class="stat-card__label">Total Uses</div></div>
                <div class="stat-card__icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
            <div class="stat-card__footer"><i class="bi bi-info-circle"></i> Across all coupons</div>
        </div>
        <div class="stat-card stat-card--exp">
            <div class="stat-card__bar"></div>
            <div class="stat-card__top">
                <div><div class="stat-card__value">{{ $expired }}</div><div class="stat-card__label">Expired</div></div>
                <div class="stat-card__icon"><i class="bi bi-clock-history"></i></div>
            </div>
            <div class="stat-card__footer"><i class="bi bi-info-circle"></i> Past expiry date</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-head">
            <div class="table-card-title"><i class="bi bi-table"></i> Coupon List</div>
        </div>
        <div class="table-card-body">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Uses</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($coupons as $coupon)
                    <tr id="row-{{ $coupon->id }}">
                        <td><span class="code-badge">{{ $coupon->code }}</span></td>
                        <td>
                            @if($coupon->type === 'percentage')
                                <span class="badge-pct">% Percentage</span>
                            @else
                                <span class="badge-fixed">$ Fixed</span>
                            @endif
                        </td>
                        <td>
                            <strong>
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    ${{ number_format($coupon->value, 2) }}
                                @endif
                            </strong>
                        </td>
                        <td>{{ $coupon->min_order_amount ? '$'.number_format($coupon->min_order_amount,2) : '—' }}</td>
                        <td>
                            {{ $coupon->used_count }}
                            @if($coupon->max_uses)
                                / {{ $coupon->max_uses }}
                            @else
                                <span style="color:#94a3b8;">/ ∞</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->expires_at)
                                <span style="{{ $coupon->expires_at->isPast() ? 'color:#ef4444;' : '' }}">
                                    {{ $coupon->expires_at->format('M d, Y') }}
                                </span>
                            @else
                                <span style="color:#94a3b8;">Never</span>
                            @endif
                        </td>
                        <td>
                            <span id="status-{{ $coupon->id }}" class="{{ $coupon->is_active ? 'badge-active' : 'badge-inactive' }}">
                                <i class="bi {{ $coupon->is_active ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}" style="font-size:8px;"></i>
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="act-wrap">
                                <button class="btn-act {{ $coupon->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}"
                                        title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}"
                                        onclick="toggleCoupon({{ $coupon->id }}, this)">
                                    <i class="bi {{ $coupon->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                </button>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-act btn-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}"
                                      onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-act btn-del" title="Delete">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;padding:50px;color:#94a3b8;">
                        <i class="bi bi-ticket-perforated" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                        No coupons yet. <a href="{{ route('admin.coupons.create') }}">Create one</a>.
                    </td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $coupons->links() }}</div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function toggleCoupon(id, btn) {
    fetch('{{ url('admin/coupons') }}/' + id + '/toggle', {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        var statusEl = document.getElementById('status-' + id);
        if (data.active) {
            statusEl.className = 'badge-active';
            statusEl.innerHTML = '<i class="bi bi-check-circle-fill" style="font-size:8px;"></i> Active';
            btn.className = 'btn-act btn-toggle-on';
            btn.title = 'Deactivate';
            btn.innerHTML = '<i class="bi bi-toggle-on"></i>';
        } else {
            statusEl.className = 'badge-inactive';
            statusEl.innerHTML = '<i class="bi bi-x-circle-fill" style="font-size:8px;"></i> Inactive';
            btn.className = 'btn-act btn-toggle-off';
            btn.title = 'Activate';
            btn.innerHTML = '<i class="bi bi-toggle-off"></i>';
        }
    });
}
</script>
@endpush
