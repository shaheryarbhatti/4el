{{-- Vendor shop settings --}}
@extends('vendor.layouts.app')
@section('title', 'Shop Settings')

@section('vendor_content')
<form action="{{ route('vendor.shop.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="card mb-3">
        <div class="card-header"><strong>Store Profile</strong></div>
        <div class="card-body">
            <div class="form-group">
                <label>Store Name *</label>
                <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $profile->store_name) }}" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Logo</label>
                    @if ($profile->logo)<div class="mb-2"><img src="{{ asset('storage/'.$profile->logo) }}" height="40"></div>@endif
                    <input type="file" name="logo" class="form-control-file" accept="image/*">
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" rows="2" class="form-control">{{ old('address', $profile->address) }}</textarea>
            </div>
            <div class="form-group">
                <label>About Your Store</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $profile->description) }}</textarea>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><strong>Selling Defaults</strong> <span class="text-muted small">(pre-fill new products)</span></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Default Shipping Cost</label>
                    <input type="number" step="0.01" min="0" name="default_shipping_cost" class="form-control" value="{{ old('default_shipping_cost', $profile->default_shipping_cost) }}">
                </div>
                <div class="form-group col-md-4 d-flex align-items-end">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" name="default_free_shipping" id="dfs" value="1" @checked(old('default_free_shipping', $profile->default_free_shipping))>
                        <label class="custom-control-label" for="dfs">Free Shipping by default</label>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Default Tax Class</label>
                    <select name="default_tax_class_id" class="form-control">
                        <option value="">— None —</option>
                        @foreach ($taxClasses as $tax)
                            <option value="{{ $tax->id }}" @selected(old('default_tax_class_id', $profile->default_tax_class_id) == $tax->id)>{{ $tax->name }} ({{ rtrim(rtrim(number_format($tax->rate,2),'0'),'.') }}%)</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-dark">Save Settings</button>
</form>
@endsection
