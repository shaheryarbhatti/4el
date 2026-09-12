{{-- Reset password form (storefront) --}}
@extends('frontend.layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <h2 class="title text-center mb-2">Reset Password</h2>
            <p class="text-center text-muted mb-4" style="font-size:14px">
                Enter your email and choose a new password.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label>Email address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label>New Password *</label>
                    <input type="password" name="password" class="form-control" required autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label>Confirm New Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                </div>
                <div class="form-footer d-flex align-items-center justify-content-between mt-3">
                    <a href="{{ route('login') }}" class="text-muted" style="font-size:14px">
                        <i class="bx bx-arrow-back"></i> Back to Sign In
                    </a>
                    <button type="submit" class="btn btn-dark">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
