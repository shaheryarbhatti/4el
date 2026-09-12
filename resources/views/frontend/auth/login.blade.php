{{-- Customer login (storefront) --}}
@extends('frontend.layouts.app')
@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <h2 class="title text-center mb-4">Sign In</h2>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-footer d-flex align-items-center justify-content-between">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" id="remember" class="custom-control-input">
                        <label class="custom-control-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-dark">Sign In</button>
                </div>
                <div class="text-center mt-2">
                    <a href="{{ route('password.request') }}" style="font-size:13px;color:#666;">Forgot password?</a>
                </div>
            </form>
            <p class="text-center mt-3">No account? <a href="{{ route('register') }}">Create one</a></p>
        </div>
    </div>
</div>
@endsection
