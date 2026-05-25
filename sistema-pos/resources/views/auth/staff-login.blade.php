<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acceso Staff | Poss Atelier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --accent: #f14f74; }
        .login-scene {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            background:
                linear-gradient(to bottom, rgba(0, 0, 0, .55), rgba(0, 0, 0, .65)),
                url('https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=2000') center/cover no-repeat;
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
                url('https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=2000') center/cover no-repeat;
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
            background: var(--accent);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 19px;
            letter-spacing: .02em;
            box-shadow: 0 8px 20px rgba(241, 79, 116, .45);
        }
        .login-content { padding: 56px 30px 24px; }
        .field-label {
            font-size: 11px;
            letter-spacing: .12em;
            color: #a1a1aa;
            text-transform: uppercase;
            margin-bottom: 6px;
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
            background: var(--accent);
            color: #fff;
            text-transform: uppercase;
            letter-spacing: .14em;
            font-size: 12px;
            transition: transform .2s ease, filter .2s ease;
        }
        .sign-btn:hover { transform: translateY(-1px); filter: brightness(.97); }
        .helper-link { font-size: 13px; color: #6b7280; text-decoration: none; }
        .helper-link:hover { color: #111827; text-decoration: underline; }
        .small-note {
            margin-top: 14px;
            text-align: center;
            font-size: 11px;
            letter-spacing: .08em;
            color: #9ca3af;
            text-transform: uppercase;
        }
        .alert {
            margin-top: 12px;
            font-size: 12px;
            color: #be123c;
        }
        .session-box {
            border: 1px solid #f1f5f9;
            background: #f8fafc;
            border-radius: 6px;
            padding: 14px;
            font-size: 14px;
            color: #334155;
        }
        .session-actions { margin-top: 12px; display: flex; gap: 8px; }
        .btn-lite { font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #d4d4d8; text-decoration: none; color: #111827; background: #fff; }
        .btn-lite:hover { background: #f5f5f5; }
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
            letter-spacing: .03em;
        }
        .back-home:hover { background: rgba(255,255,255,.92); color: #111827; }
    </style>
</head>
<body class="font-sans antialiased">
    <a href="{{ route('home') }}" class="back-home" aria-label="Volver al inicio">
        <span aria-hidden="true">←</span>
        <span>Volver al inicio</span>
    </a>

    <div class="login-scene">
        <div class="login-card">
            <div class="login-top-photo">
                <div class="brand-badge">Poss</div>
            </div>

            <div class="login-content">
                @if (auth()->check())
                    <div class="session-box">
                        Ya tienes sesión iniciada como <strong>{{ auth()->user()->name }}</strong>.
                        <div class="session-actions">
                            <a class="btn-lite" href="{{ route('dashboard') }}">Ir al panel</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-lite">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <label class="field-label" for="email">Correo</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="field-input">
                        @error('email') <p class="alert">{{ $message }}</p> @enderror

                        <div style="margin-top: 18px;">
                            <label class="field-label" for="password">Contraseña</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="field-input">
                            @error('password') <p class="alert">{{ $message }}</p> @enderror
                        </div>

                        <div style="margin-top: 12px; display:flex; align-items:center; justify-content:space-between;">
                            <label style="font-size: 12px; color:#6b7280;">
                                <input id="remember_me" type="checkbox" name="remember"> Recordarme
                            </label>
                            @if (Route::has('password.request'))
                                <a class="helper-link" href="{{ route('password.request') }}">Olvidé mi contraseña</a>
                            @endif
                        </div>

                        <button type="submit" class="sign-btn">Ingresar</button>

                        <p class="small-note">Acceso solo para personal (admin / empleados).</p>
                        <p class="small-note" style="margin-top:8px;">¿Quieres comprar? <a href="{{ route('client.login') }}" style="color:#6b7280; text-decoration:none;">Ingreso clientes</a></p>
                    </form>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
