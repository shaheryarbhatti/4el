{{--
    ADMIN MASTER LAYOUT  (YNEX Bootstrap 5 template)
    ============================================================================
    Every admin page extends this file:

        @extends('admin.layouts.app')
        @section('title', 'Dashboard')
        @section('content') ... your page ... @endsection

    The shell (sidebar + header + footer + all CSS/JS) lives here and in the
    partials under resources/views/admin/layouts/partials/. You almost never
    need to touch this file — just build page content in @section('content').

    Asset paths point to  public/admin-assets/  (copied from the YNEX "dist").
--}}
<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light"
      data-header-styles="light" data-menu-styles="dark" data-toggled="close">

@include('admin.layouts.partials.head')

<body>
    <div class="page">

        {{-- Top header bar (logo toggle, search, profile, logout) --}}
        @include('admin.layouts.partials.header')

        {{-- Left sidebar navigation --}}
        @include('admin.layouts.partials.sidebar')

        {{-- ================= PAGE CONTENT ================= --}}
        <div class="main-content app-content">
            <div class="container-fluid">

                {{-- Page heading + breadcrumb (each page can override) --}}
                <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
                    <div>
                        <h1 class="page-title fw-medium fs-18 mb-0">@yield('page_title', View::yieldContent('title'))</h1>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                    <div>@yield('page_actions')</div>
                </div>

                {{-- Flash messages (success / error) shown on every page --}}
                @include('admin.layouts.partials.alerts')

                {{-- The actual page content --}}
                @yield('content')

            </div>
        </div>
        {{-- =============== /PAGE CONTENT =============== --}}

        @include('admin.layouts.partials.footer')
    </div>

    @include('admin.layouts.partials.scripts')
    @stack('scripts') {{-- pages can push extra <script> here --}}
</body>
</html>
