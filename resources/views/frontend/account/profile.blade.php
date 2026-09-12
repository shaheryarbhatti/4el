{{-- Customer profile settings (personal details + address + password) --}}
@extends('frontend.account.layout')
@section('title', 'Profile Settings')

@push('styles')<link rel="stylesheet" href="{{ asset('frontend-assets/css/checkout-autocomplete.css') }}">@endpush

@section('account_content')
@php $countries = ['Pakistan','United States','United Kingdom','United Arab Emirates','Canada','Australia','India','Saudi Arabia','Germany','France','Other']; @endphp
<form action="{{ route('account.profile.update') }}" method="POST">
    @csrf @method('PUT')

    {{-- Personal details --}}
    <div class="ac-card">
        <div class="ac-card__head"><h2 class="ac-card__title">Personal Details</h2></div>
        <div class="ac-card__body">
            <div class="ac-row">
                <div class="ac-field">
                    <label class="ac-label">Full Name</label>
                    <input type="text" name="name" class="ac-input" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="ac-field">
                    <label class="ac-label">Phone</label>
                    <input type="text" name="phone" class="ac-input" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>
            <div class="ac-field">
                <label class="ac-label">Email</label>
                <input type="email" name="email" class="ac-input" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>
    </div>

    {{-- Saved address (with Google Places autocomplete) --}}
    <div class="ac-card">
        <div class="ac-card__head"><h2 class="ac-card__title">Address</h2></div>
        <div class="ac-card__body">
            <div class="ac-field">
                <label class="ac-label">Street Address</label>
                <input type="text" name="address_line" class="ac-input" autocomplete="off"
                       value="{{ old('address_line', $user->address_line) }}"
                       placeholder="Start typing your address…">
                <small class="text-muted" style="font-size:12px">Start typing and pick your address — city, state &amp; postal code fill automatically.</small>
            </div>
            <div class="ac-row">
                <div class="ac-field">
                    <label class="ac-label">City</label>
                    <input type="text" name="city" class="ac-input" value="{{ old('city', $user->city) }}">
                </div>
                <div class="ac-field">
                    <label class="ac-label">State / Province</label>
                    <input type="text" name="state" class="ac-input" value="{{ old('state', $user->state) }}">
                </div>
            </div>
            <div class="ac-row">
                <div class="ac-field">
                    <label class="ac-label">Postal Code</label>
                    <input type="text" name="postal_code" class="ac-input" value="{{ old('postal_code', $user->postal_code) }}">
                </div>
                <div class="ac-field">
                    <label class="ac-label">Country</label>
                    <select name="country" class="ac-input">
                        <option value="">— Select —</option>
                        @foreach ($countries as $c)
                            <option value="{{ $c }}" {{ old('country', $user->country) === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Change password (optional) --}}
    <div class="ac-card">
        <div class="ac-card__head"><h2 class="ac-card__title">Change Password</h2></div>
        <div class="ac-card__body">
            <p class="text-muted" style="font-size:13px;margin-top:-6px">Leave blank to keep your current password.</p>
            <div class="ac-field">
                <label class="ac-label">Current Password</label>
                <input type="password" name="current_password" class="ac-input" autocomplete="current-password">
            </div>
            <div class="ac-row">
                <div class="ac-field">
                    <label class="ac-label">New Password</label>
                    <input type="password" name="password" class="ac-input" autocomplete="new-password">
                </div>
                <div class="ac-field">
                    <label class="ac-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="ac-input" autocomplete="new-password">
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="ac-btn"><i class="fas fa-save"></i> Save Changes</button>
</form>

{{-- ---- Dedicated Change Password form ---- --}}
<form action="{{ route('account.password.change') }}" method="POST" style="margin-top:28px;">
    @csrf @method('PUT')

    <div class="ac-card">
        <div class="ac-card__head"><h2 class="ac-card__title">Change Password</h2></div>
        <div class="ac-card__body">

            @if (session('password_success'))
                <div class="alert alert-success mb-3">{{ session('password_success') }}</div>
            @endif
            @if ($errors->has('current_password') || $errors->has('new_password'))
                <div class="alert alert-danger mb-3">{{ $errors->first('current_password') ?: $errors->first('new_password') }}</div>
            @endif

            <div class="ac-field">
                <label class="ac-label">Current Password</label>
                <input type="password" name="current_password" class="ac-input" autocomplete="current-password">
            </div>
            <div class="ac-row">
                <div class="ac-field">
                    <label class="ac-label">New Password</label>
                    <input type="password" name="new_password" class="ac-input" autocomplete="new-password">
                </div>
                <div class="ac-field">
                    <label class="ac-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="ac-input" autocomplete="new-password">
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="ac-btn"><i class="fas fa-lock"></i> Update Password</button>
</form>
@endsection

@push('scripts')
{{-- Google Places autocomplete (same helper used on checkout) — only if a key is configured --}}
@if (setting('google_maps_api_key'))
    <script src="{{ asset('frontend-assets/js/checkout-autocomplete.js') }}"></script>
    <script async
            src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_api_key') }}&libraries=places&callback=initCheckoutAutocomplete&loading=async"></script>
@endif
@endpush
