{{-- Forgot password form (storefront) --}}
@extends('frontend.layouts.app')
@section('title', 'Forgot Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <h2 class="title text-center mb-2">Forgot Password</h2>
            <p class="text-center text-muted mb-4" style="font-size:14px">
                Enter your email address and we'll send you a link to reset your password.
            </p>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="form-footer d-flex align-items-center justify-content-between mt-3">
                    <a href="{{ route('login') }}" class="text-muted" style="font-size:14px">
                        <i class="bx bx-arrow-back"></i> Back to Sign In
                    </a>
                    <button type="submit" class="btn btn-dark">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
