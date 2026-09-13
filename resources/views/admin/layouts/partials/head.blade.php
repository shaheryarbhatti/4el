{{-- Admin <head>: meta + all CSS (paths point to public/admin-assets) --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Title: page @section('title') + site name --}}
    <title>@yield('title', 'Dashboard') · {{ setting('site_name', 'Admin') }}</title>

    <link rel="icon" href="{{ asset('admin-assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    {{-- Theme JS that must load before render (menu/theme setup) --}}
    <script src="{{ asset('admin-assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/main.js') }}"></script>

    {{-- Core CSS --}}
    <link id="style" href="{{ asset('admin-assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

    {{-- Brand theme (sidebar etc.) — loads on every admin page, after core CSS --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/admin-theme.css') }}">

    {{-- Page-specific CSS goes here --}}
    @stack('styles')
</head>
