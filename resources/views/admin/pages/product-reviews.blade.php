@extends('admin.layouts.app')
@section('title', 'Product Reviews')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }

.rv-page-head {
    margin-top: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}
.rv-page-head h2 {
    font-size: 22px;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
}
.rv-stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 24px;
}
.rv-stat {
    background: #fff;
    border-radius: 16px;
    padding: 20px 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    display: flex;
    align-items: center;
    gap: 16px;
}
.rv-stat__icon {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.rv-stat__val  { font-size: 32px; font-weight: 900; color: #1e293b; line-height: 1; }
.rv-stat__lbl  { font-size: 12px; font-weight: 600; color: #64748b; margin-top: 3px; }

.rv-filter-bar {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
}
.rv-filter-bar a {
    padding: 7px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    border: 2px solid #e2e8f0;
    color: #64748b;
    text-decoration: none;
    transition: all .15s;
}
.rv-filter-bar a.active,
.rv-filter-bar a:hover {
    border-color: #6366f1;
    background: #6366f1;
    color: #fff;
}

.rv-table-wrap {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    overflow: hidden;
}
.rv-table { width: 100%; border-collapse: collapse; }
.rv-table thead th {
    background: #f8fafc;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: .06em;
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    white-space: nowrap;
}
.rv-table tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
    font-size: 13.5px;
    color: #334155;
}
.rv-table tbody tr:last-child td { border-bottom: none; }
.rv-table tbody tr:hover td { background: #f8fafc; }

.rv-product-name {
    font-weight: 700;
    color: #1e293b;
    font-size: 13px;
}
.rv-reviewer { font-size: 13px; color: #334155; font-weight: 600; }
.rv-email    { font-size: 11px; color: #94a3b8; }

.stars { color: #fbbf24; font-size: 13px; }
.stars .empty { color: #e5e7eb; }

.rv-status-approved { background:#dcfce7; color:#166534; border-radius:20px; padding:3px 12px; font-size:11px; font-weight:700; }
.rv-status-pending  { background:#fef9c3; color:#854d0e; border-radius:20px; padding:3px 12px; font-size:11px; font-weight:700; }

.rv-review-text { color:#475569; font-size:13px; max-width:320px; }

.btn-approve {
    background: #22c55e; color: #fff; border: none; border-radius: 8px;
    padding: 5px 14px; font-size: 12px; font-weight: 700; cursor: pointer;
    transition: background .15s;
}
.btn-approve:hover { background: #16a34a; }
.btn-del {
    background: #fee2e2; color: #dc2626; border: none; border-radius: 8px;
    padding: 5px 14px; font-size: 12px; font-weight: 700; cursor: pointer;
    transition: background .15s;
}
.btn-del:hover { background: #fca5a5; }
</style>
@endpush

@section('content')
<div class="rv-page-head">
    <h2><i class="fas fa-star mr-2" style="color:#f59e0b"></i>Product Reviews</h2>
</div>

{{-- Stats --}}
<div class="rv-stat-grid">
    <div class="rv-stat">
        <div class="rv-stat__icon" style="background:#ede9fe;">
            <i class="fas fa-star" style="color:#7c3aed"></i>
        </div>
        <div>
            <div class="rv-stat__val">{{ $pending + $approved }}</div>
            <div class="rv-stat__lbl">Total Reviews</div>
        </div>
    </div>
    <div class="rv-stat">
        <div class="rv-stat__icon" style="background:#fef9c3;">
            <i class="fas fa-clock" style="color:#b45309"></i>
        </div>
        <div>
            <div class="rv-stat__val">{{ $pending }}</div>
            <div class="rv-stat__lbl">Pending Approval</div>
        </div>
    </div>
    <div class="rv-stat">
        <div class="rv-stat__icon" style="background:#dcfce7;">
            <i class="fas fa-check-circle" style="color:#16a34a"></i>
        </div>
        <div>
            <div class="rv-stat__val">{{ $approved }}</div>
            <div class="rv-stat__lbl">Approved & Live</div>
        </div>
    </div>
</div>

@include('admin.layouts.partials.alerts')

{{-- Filter bar --}}
<div class="rv-filter-bar">
    <a href="{{ route('admin.product-reviews.index', ['status' => 'pending']) }}"
       class="{{ $status === 'pending' ? 'active' : '' }}">
        Pending <span class="ml-1 badge badge-warning badge-pill">{{ $pending }}</span>
    </a>
    <a href="{{ route('admin.product-reviews.index', ['status' => 'approved']) }}"
       class="{{ $status === 'approved' ? 'active' : '' }}">
        Approved
    </a>
    <a href="{{ route('admin.product-reviews.index', ['status' => 'all']) }}"
       class="{{ $status === 'all' ? 'active' : '' }}">
        All
    </a>
</div>

<div class="rv-table-wrap">
    @if($reviews->count())
    <table class="rv-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Reviewer</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td style="color:#94a3b8; font-size:12px;">#{{ $review->id }}</td>
                <td>
                    <span class="rv-product-name">{{ Str::limit($review->product->name ?? '—', 40) }}</span>
                </td>
                <td>
                    <div class="rv-reviewer">{{ $review->reviewer_name }}</div>
                    <div class="rv-email">{{ $review->reviewer_email }}</div>
                </td>
                <td>
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star empty"></i>
                            @endif
                        @endfor
                    </span>
                    <div style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ $review->rating }}/5</div>
                </td>
                <td>
                    <div class="rv-review-text">{{ Str::limit($review->review, 120) }}</div>
                </td>
                <td>
                    @if($review->is_approved)
                        <span class="rv-status-approved"><i class="fas fa-check mr-1"></i>Approved</span>
                    @else
                        <span class="rv-status-pending"><i class="fas fa-clock mr-1"></i>Pending</span>
                    @endif
                </td>
                <td style="white-space:nowrap; font-size:12px; color:#94a3b8;">
                    {{ $review->created_at->format('M j, Y') }}
                </td>
                <td>
                    <div class="d-flex gap-2" style="gap:6px;">
                        @if(!$review->is_approved)
                        <form method="POST" action="{{ route('admin.product-reviews.approve', $review) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="btn-approve">
                                <i class="fas fa-check mr-1"></i>Approve
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.product-reviews.destroy', $review) }}"
                              onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($reviews->hasPages())
    <div class="p-3">
        {{ $reviews->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-5">
        <i class="fas fa-star fa-3x mb-3" style="color:#e2e8f0"></i>
        <p class="text-muted">No reviews found for this filter.</p>
    </div>
    @endif
</div>
@endsection
