{{-- Vendor application status page (pending / rejected) --}}
@extends('frontend.layouts.app')
@section('title', 'Store Application')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            @include('vendor.partials.alerts')

            @if ($profile->status === 'pending')
                <div class="card">
                    <div class="card-body p-5">
                        <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                        <h3>Application Under Review</h3>
                        <p class="text-muted">
                            Thanks for applying to open <strong>{{ $profile->store_name }}</strong>.
                            Our team is reviewing your application — you'll be able to add products once approved.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-dark">Back to Home</a>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body p-5">
                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                        <h3>Application Not Approved</h3>
                        <p class="text-muted">
                            Unfortunately your store application was not approved. Please contact support for details.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-dark">Back to Home</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
