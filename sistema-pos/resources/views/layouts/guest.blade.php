<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Poss Atelier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg {
            background-image: linear-gradient(to bottom, rgba(5, 5, 5, .5), rgba(5, 5, 5, .75)),
                url('https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=2000');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans antialiased text-neutral-900">
    <div class="min-h-screen auth-bg flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl border border-white/25 bg-white/10 backdrop-blur-xl shadow-2xl">
            <div class="px-7 pt-7 pb-3 text-white">
                <a href="{{ route('home') }}" class="text-xs tracking-[0.25em] uppercase text-white/80 hover:text-white">Poss Atelier</a>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight">Bienvenido</h1>
                <p class="mt-1 text-white/75 text-sm">Inicia sesión para acceder al panel administrativo.</p>
            </div>
            <div class="px-7 pb-7">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
