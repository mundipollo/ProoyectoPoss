<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Poss Atelier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-scene {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            background:
                linear-gradient(to bottom, rgba(0, 0, 0, .55), rgba(0, 0, 0, .65)),
                url('https://images.unsplash.com/photo-1529139574466-a303027c1d8b?q=80&w=2000') center/cover no-repeat;
        }
        .login-card {
            width: 100%;
            max-width: 430px;
            background: #fff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .30);
            border-radius: 6px;
            overflow: hidden;
        }
        .login-top-photo {
            height: 200px;
            background:
                linear-gradient(to bottom, rgba(0,0,0,.08), rgba(0,0,0,.12)),
                url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2000') center/cover no-repeat;
            position: relative;
        }
        .brand-badge {
            position: absolute;
            left: 50%;
            bottom: -34px;
            transform: translateX(-50%);
            width: 86px;
            height: 86px;
            border-radius: 999px;
            background: #171717;
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 14px;
            letter-spacing: .08em;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .25);
        }
        .login-content { padding: 56px 30px 28px; }
        .field-label {
            font-size: 11px;
            letter-spacing: .12em;
            color: #a1a1aa;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: block;
        }
        .field-input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #e4e4e7;
            background: transparent;
            padding: 8px 0;
            font-size: 15px;
            color: #111827;
            outline: none;
        }
        .field-input:focus { border-bottom-color: #9ca3af; }
        .sign-btn {
            width: 100%;
            margin-top: 24px;
            border: none;
            padding: 12px 16px;
            background: #171717;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: .14em;
            font-size: 12px;
            border-radius: 9999px;
            transition: transform .2s ease, background .2s ease;
        }
        .sign-btn:hover { transform: translateY(-1px); background: #404040; }
        .helper-link { font-size: 13px; color: #6b7280; text-decoration: none; }
        .helper-link:hover { color: #111827; text-decoration: underline; }
        .small-note {
            margin-top: 14px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
        .alert { margin-top: 8px; font-size: 12px; color: #be123c; }
        .back-home {
            position: fixed;
            top: 18px;
            left: 18px;
            z-index: 80;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.35);
            background: rgba(15, 15, 15, .35);
            backdrop-filter: blur(8px);
            font-size: 12px;
            color: #fff;
            text-decoration: none;
        }
        .back-home:hover { background: rgba(255,255,255,.92); color: #111827; }
    </style>
</head>
<body class="font-sans antialiased bg-white text-neutral-900">
    <a href="{{ route('store.catalog') }}" class="back-home" aria-label="Volver a la tienda">
        <span aria-hidden="true">←</span>
        <span>Volver a la tienda</span>
    </a>

    <div class="login-scene">
        <div class="login-card">
            <div class="login-top-photo">
                <div class="brand-badge">Poss</div>
            </div>
            <div class="login-content">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
