@extends('auth.client-auth-layout')

@section('title', 'Ingresar como cliente')

@section('content')
    <h1 class="text-xl font-medium tracking-tight text-center mb-1">Ingresar como cliente</h1>
    <p class="text-sm text-neutral-500 text-center mb-6">Compra en la tienda y guarda productos en tu carrito.</p>

    @if (session('status'))
        <p class="mb-4 text-sm text-green-800 bg-green-50 p-3 rounded-lg text-center">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('client.login.store') }}">
        @csrf

        <label class="field-label" for="email">Correo</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="field-input">
        @error('email') <p class="alert">{{ $message }}</p> @enderror

        <div style="margin-top: 18px;">
            <label class="field-label" for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="field-input">
            @error('password') <p class="alert">{{ $message }}</p> @enderror
        </div>

        <label style="margin-top: 12px; display:flex; align-items:center; gap:8px; font-size:12px; color:#6b7280;">
            <input type="checkbox" name="remember"> Recordarme
        </label>

        <button type="submit" class="sign-btn">Ingresar</button>
    </form>

    <p class="small-note">
        ¿No tienes cuenta? <a href="{{ route('client.register') }}" class="helper-link">Regístrate</a>
    </p>
    <p class="small-note" style="margin-top:8px;">
        ¿Eres empleado? <a href="{{ route('staff.login') }}" class="helper-link">Acceso staff</a>
    </p>
@endsection
