{{--
    ADMIN LOGIN — custom premium split-screen design.
    ----------------------------------------------------------------------------
    Fully self-contained (its own CSS + inline SVG art), so it does NOT depend on
    the template's carousel/auth classes. Left = branded visual panel with an
    animated gradient mesh, floating glass stat cards and a custom marketplace
    illustration. Right = clean, functional sign-in form (posts to the real
    admin.login.submit route with CSRF, validation, remember-me, show/hide).

    To rebrand: change the palette variables in :root below, and the copy in the
    left panel. Nothing else needs editing.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · {{ setting('site_name', 'Admin') }}</title>
    <link rel="icon" href="{{ asset('admin-assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    {{-- Only Remix icon font is needed from the template; everything else is custom. --}}
    <link href="{{ asset('admin-assets/css/icons.min.css') }}" rel="stylesheet">

    <style>
        /* ===== Palette (edit these to rebrand) ===== */
        :root{
            --brand-1:#4f46e5;   /* indigo  */
            --brand-2:#7c3aed;   /* violet  */
            --brand-3:#db2777;   /* pink    */
            --brand-4:#2563eb;   /* blue    */
            --ink:#1f2430;
            --muted:#8a92a6;
            --line:#e7e9f0;
            --field-bg:#f6f7fb;
        }

        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%}
        body{
            font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
            color:var(--ink);
            background:#fff;
            -webkit-font-smoothing:antialiased;
        }

        .auth-wrap{display:grid;grid-template-columns:1.05fr .95fr;min-height:100vh}

        /* ============================================================
           LEFT — branded visual panel
        ============================================================ */
        .auth-brand{
            position:relative;overflow:hidden;
            display:flex;flex-direction:column;justify-content:space-between;
            padding:56px 60px;color:#fff;
            background:linear-gradient(135deg,var(--brand-1),var(--brand-2) 55%,var(--brand-3));
        }
        /* animated moving mesh blobs */
        .auth-brand::before,.auth-brand::after{
            content:"";position:absolute;border-radius:50%;filter:blur(60px);opacity:.55;z-index:0;
        }
        .auth-brand::before{
            width:520px;height:520px;top:-160px;right:-120px;
            background:radial-gradient(circle at 30% 30%,#22d3ee,transparent 60%),radial-gradient(circle at 70% 70%,var(--brand-3),transparent 60%);
            animation:float1 12s ease-in-out infinite;
        }
        .auth-brand::after{
            width:460px;height:460px;bottom:-160px;left:-120px;
            background:radial-gradient(circle at 40% 40%,var(--brand-4),transparent 60%),radial-gradient(circle at 60% 60%,#f59e0b,transparent 60%);
            animation:float2 14s ease-in-out infinite;
        }
        @keyframes float1{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(-30px,30px) scale(1.08)}}
        @keyframes float2{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(30px,-24px) scale(1.05)}}

        .brand-top,.brand-mid,.brand-bottom{position:relative;z-index:1}

        .brand-logo{height:34px;filter:brightness(0) invert(1)}
        .brand-badge{
            display:inline-flex;align-items:center;gap:8px;margin-top:26px;
            padding:7px 14px;border-radius:999px;font-size:12.5px;font-weight:600;
            background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(6px);
        }
        .brand-title{font-size:34px;line-height:1.18;font-weight:700;margin-top:22px;max-width:15ch}
        .brand-sub{margin-top:14px;font-size:15px;line-height:1.6;opacity:.85;max-width:44ch}

        /* illustration + floating glass cards */
        .brand-art{position:relative;margin:26px 0;height:230px}
        .brand-art svg{width:100%;height:100%;display:block;filter:drop-shadow(0 24px 40px rgba(0,0,0,.28))}
        .glass{
            position:absolute;z-index:2;display:flex;align-items:center;gap:11px;
            padding:12px 15px;border-radius:16px;color:#fff;
            background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.3);
            backdrop-filter:blur(10px);box-shadow:0 12px 30px rgba(0,0,0,.18);
            animation:bob 6s ease-in-out infinite;
        }
        .glass i{font-size:20px}
        .glass .g-num{font-size:16px;font-weight:700;line-height:1}
        .glass .g-lbl{font-size:11px;opacity:.85}
        .glass.g1{top:6px;right:6px;animation-delay:.2s}
        .glass.g2{bottom:2px;left:-6px;animation-delay:1.2s}
        @keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}

        .brand-features{display:flex;flex-direction:column;gap:16px;margin-top:6px}
        .feat{display:flex;align-items:center;gap:14px}
        .feat-ic{
            flex:0 0 auto;width:42px;height:42px;border-radius:12px;
            display:grid;place-items:center;font-size:20px;
            background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);
        }
        .feat h6{font-size:14.5px;font-weight:600;margin-bottom:2px}
        .feat p{font-size:12.5px;opacity:.8}

        .brand-bottom{display:flex;align-items:center;gap:18px;font-size:12.5px;opacity:.85}
        .brand-bottom .dot{width:4px;height:4px;border-radius:50%;background:#fff;opacity:.5}

        /* ============================================================
           RIGHT — form panel
        ============================================================ */
        .auth-form{display:flex;align-items:center;justify-content:center;padding:40px}
        .form-card{width:100%;max-width:410px}
        .form-logo{display:none;height:34px;margin-bottom:26px}
        .form-h{font-size:26px;font-weight:700;letter-spacing:-.02em}
        .form-p{color:var(--muted);margin-top:8px;font-size:14.5px}

        .alert-err{
            display:flex;align-items:center;gap:10px;margin-top:20px;
            padding:12px 14px;border-radius:12px;font-size:13.5px;
            background:#fdecec;color:#c0392b;border:1px solid #f7c9c9;
        }

        .field{margin-top:20px}
        .field label{display:block;font-size:13px;font-weight:600;margin-bottom:8px;color:#3a4152}
        .input{position:relative;display:flex;align-items:center}
        .input > i{position:absolute;left:15px;color:var(--muted);font-size:18px}
        .input input{
            width:100%;height:52px;border:1.5px solid var(--line);background:var(--field-bg);
            border-radius:13px;padding:0 48px;font-size:15px;color:var(--ink);
            transition:border-color .15s, box-shadow .15s, background .15s;
        }
        .input input::placeholder{color:#b6bccb}
        .input input:focus{
            outline:none;border-color:var(--brand-2);background:#fff;
            box-shadow:0 0 0 4px rgba(124,58,237,.12);
        }
        .input .toggle{
            position:absolute;right:8px;left:auto;background:none;border:0;cursor:pointer;
            color:var(--muted);width:38px;height:38px;border-radius:9px;display:grid;place-items:center;
        }
        .input .toggle:hover{background:#eef0f6;color:var(--ink)}

        .row-between{display:flex;align-items:center;justify-content:space-between;margin-top:16px}
        .check{display:flex;align-items:center;gap:8px;font-size:13.5px;color:#3a4152;cursor:pointer;user-select:none}
        .check input{width:16px;height:16px;accent-color:var(--brand-2)}
        .link{color:var(--brand-2);text-decoration:none;font-size:13.5px;font-weight:600}
        .link:hover{text-decoration:underline}

        .btn-primary{
            width:100%;height:52px;margin-top:24px;border:0;cursor:pointer;color:#fff;
            font-size:15.5px;font-weight:600;border-radius:13px;
            background:linear-gradient(135deg,var(--brand-1),var(--brand-2) 60%,var(--brand-3));
            box-shadow:0 12px 26px rgba(79,70,229,.34);
            transition:transform .12s, box-shadow .12s, filter .12s;
        }
        .btn-primary:hover{filter:brightness(1.05);box-shadow:0 16px 30px rgba(79,70,229,.42)}
        .btn-primary:active{transform:translateY(1px)}

        .cred-hint{
            margin-top:22px;padding:11px 14px;border-radius:11px;font-size:12.5px;color:#5b6474;
            background:var(--field-bg);border:1px dashed var(--line);text-align:center;
        }
        .cred-hint b{color:var(--brand-2)}
        .form-foot{margin-top:26px;font-size:12.5px;color:var(--muted);text-align:center}

        /* ============================================================
           Responsive — hide the visual panel on small screens
        ============================================================ */
        @media (max-width:991px){
            .auth-wrap{grid-template-columns:1fr}
            .auth-brand{display:none}
            .form-logo{display:block}
        }
    </style>

    {{-- One-screen layout overrides (external, loaded after the inline styles) --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/admin-login.css') }}">
</head>
<body>
<div class="auth-wrap">

    {{-- ===================== LEFT: BRAND PANEL ===================== --}}
    <aside class="auth-brand">
        <div class="brand-top">
            <img src="{{ setting('login_logo') ? asset('storage/'.setting('login_logo')) : asset('admin-assets/images/brand-logos/desktop-logo.png') }}" class="brand-logo" alt="logo">
            <div class="brand-badge"><i class="ri-shield-check-line"></i> Secure Admin Access</div>
            <h1 class="brand-title">Run your marketplace with confidence</h1>
            <p class="brand-sub">Products, auctions, orders, vendors and payments — everything in one powerful, beautifully organized dashboard.</p>
        </div>

        {{-- Custom inline SVG marketplace illustration + floating glass cards --}}
        <div class="brand-mid">
            <div class="brand-art">
                <div class="glass g1"><i class="ri-auction-line"></i><div><div class="g-num">3,240</div><div class="g-lbl">Live auctions</div></div></div>
                <div class="glass g2"><i class="ri-line-chart-line"></i><div><div class="g-num">$1.2M</div><div class="g-lbl">Sales this month</div></div></div>

                <svg viewBox="0 0 520 230" xmlns="http://www.w3.org/2000/svg" fill="none">
                    <defs>
                        <linearGradient id="card" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#ffffff" stop-opacity=".95"/>
                            <stop offset="1" stop-color="#eef1ff" stop-opacity=".9"/>
                        </linearGradient>
                        <linearGradient id="tag" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#f59e0b"/><stop offset="1" stop-color="#db2777"/>
                        </linearGradient>
                    </defs>
                    <!-- storefront window -->
                    <rect x="150" y="24" width="220" height="150" rx="16" fill="url(#card)"/>
                    <rect x="150" y="24" width="220" height="40" rx="16" fill="#4f46e5"/>
                    <circle cx="170" cy="44" r="5" fill="#fff" opacity=".9"/><circle cx="188" cy="44" r="5" fill="#fff" opacity=".6"/><circle cx="206" cy="44" r="5" fill="#fff" opacity=".4"/>
                    <!-- product tiles -->
                    <rect x="168" y="78" width="80" height="40" rx="8" fill="#e9ecff"/><rect x="168" y="126" width="80" height="10" rx="5" fill="#c7ccf5"/><rect x="168" y="142" width="52" height="8" rx="4" fill="#dfe3fb"/>
                    <rect x="272" y="78" width="80" height="40" rx="8" fill="#ffe7f2"/><rect x="272" y="126" width="80" height="10" rx="5" fill="#f5b6d3"/><rect x="272" y="142" width="52" height="8" rx="4" fill="#fbd7e8"/>
                    <!-- price tag -->
                    <g transform="rotate(-12 96 150)">
                        <rect x="60" y="120" width="96" height="60" rx="12" fill="url(#tag)"/>
                        <circle cx="80" cy="140" r="7" fill="#fff"/>
                        <rect x="98" y="132" width="44" height="9" rx="4.5" fill="#fff" opacity=".95"/>
                        <rect x="98" y="150" width="30" height="9" rx="4.5" fill="#fff" opacity=".7"/>
                    </g>
                    <!-- auction gavel -->
                    <g transform="rotate(18 410 150)">
                        <rect x="372" y="150" width="86" height="14" rx="7" fill="#fff" opacity=".95"/>
                        <rect x="398" y="104" width="18" height="54" rx="9" fill="#22d3ee"/>
                        <rect x="378" y="92" width="58" height="26" rx="10" fill="#0ea5e9"/>
                    </g>
                </svg>
            </div>

            <div class="brand-features">
                <div class="feat"><div class="feat-ic"><i class="ri-store-2-line"></i></div><div><h6>Products &amp; Auctions</h6><p>Fixed-price and timed bidding in one place.</p></div></div>
                <div class="feat"><div class="feat-ic"><i class="ri-bank-card-line"></i></div><div><h6>Secure Payments</h6><p>Stripe &amp; PayPal, taxes and vendor payouts.</p></div></div>
                <div class="feat"><div class="feat-ic"><i class="ri-pie-chart-2-line"></i></div><div><h6>Real-time Analytics</h6><p>Track revenue, top sellers and orders live.</p></div></div>
            </div>
        </div>

        <div class="brand-bottom">
            <span>© {{ date('Y') }} {{ setting('site_name', 'eBay Clone') }}</span>
            <span class="dot"></span><span>Privacy</span>
            <span class="dot"></span><span>Terms</span>
        </div>
    </aside>

    {{-- ===================== RIGHT: FORM ===================== --}}
    <main class="auth-form">
        <div class="form-card">
            <img src="{{ setting('login_logo') ? asset('storage/'.setting('login_logo')) : asset('admin-assets/images/brand-logos/desktop-logo.png') }}" class="form-logo" alt="logo">

            <h2 class="form-h">Admin Sign In</h2>
            <p class="form-p">Welcome back to <b>{{ setting('site_name', 'eBay Clone') }}</b> — please sign in to continue.</p>

            @if ($errors->any())
                <div class="alert-err"><i class="ri-error-warning-line" style="font-size:18px"></i><span>{{ $errors->first() }}</span></div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <div class="input">
                        <i class="ri-mail-line"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               placeholder="admin@ebay.test" required autofocus>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input">
                        <i class="ri-lock-2-line"></i>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle" onclick="togglePw(this)" aria-label="Show password">
                            <i class="ri-eye-off-line"></i>
                        </button>
                    </div>
                </div>

                <div class="row-between">
                    <label class="check">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="javascript:void(0)" class="link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <div class="cred-hint">Demo access — <b>admin@ebay.test</b> / <b>password</b></div>
            <p class="form-foot">Protected area · authorized administrators only</p>
        </div>
    </main>

</div>

<script>
    // Show/hide password toggle (swaps input type + eye icon).
    function togglePw(btn){
        var input = document.getElementById('password');
        var icon  = btn.querySelector('i');
        if(input.type === 'password'){ input.type = 'text';  icon.className = 'ri-eye-line'; }
        else                         { input.type = 'password'; icon.className = 'ri-eye-off-line'; }
    }
</script>
</body>
</html>
