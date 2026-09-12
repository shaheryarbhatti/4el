@extends('admin.layouts.app')
@section('title', 'Commission & Payout Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Commission</li>
@endsection

@section('content')
@include('admin.settings._nav')

<form action="{{ route('admin.settings.update', 'commission') }}" method="POST">
@csrf

{{-- ── Platform Commission ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#f59e0b,#d97706)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                <i class="bx bx-percentage"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Platform Commission</h6>
                <p>How much the platform keeps from every sale</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Commission Type</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-slider st-input-icon"></i>
                        <select name="commission_type" class="form-select" id="commission_type_sel">
                            <option value="percentage" {{ ($values['commission_type'] ?? 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage (%) of sale amount</option>
                            <option value="fixed"      {{ ($values['commission_type'] ?? '') === 'fixed' ? 'selected' : '' }}>Fixed amount per order</option>
                        </select>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Percentage scales with sale size; fixed is constant</div>
                </div>
            </div>

            <div class="col-md-4" id="col_pct">
                <div class="st-field">
                    <label class="st-label">Commission Rate <span class="opt">(percentage)</span></label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <i class="bx bx-percentage st-input-icon"></i>
                        <input type="number" name="commission_rate" class="form-control" id="comm_rate_inp"
                               value="{{ old('commission_rate', $values['commission_rate'] ?? '10') }}"
                               min="0" max="100" step="0.01" placeholder="10"
                               style="border-radius:10px 0 0 10px">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-left:none;border-radius:0 10px 10px 0;padding:0 14px;display:flex;align-items:center;font-size:14px;font-weight:700;color:#6b7280">%</span>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> e.g. 10 = platform keeps 10% of each sale</div>
                </div>
            </div>

            <div class="col-md-4" id="col_fixed" style="display:none">
                <div class="st-field">
                    <label class="st-label">Fixed Fee <span class="opt">(per order)</span></label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-right:none;border-radius:10px 0 0 10px;padding:0 14px;display:flex;align-items:center;font-size:14px;font-weight:700;color:#6b7280">{{ setting('currency_symbol','$') }}</span>
                        <input type="number" name="commission_fixed" class="form-control" id="comm_fixed_inp"
                               value="{{ old('commission_fixed', $values['commission_fixed'] ?? '0') }}"
                               min="0" step="0.01" placeholder="5.00"
                               style="border-radius:0 10px 10px 0;border-left:none;padding-left:12px">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Flat fee deducted from every order</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Minimum Commission</label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-right:none;border-radius:10px 0 0 10px;padding:0 14px;display:flex;align-items:center;font-size:14px;font-weight:700;color:#6b7280">{{ setting('currency_symbol','$') }}</span>
                        <input type="number" name="commission_min" class="form-control"
                               value="{{ old('commission_min', $values['commission_min'] ?? '0') }}"
                               min="0" step="0.01" placeholder="0.00"
                               style="border-radius:0 10px 10px 0;border-left:none;padding-left:12px">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Commission won't go below this</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Maximum Commission</label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-right:none;border-radius:10px 0 0 10px;padding:0 14px;display:flex;align-items:center;font-size:14px;font-weight:700;color:#6b7280">{{ setting('currency_symbol','$') }}</span>
                        <input type="number" name="commission_max" class="form-control"
                               value="{{ old('commission_max', $values['commission_max'] ?? '0') }}"
                               min="0" step="0.01" placeholder="0.00"
                               style="border-radius:0 10px 10px 0;border-left:none;padding-left:12px">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> 0 = no cap</div>
                </div>
            </div>
        </div>

        {{-- Live Calculator --}}
        <div style="margin-top:20px;background:linear-gradient(135deg,#fffbeb,#fef9ee);border:1px solid #fde68a;border-radius:12px;padding:18px 20px">
            <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#b45309;margin-bottom:12px">
                <i class="bx bx-calculator me-1"></i> Live Commission Preview
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                <span style="font-size:13px;color:#92400e">On a sale of</span>
                <div style="display:flex;align-items:center;gap:0;border:1.5px solid #fcd34d;border-radius:8px;overflow:hidden">
                    <span style="background:#fef3c7;padding:6px 10px;font-size:13px;font-weight:700;color:#92400e">{{ setting('currency_symbol','$') }}</span>
                    <input type="number" id="preview_sale" value="100" min="1" step="1"
                           style="width:80px;border:none;padding:6px 10px;font-size:14px;font-weight:700;color:#111827;background:#fff;outline:none">
                </div>
                <span style="font-size:13px;color:#92400e">— platform keeps</span>
                <strong id="prev_platform" style="font-size:16px;color:#d97706;background:#fef3c7;padding:4px 12px;border-radius:8px">$10.00</strong>
                <span style="font-size:13px;color:#92400e">and vendor receives</span>
                <strong id="prev_vendor" style="font-size:16px;color:#059669;background:#ecfdf5;padding:4px 12px;border-radius:8px">$90.00</strong>
            </div>
        </div>
    </div>
</div>

{{-- ── Category Overrides ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#6366f1,#4f46e5)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
                <i class="bx bx-category"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Category Commission Overrides</h6>
                <p>Set category-specific rates — overrides the global rate above for those categories</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div style="background:#f8f9ff;border:1px solid #e0e7ff;border-radius:10px;padding:12px 14px;margin-bottom:20px;font-size:12.5px;color:#4338ca;display:flex;align-items:center;gap:8px">
            <i class="bx bx-info-circle" style="font-size:15px;color:#6366f1"></i>
            Leave blank to use the global rate above. Set a custom % for specific categories (e.g. Electronics 8%, Clothing 15%).
        </div>
        <div class="row gy-3">
            @foreach (\App\Models\Category::where('parent_id', null)->where('is_active', 1)->orderBy('name')->get() as $cat)
            <div class="col-md-4">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label" style="font-size:11px">
                        <i class="bx bx-folder-open me-1" style="color:#6366f1"></i>{{ $cat->name }}
                    </label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <input type="number" name="commission_cat_{{ $cat->id }}"
                               class="form-control"
                               value="{{ old('commission_cat_'.$cat->id, $values['commission_cat_'.$cat->id] ?? '') }}"
                               min="0" max="100" step="0.01" placeholder="Global default"
                               style="border-radius:10px 0 0 10px">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-left:none;border-radius:0 10px 10px 0;padding:0 12px;display:flex;align-items:center;font-size:13px;font-weight:700;color:#6b7280">%</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Vendor Payout Settings ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#10b981,#059669)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                <i class="bx bx-wallet"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Vendor Payout Settings</h6>
                <p>When and how vendors receive their earnings after platform commission</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Payout Schedule</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-calendar st-input-icon"></i>
                        <select name="payout_schedule" class="form-select">
                            @foreach (['manual'=>'Manual (admin triggers)','weekly'=>'Weekly (every Monday)','biweekly'=>'Bi-weekly','monthly'=>'Monthly (1st of month)'] as $v => $l)
                            <option value="{{ $v }}" {{ ($values['payout_schedule'] ?? 'manual') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> How frequently vendor payouts are processed</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Minimum Payout Amount</label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-right:none;border-radius:10px 0 0 10px;padding:0 12px;display:flex;align-items:center;font-size:14px;font-weight:700;color:#6b7280">{{ setting('currency_symbol','$') }}</span>
                        <input type="number" name="payout_min_amount" class="form-control"
                               value="{{ old('payout_min_amount', $values['payout_min_amount'] ?? '20') }}"
                               min="0" step="0.01" placeholder="20.00"
                               style="border-radius:0 10px 10px 0;border-left:none;padding-left:12px">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Vendor must have at least this balance to request payout</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Payout Hold Period</label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <input type="number" name="payout_hold_days" class="form-control"
                               value="{{ old('payout_hold_days', $values['payout_hold_days'] ?? '7') }}"
                               min="0" step="1" placeholder="7"
                               style="border-radius:10px 0 0 10px">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-left:none;border-radius:0 10px 10px 0;padding:0 12px;display:flex;align-items:center;font-size:12px;font-weight:700;color:#6b7280;white-space:nowrap">days</span>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Days after delivery before earnings become payable</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">Payout Method</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-transfer st-input-icon"></i>
                        <select name="payout_method" class="form-select">
                            @foreach (['bank'=>'Bank Transfer (manual)','paypal'=>'PayPal Mass Pay','stripe'=>'Stripe Connect'] as $v => $l)
                            <option value="{{ $v }}" {{ ($values['payout_method'] ?? 'bank') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">Auto-approve Payout Requests</label>
                    <div class="st-toggle-row" style="padding:0;border:none;margin-top:6px">
                        <div class="st-toggle-info">
                            <p style="margin:0">If disabled, admin reviews every payout request before releasing funds</p>
                        </div>
                        <div class="flex-shrink-0">
                            <input type="hidden" name="payout_auto_approve" value="0">
                            <input class="form-check-input" type="checkbox" name="payout_auto_approve" value="1"
                                   id="payout_auto" style="width:44px;height:22px;cursor:pointer"
                                   {{ ($values['payout_auto_approve'] ?? '0') === '1' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Auction Controls ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#ef4444,#dc2626)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                <i class="bx bx-trip"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Auction Controls</h6>
                <p>Enable or disable auction features and their frontend display</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="st-toggle-row">
            <div class="st-toggle-info">
                <h6>Enable Auctions</h6>
                <p>Allow vendors to create auction-type listings with bidding. When off, only Buy Now listings are allowed.</p>
            </div>
            <div class="flex-shrink-0">
                <input type="hidden" name="auction_enabled" value="0">
                <input class="form-check-input" type="checkbox" name="auction_enabled" value="1"
                       id="auction_enabled" style="width:44px;height:22px;cursor:pointer"
                       {{ ($values['auction_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
            </div>
        </div>
        <div class="st-toggle-row">
            <div class="st-toggle-info">
                <h6>Show "Auction" Badge on Product Cards</h6>
                <p>Display a red "🔨 Auction" badge on product listing cards in category and search pages.</p>
            </div>
            <div class="flex-shrink-0">
                <input type="hidden" name="auction_badge_on_cards" value="0">
                <input class="form-check-input" type="checkbox" name="auction_badge_on_cards" value="1"
                       id="auction_badge_on_cards" style="width:44px;height:22px;cursor:pointer"
                       {{ ($values['auction_badge_on_cards'] ?? '1') === '1' ? 'checked' : '' }}>
            </div>
        </div>
        <div class="st-toggle-row">
            <div class="st-toggle-info">
                <h6>Show Countdown Timer on Cards</h6>
                <p>Display "Ends in Xh Ym" live countdown below the price on auction product cards.</p>
            </div>
            <div class="flex-shrink-0">
                <input type="hidden" name="auction_countdown_on_cards" value="0">
                <input class="form-check-input" type="checkbox" name="auction_countdown_on_cards" value="1"
                       id="auction_countdown_on_cards" style="width:44px;height:22px;cursor:pointer"
                       {{ ($values['auction_countdown_on_cards'] ?? '1') === '1' ? 'checked' : '' }}>
            </div>
        </div>
    </div>
</div>

{{-- ── Listing Fees ── --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#3b82f6,#2563eb)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
                <i class="bx bx-purchase-tag"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Listing Fees</h6>
                <p>Charges for vendors creating product listings (like eBay insertion fees)</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Free Listings per Month</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-package st-input-icon"></i>
                        <input type="number" name="listing_free_count" class="form-control"
                               value="{{ old('listing_free_count', $values['listing_free_count'] ?? '50') }}"
                               min="0" step="1" placeholder="50">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Vendors get this many free listings monthly</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Listing Fee (after free limit)</label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-right:none;border-radius:10px 0 0 10px;padding:0 12px;display:flex;align-items:center;font-size:14px;font-weight:700;color:#6b7280">{{ setting('currency_symbol','$') }}</span>
                        <input type="number" name="listing_fee" class="form-control"
                               value="{{ old('listing_fee', $values['listing_fee'] ?? '0') }}"
                               min="0" step="0.01" placeholder="0.35"
                               style="border-radius:0 10px 10px 0;border-left:none;padding-left:12px">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Per extra listing beyond the monthly free limit</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Auction Extra Fee</label>
                    <div class="st-input-wrap" style="display:flex;gap:0">
                        <input type="number" name="auction_fee_rate" class="form-control"
                               value="{{ old('auction_fee_rate', $values['auction_fee_rate'] ?? '0') }}"
                               min="0" max="100" step="0.01" placeholder="0"
                               style="border-radius:10px 0 0 10px">
                        <span style="background:#f3f4f6;border:1.5px solid #e5e7eb;border-left:none;border-radius:0 10px 10px 0;padding:0 12px;display:flex;align-items:center;font-size:14px;font-weight:700;color:#6b7280">%</span>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Extra % on auction sales added on top of base commission. 0 = same rate as fixed listings</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="st-save-bar">
    <div class="st-save-bar-text">
        <i class="bx bx-shield-check"></i>
        Commission changes apply to all new orders placed after saving.
    </div>
    <button type="submit" class="st-btn-save" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
        <i class="bx bx-save"></i> Save Commission Settings
    </button>
</div>

</form>

@include('admin.settings._nav_end')
@endsection

@push('scripts')
<script>
(function () {
    var typeEl   = document.getElementById('commission_type_sel');
    var rateEl   = document.getElementById('comm_rate_inp');
    var fixedEl  = document.getElementById('comm_fixed_inp');
    var colPct   = document.getElementById('col_pct');
    var colFix   = document.getElementById('col_fixed');
    var saleEl   = document.getElementById('preview_sale');
    var prevP    = document.getElementById('prev_platform');
    var prevV    = document.getElementById('prev_vendor');
    var sym      = '{{ setting('currency_symbol','$') }}';

    function update() {
        var type = typeEl.value;
        colPct.style.display = type === 'percentage' ? '' : 'none';
        colFix.style.display = type === 'fixed'      ? '' : 'none';

        var sale = parseFloat(saleEl.value) || 100;
        var comm = 0;
        if (type === 'percentage') {
            comm = sale * (parseFloat(rateEl.value) || 0) / 100;
        } else {
            comm = parseFloat(fixedEl.value) || 0;
        }
        comm = Math.max(0, Math.min(comm, sale));
        prevP.textContent = sym + comm.toFixed(2);
        prevV.textContent = sym + (sale - comm).toFixed(2);
    }

    typeEl.addEventListener('change', update);
    rateEl.addEventListener('input', update);
    fixedEl.addEventListener('input', update);
    saleEl.addEventListener('input', update);
    update();
})();
</script>
@endpush
