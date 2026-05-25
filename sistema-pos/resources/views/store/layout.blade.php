<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tienda') — Poss Atelier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('store.partials.styles')
    @stack('styles')
</head>
<body class="bg-white text-neutral-900 antialiased min-h-screen flex flex-col">
    @include('store.partials.header', ['headerSolid' => $headerSolid ?? false])

    @if (session('status'))
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-40 w-[92%] max-w-xl">
            <div class="store-alert text-green-800 text-center">{{ session('status') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-40 w-[92%] max-w-xl">
            <div class="store-alert text-red-800 text-center">{{ session('error') }}</div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('store.partials.footer')
    @include('store.partials.scripts')
    @stack('scripts')
</body>
</html>
