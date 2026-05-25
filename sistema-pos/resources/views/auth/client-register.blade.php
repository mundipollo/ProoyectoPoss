@extends('auth.client-auth-layout')

@section('title', 'Registro de cliente')

@section('content')
    <h1 class="text-xl font-medium tracking-tight text-center mb-1">Crear cuenta</h1>
    <p class="text-sm text-neutral-500 text-center mb-6">Solo para compradores. Sin acceso al panel administrativo.</p>

    <form method="POST" action="{{ route('client.register.store') }}">
        @csrf

        <label class="field-label" for="name">Nombre completo</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="field-input">
        @error('name') <p class="alert">{{ $message }}</p> @enderror

        <div style="margin-top: 18px;">
            <label class="field-label" for="email">Correo</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="field-input">
            @error('email') <p class="alert">{{ $message }}</p> @enderror
        </div>

        <div style="margin-top: 18px;">
            <label class="field-label" for="password">Contraseña</label>
            <input id="password" type="password" name="password" required class="field-input">
            @error('password') <p class="alert">{{ $message }}</p> @enderror
        </div>

        <div style="margin-top: 18px;">
            <label class="field-label" for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="field-input">
        </div>

        <button type="submit" class="sign-btn">Crear cuenta</button>
    </form>

    <p class="small-note">
        ¿Ya tienes cuenta? <a href="{{ route('client.login') }}" class="helper-link">Ingresar</a>
    </p>
@endsection
