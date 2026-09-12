{{-- Admin footer --}}
<footer class="footer mt-auto py-3 bg-white text-center">
    <div class="container">
        <span class="text-muted">
            Copyright © <span id="year">{{ date('Y') }}</span>
            <a href="{{ route('admin.dashboard') }}" class="text-dark fw-semibold">{{ setting('site_name', 'eBay Clone') }}</a>.
            All rights reserved.
        </span>
    </div>
</footer>
