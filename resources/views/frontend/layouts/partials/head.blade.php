{{-- Storefront <head>: fonts + Porto demo36 CSS (paths -> public/frontend-assets) --}}
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', setting('site_tagline', 'Buy & Sell Everything')) · {{ setting('site_name', 'eBay Clone') }}</title>
    <meta name="description" content="{{ setting('site_tagline', '') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('frontend-assets/images/icons/favicon.png') }}">

    {{-- Google fonts loader (self-hosted webfont.js) --}}
    <script>
        WebFontConfig = {
            google: { families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700,800', 'Oswald:300,400,500,600,700,800'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '{{ asset('frontend-assets/js/webfont.js') }}';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <link rel="stylesheet" href="{{ asset('frontend-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/demo36.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/icon-fonts/bootstrap-icons/icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/wishlist.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/confirm-modal.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
    <style>
/* ========================================================
   LOGIN / REGISTER MODAL  — rich split-panel design
   ======================================================== */
#loginModal .modal-dialog { max-width: 860px; margin: 1.75rem auto; }
#loginModal .modal-content {
    border: 0; border-radius: 18px; overflow: hidden;
    box-shadow: 0 32px 80px rgba(0,0,0,.28);
    display: flex; flex-direction: row; min-height: 520px;
}

/* ---- Left dark panel ---- */
#loginModal .lm-panel {
    width: 300px; flex: 0 0 300px; position: relative; overflow: hidden;
    background: #0d0e14; display: flex; flex-direction: column;
    justify-content: space-between; padding: 36px 30px;
}
#loginModal .lm-panel__dots {
    position: absolute; inset: 0; pointer-events: none;
    background-image: radial-gradient(circle, rgba(255,255,255,.07) 1px, transparent 1px);
    background-size: 24px 24px;
}
#loginModal .lm-panel__orb {
    position: absolute; border-radius: 50%; filter: blur(60px); pointer-events: none;
}
#loginModal .lm-panel__orb--1 {
    width: 280px; height: 280px; top: -80px; left: -80px;
    background: radial-gradient(circle, #4f46e5 0%, transparent 70%); opacity: .65;
}
#loginModal .lm-panel__orb--2 {
    width: 220px; height: 220px; bottom: -60px; right: -60px;
    background: radial-gradient(circle, #db2777 0%, transparent 70%); opacity: .55;
}
#loginModal .lm-panel__orb--3 {
    width: 160px; height: 160px; top: 40%; left: 30%;
    background: radial-gradient(circle, #7c3aed 0%, transparent 70%); opacity: .30;
}
#loginModal .lm-brand {
    position: relative; z-index: 1;
}
#loginModal .lm-brand__logo {
    display: flex; align-items: center; gap: 10px; margin-bottom: 28px;
}
#loginModal .lm-brand__icon {
    width: 42px; height: 42px; border-radius: 12px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff; flex: 0 0 auto;
    box-shadow: 0 8px 20px rgba(79,70,229,.45);
}
#loginModal .lm-brand__name {
    font-size: 18px; font-weight: 800; color: #fff; letter-spacing: -.3px; line-height: 1;
}
#loginModal .lm-brand__tagline { font-size: 11px; color: rgba(255,255,255,.45); margin-top: 2px; }
#loginModal .lm-headline {
    font-size: 22px; font-weight: 800; color: #fff; line-height: 1.25;
    letter-spacing: -.4px; margin-bottom: 14px;
}
#loginModal .lm-headline span {
    background: linear-gradient(90deg, #818cf8, #f472b6);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
#loginModal .lm-desc { font-size: 13px; color: rgba(255,255,255,.48); line-height: 1.65; }
#loginModal .lm-features { position: relative; z-index: 1; }
#loginModal .lm-feature {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 10px;
}
#loginModal .lm-feature:last-child { margin-bottom: 0; }
#loginModal .lm-feature__icon {
    width: 30px; height: 30px; border-radius: 8px; flex: 0 0 auto;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
#loginModal .lm-feature__icon--blue  { background: rgba(79,70,229,.25); color: #a5b4fc; }
#loginModal .lm-feature__icon--pink  { background: rgba(219,39,119,.22); color: #f9a8d4; }
#loginModal .lm-feature__icon--green { background: rgba(16,185,129,.22); color: #6ee7b7; }
#loginModal .lm-feature__text { font-size: 12.5px; color: rgba(255,255,255,.60); font-weight: 500; }

/* ---- Right form panel ---- */
#loginModal .lm-form-panel {
    flex: 1; background: #fff; display: flex; flex-direction: column;
    padding: 36px 38px;
}
#loginModal .lm-close {
    position: absolute; top: 16px; right: 18px; font-size: 22px; line-height: 1;
    color: #999; background: none; border: 0; cursor: pointer; z-index: 10;
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; transition: background .15s;
}
#loginModal .lm-close:hover { background: #f0f0f0; color: #333; }

/* Tabs */
#loginModal .lm-tabs {
    display: flex; border-bottom: 1.5px solid #eee; margin-bottom: 28px; gap: 0;
}
#loginModal .lm-tab {
    flex: 1; text-align: center; padding: 11px 0; font-size: 14px; font-weight: 700;
    color: #aaa; border: 0; background: none; cursor: pointer; letter-spacing: -.1px;
    border-bottom: 2.5px solid transparent; margin-bottom: -1.5px; transition: all .15s;
}
#loginModal .lm-tab.active { color: #111; border-bottom-color: #111; }
#loginModal .lm-tab:hover:not(.active) { color: #555; }

/* Form fields */
#loginModal .lm-field { margin-bottom: 18px; }
#loginModal .lm-field label {
    display: block; font-size: 12.5px; font-weight: 700; color: #555;
    margin-bottom: 6px; letter-spacing: .01em;
}
#loginModal .lm-input-wrap { position: relative; }
#loginModal .lm-input-wrap .lm-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #bbb; font-size: 15px; pointer-events: none; transition: color .15s;
}
#loginModal .lm-input-wrap input {
    width: 100%; padding: 11px 14px 11px 38px; border-radius: 10px;
    border: 1.5px solid #e8e8e8; font-size: 14px; color: #222;
    outline: none; transition: border-color .15s, box-shadow .15s; background: #fafafa;
    height: 44px;
}
#loginModal .lm-input-wrap input:focus {
    border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff;
}
#loginModal .lm-input-wrap input:focus + .lm-icon,
#loginModal .lm-input-wrap:focus-within .lm-icon { color: #4f46e5; }
#loginModal .lm-input-wrap input::placeholder { color: #c4c4c4; }

/* Row with checkbox + forgot */
#loginModal .lm-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
#loginModal .lm-remember { display: flex; align-items: center; gap: 7px; font-size: 13px; color: #666; cursor: pointer; }
#loginModal .lm-remember input[type="checkbox"] { width: 15px; height: 15px; accent-color: #4f46e5; cursor: pointer; }
#loginModal .lm-forgot { font-size: 12.5px; color: #4f46e5; text-decoration: none; font-weight: 600; }
#loginModal .lm-forgot:hover { text-decoration: underline; }

/* Submit button */
#loginModal .lm-submit {
    width: 100%; padding: 13px; border: 0; border-radius: 11px; cursor: pointer;
    font-size: 14.5px; font-weight: 700; color: #fff; letter-spacing: .01em;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    box-shadow: 0 10px 26px rgba(79,70,229,.38); transition: filter .15s, transform .12s;
}
#loginModal .lm-submit:hover { filter: brightness(1.08); transform: translateY(-1px); }
#loginModal .lm-submit:active { transform: translateY(0); }

/* Switch pane link */
#loginModal .lm-switch { text-align: center; margin-top: 20px; font-size: 13px; color: #888; }
#loginModal .lm-switch a { color: #4f46e5; font-weight: 700; text-decoration: none; }
#loginModal .lm-switch a:hover { text-decoration: underline; }

/* Error alert */
#loginModal .lm-alert {
    background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;
    border-radius: 9px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px;
}

/* Divider */
#loginModal .lm-divider {
    display: flex; align-items: center; gap: 12px; margin: 18px 0;
    font-size: 12px; color: #ccc;
}
#loginModal .lm-divider::before,
#loginModal .lm-divider::after { content: ''; flex: 1; height: 1px; background: #eee; }

/* Trust badges */
#loginModal .lm-trust {
    display: flex; align-items: center; justify-content: center; gap: 16px;
    margin-top: 18px; padding-top: 16px; border-top: 1px solid #f0f0f0;
}
#loginModal .lm-trust__item {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: #aaa; font-weight: 600;
}
#loginModal .lm-trust__item i { font-size: 13px; color: #12b76a; }

/* Tab pane visibility */
#loginModal .lm-pane { display: none; }
#loginModal .lm-pane.active { display: block; }

/* Responsive */
@media (max-width: 660px) {
    #loginModal .lm-panel { display: none; }
    #loginModal .modal-dialog { max-width: 440px; }
    #loginModal .lm-form-panel { padding: 28px 24px; }
}
    </style>
</head>
