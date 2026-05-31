<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — WMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #f1f3f7; min-height: 100vh; display: flex; align-items: center; justify-content: center; }

        .auth-wrap { display: flex; width: 100%; max-width: 900px; min-height: 520px; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.12); margin: 24px; }

        /* Left panel */
        .auth-left { flex: 1; background: #0f1117; padding: 48px 44px; display: flex; flex-direction: column; justify-content: space-between; }
        .auth-brand { display: flex; align-items: center; gap: 12px; }
        .auth-brand-mark { width: 38px; height: 38px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .auth-brand-name { font-size: 15px; font-weight: 600; color: #fff; letter-spacing: 0.01em; }
        .auth-brand-sub { font-size: 11px; color: #6b7280; margin-top: 1px; }
        .auth-tagline { }
        .auth-tagline h2 { font-size: 26px; font-weight: 600; color: #fff; line-height: 1.35; letter-spacing: -0.01em; }
        .auth-tagline p { font-size: 13px; color: #6b7280; margin-top: 10px; line-height: 1.6; }
        .auth-features { display: flex; flex-direction: column; gap: 10px; }
        .auth-feature { display: flex; align-items: center; gap: 10px; }
        .auth-feature-dot { width: 6px; height: 6px; background: #2563eb; border-radius: 50%; flex-shrink: 0; }
        .auth-feature span { font-size: 12px; color: #9ca3af; }

        /* Right panel */
        .auth-right { width: 400px; background: #fff; padding: 48px 44px; display: flex; flex-direction: column; justify-content: center; flex-shrink: 0; }
        .auth-title { font-size: 20px; font-weight: 600; color: #111827; margin-bottom: 4px; }
        .auth-subtitle { font-size: 13px; color: #9ca3af; margin-bottom: 28px; }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 11px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 10px 13px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #111827; background: #fff; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.15s, box-shadow 0.15s; }
        .form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-input::placeholder { color: #d1d5db; }

        .btn-submit { width: 100%; padding: 11px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.15s; margin-top: 8px; }
        .btn-submit:hover { background: #1d4ed8; }

        .auth-link { text-align: center; margin-top: 18px; font-size: 13px; color: #9ca3af; }
        .auth-link a { color: #2563eb; text-decoration: none; font-weight: 500; }
        .auth-link a:hover { text-decoration: underline; }

        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 13px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; }

        @media (max-width: 640px) {
            .auth-left { display: none; }
            .auth-right { width: 100%; }
        }
    </style>
</head>
<body>

<div class="auth-wrap">

    {{-- Left branding panel --}}
    <div class="auth-left">
        <div class="auth-brand">
            <div class="auth-brand-mark">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M3 6l7-3 7 3v9l-7 3-7-3V6z" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M10 3v13M3 6l7 3 7-3" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <div class="auth-brand-name">WMS</div>
                <div class="auth-brand-sub">Warehouse Management</div>
            </div>
        </div>

        <div class="auth-tagline">
            <h2>Manage your warehouse with confidence.</h2>
            <p>Track inventory, monitor stock levels, and keep your operations running smoothly.</p>
        </div>

        <div class="auth-features">
            <div class="auth-feature"><div class="auth-feature-dot"></div><span>Real-time stock tracking</span></div>
            <div class="auth-feature"><div class="auth-feature-dot"></div><span>Full transaction history</span></div>
            <div class="auth-feature"><div class="auth-feature-dot"></div><span>Inventory reports</span></div>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="auth-right">
        <div class="auth-title">Welcome back</div>
        <div class="auth-subtitle">Sign in to your account to continue</div>

        @if($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="you@example.com" class="form-input" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       placeholder="••••••••" class="form-input" required>
            </div>

            <button type="submit" class="btn-submit">Sign in</button>
        </form>

        <div class="auth-link">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>

</div>

</body>
</html>