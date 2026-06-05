<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Poss Atelier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; background: #fff; }
        .header-shell { transition: all .3s ease; }
        .header-solid { background: rgba(255,255,255,.88); backdrop-filter: blur(10px); border: 1px solid rgba(0,0,0,.08); box-shadow: 0 10px 30px rgba(0,0,0,.08); }
        .header-shell:not(.header-solid) .brand-link { color: #fff; }
        .header-shell:not(.header-solid) .nav-link { color: rgba(255,255,255,.82); }
        .header-shell:not(.header-solid) .nav-link:hover { color: #fff; }
        .header-shell:not(.header-solid) .login-link,
        .header-shell:not(.header-solid) .buy-link {
            color: #fff;
            border-color: rgba(255,255,255,.55);
            background: rgba(255,255,255,.06);
        }
        .header-shell:not(.header-solid) .login-link:hover,
        .header-shell:not(.header-solid) .buy-link:hover {
            background: #fff;
            color: #111827;
            border-color: #fff;
        }
        .hero-title span { display: inline-block; opacity: 0; transform: translateY(60px); animation: rise .7s ease forwards; }
        .hero-title {
            font-size: clamp(110px, 17vw, 320px) !important;
            line-height: .86 !important;
            font-weight: 800 !important;
            letter-spacing: -0.04em !important;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .hero-title span:nth-child(1) { animation-delay: .05s; }
        .hero-title span:nth-child(2) { animation-delay: .13s; }
        .hero-title span:nth-child(3) { animation-delay: .21s; }
        .hero-title span:nth-child(4) { animation-delay: .29s; }
        .hero-title span:nth-child(5) { animation-delay: .37s; }
        .hero-title span:nth-child(6) { animation-delay: .45s; }
        .hero-title span:nth-child(7) { animation-delay: .53s; }
        .reveal { opacity: 0; transform: translateY(32px); transition: opacity .7s ease, transform .7s ease; }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }
        .scroll-card {
            transform: translateY(30px) scale(.98);
            opacity: .92;
            transition: transform .35s ease, opacity .35s ease;
            will-change: transform;
        }
        .scroll-card.is-visible {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        .hero-center {
            position: absolute;
            inset: 0;
            z-index: 10;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 44px;
            pointer-events: none;
            transition: opacity .35s ease, transform .35s ease;
        }
        .hero-center.is-hidden {
            opacity: 0;
            transform: translateY(-16px);
        }
        .gallery-track { scrollbar-width: none; -ms-overflow-style: none; }
        .gallery-track::-webkit-scrollbar { display: none; }
        .hombre-slider-wrap {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
        }
        .hombre-marquee {
            overflow: hidden;
            width: 100%;
            position: relative;
        }
        .hombre-marquee-track {
            display: flex;
            will-change: transform;
            gap: 16px;
            width: max-content;
            animation: hombreRio 42s linear infinite;
        }
        .hombre-item {
            width: 290px;
            flex: 0 0 auto;
        }
        .hombre-card {
            border-radius: 14px;
            overflow: hidden;
            background: #171717;
            border: 1px solid rgba(255,255,255,.12);
        }
        .hombre-card img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            display: block;
        }
        .hombre-card .meta {
            padding: 16px 18px 20px;
        }
        .hombre-card .meta p {
            font-size: 14px;
            font-weight: 600;
            color: rgba(255,255,255,.65);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .hombre-card .meta h3 {
            font-size: 22px;
            line-height: 1.25;
            font-weight: 700;
            color: #fff;
        }
        .hombre-card .meta small {
            display: block;
            font-size: 13px;
            color: rgba(255,255,255,.5);
            margin-top: 5px;
            line-height: 1.4;
        }
        @keyframes hombreRio {
            from { transform: translateX(0); }
            to { transform: translateX(calc(-50% - 8px)); }
        }
        .mujer-marquee {
            overflow: hidden;
            width: 100%;
            position: relative;
        }
        .mujer-marquee-track {
            display: flex;
            will-change: transform;
            gap: 16px;
            width: max-content;
            animation: mujerRio 44s linear infinite;
        }
        .mujer-item {
            width: 290px;
            flex: 0 0 auto;
        }
        @keyframes mujerRio {
            from { transform: translateX(0); }
            to { transform: translateX(calc(-50% - 8px)); }
        }
        @keyframes rise { to { opacity: 1; transform: translateY(0); } }

        /* ── Tarjetas clickeables ──────────────────────────────────────── */
        .hombre-card {
            cursor: pointer;
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .hombre-card:hover {
            transform: translateY(-4px) scale(1.015);
            box-shadow: 0 16px 40px rgba(0,0,0,.35);
        }
        .hombre-card::after {
            content: 'Ver en tienda →';
            position: absolute;
            bottom: 12px;
            right: 14px;
            background: #fff;
            color: #111827;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 999px;
            opacity: 0;
            transition: opacity .2s ease;
            pointer-events: none;
        }
        .hombre-card { position: relative; }
        .hombre-card:hover::after { opacity: 1; }

        /* Novedades */
        #products .scroll-card { cursor: pointer; }
        #products .scroll-card:hover img { transform: scale(1.03); transition: transform .4s ease; }
        #products .scroll-card img { transition: transform .4s ease; }

        /* Accesorios */
        #accessories .scroll-card {
            cursor: pointer;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        #accessories .scroll-card:hover {
            border-color: #111827;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
    </style>
</head>
<body class="bg-white text-neutral-900 antialiased">
    <header id="store-header" class="header-shell fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-4xl rounded-full">
        <div class="px-4 py-3 md:px-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="brand-link font-medium tracking-tight text-sm md:text-base">POSS ATELIER</a>
            <nav class="hidden md:flex items-center gap-8 text-sm text-neutral-600 font-medium">
                <a href="#products" class="nav-link hover:text-black">Novedades</a>
                <a href="#technology" class="nav-link hover:text-black">Hombre</a>
                <a href="#gallery" class="nav-link hover:text-black">Mujer</a>
                <a href="#accessories" class="nav-link hover:text-black">Accesorios</a>
            </nav>
            <div class="flex items-center gap-2 md:gap-3">
                <a href="{{ route('store.catalog') }}" class="buy-link text-sm px-4 py-2 rounded-full border border-neutral-300 hover:bg-neutral-900 hover:text-white transition">
                    Comprar
                </a>
                @auth
                    @if (auth()->user()->isCliente())
                        <a href="{{ route('store.cart') }}" class="login-link text-sm px-4 py-2 rounded-full border border-neutral-300 hover:bg-neutral-900 hover:text-white transition">
                            Carrito @if(($cartCount ?? 0) > 0)({{ $cartCount }})@endif
                        </a>
                    @else
                        <a href="{{ route('staff.login') }}" class="login-link text-sm px-4 py-2 rounded-full border border-neutral-300 hover:bg-neutral-900 hover:text-white transition">
                            Panel
                        </a>
                    @endif
                @else
                    <a href="{{ route('client.login') }}" class="login-link text-sm px-4 py-2 rounded-full border border-neutral-300 hover:bg-neutral-900 hover:text-white transition">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        <section class="relative min-h-screen flex items-end overflow-hidden">
            <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?q=80&w=2000" alt="Hero moda y estilo" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-black/35"></div>
            <div id="hero-center-title" class="hero-center">
                <h1 class="hero-title text-white text-center select-none">
                    <span>E</span><span>V</span><span>A</span><span>S</span><span>I</span><span>O</span><span>N</span>
                </h1>
            </div>
        </section>

        <section id="products" class="px-6 md:px-12 lg:px-20 py-20 reveal">
            <h2 class="text-3xl md:text-5xl font-medium tracking-tight mb-3">Novedades</h2>
            <p class="text-neutral-600 text-base md:text-lg mb-10">Novedades de la semana y tendencias que marcan estilo.</p>
            <div class="grid md:grid-cols-2 gap-6">
                <article class="scroll-card relative rounded-2xl overflow-hidden aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1400" alt="Nuevo stock de camisas y prendas" class="w-full h-full object-cover">
                    <div class="absolute bottom-4 left-4 bg-white/85 backdrop-blur px-4 py-2 rounded-full text-sm">Camisas nuevas · $ 149.900 COP</div>
                </article>
                <article class="scroll-card relative rounded-2xl overflow-hidden aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=1400" alt="Nuevo stock de bolsos y accesorios" class="w-full h-full object-cover">
                    <div class="absolute bottom-4 left-4 bg-white/85 backdrop-blur px-4 py-2 rounded-full text-sm">Pantalones en tendencia · $ 189.900 COP</div>
                </article>
            </div>
            <p class="text-neutral-600 text-xl md:text-2xl leading-relaxed mt-12 max-w-4xl">
                Descubre piezas seleccionadas para elevar tu estilo diario: cortes modernos, texturas premium y combinaciones versátiles.
            </p>
        </section>

        <section id="technology" class="bg-neutral-950 text-white px-6 md:px-12 lg:px-20 py-20 reveal">
            <h2 class="text-3xl md:text-5xl font-medium tracking-tight mb-10">Hombre</h2>
            <div id="hombre-slider" class="hombre-slider-wrap">
                <div class="hombre-marquee">
                    <div class="hombre-marquee-track">
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?q=80&w=1200" alt="Camisas para hombre"><div class="meta"><p>Camisas</p><h3>Cortes clásicos y urbanos</h3><small>Básica blanca, polo piqué y más</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1488161628813-04466f872be2?q=80&w=1200" alt="Pantalones para hombre"><div class="meta"><p>Pantalones</p><h3>Skinny, chino y cargo</h3><small>Jean clásico · bermuda denim · jogger</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.pexels.com/photos/2081199/pexels-photo-2081199.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="Bolsos de mano para hombre"><div class="meta"><p>Accesorios</p><h3>Bolsos, gorras y cinturones</h3><small>Tote lona · trucker denim · cuero</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1521369909029-2afed882baee?q=80&w=1200" alt="Gorras para hombre"><div class="meta"><p>Gorras</p><h3>Toque urbano para cada look</h3><small>Trucker denim · gorro beanie invierno</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?q=80&w=1200" alt="Sacos para hombre"><div class="meta"><p>Chaquetas</p><h3>Elegancia casual y versátil</h3><small>Jean oversize · cardigan lana · bomber</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1539533113208-f6df8cc8b543?q=80&w=1200" alt="Outfit para hombre"><div class="meta"><p>Outfits</p><h3>Combinaciones listas para usar</h3><small>Looks completos en tendencia 2026</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=1200" alt="Chaquetas para hombre"><div class="meta"><p>Chaquetas</p><h3>Capas modernas para temporada</h3><small>Cortavientos · abrigo camel · chaleco</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=1200" alt="Relojes para hombre"><div class="meta"><p>Accesorios</p><h3>Bufandas y guantes</h3><small>Lana mixta · touch screen</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1619119069152-a2b331eb392a?q=80&w=1200" alt="Ropa deportiva para hombre"><div class="meta"><p>Ropa deportiva</p><h3>Short, pants y camiseta</h3><small>Running · compresión · yoga</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1460353581641-37baddab0fa2?q=80&w=1200" alt="Camiseta para hombre"><div class="meta"><p>Camisetas</p><h3>Oversize, dry-fit y básica</h3><small>Algodón · tie-dye · manga larga</small></div></article>

                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?q=80&w=1200" alt="Camisas para hombre"><div class="meta"><p>Camisas</p><h3>Cortes clásicos y urbanos</h3><small>Básica blanca, polo piqué y más</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1488161628813-04466f872be2?q=80&w=1200" alt="Pantalones para hombre"><div class="meta"><p>Pantalones</p><h3>Skinny, chino y cargo</h3><small>Jean clásico · bermuda denim · jogger</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.pexels.com/photos/2081199/pexels-photo-2081199.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="Bolsos de mano para hombre"><div class="meta"><p>Accesorios</p><h3>Bolsos, gorras y cinturones</h3><small>Tote lona · trucker denim · cuero</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1521369909029-2afed882baee?q=80&w=1200" alt="Gorras para hombre"><div class="meta"><p>Gorras</p><h3>Toque urbano para cada look</h3><small>Trucker denim · gorro beanie invierno</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?q=80&w=1200" alt="Sacos para hombre"><div class="meta"><p>Chaquetas</p><h3>Elegancia casual y versátil</h3><small>Jean oversize · cardigan lana · bomber</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1539533113208-f6df8cc8b543?q=80&w=1200" alt="Outfit para hombre"><div class="meta"><p>Outfits</p><h3>Combinaciones listas para usar</h3><small>Looks completos en tendencia 2026</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=1200" alt="Chaquetas para hombre"><div class="meta"><p>Chaquetas</p><h3>Capas modernas para temporada</h3><small>Cortavientos · abrigo camel · chaleco</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=1200" alt="Relojes para hombre"><div class="meta"><p>Accesorios</p><h3>Bufandas y guantes</h3><small>Lana mixta · touch screen</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1619119069152-a2b331eb392a?q=80&w=1200" alt="Ropa deportiva para hombre"><div class="meta"><p>Ropa deportiva</p><h3>Short, pants y camiseta</h3><small>Running · compresión · yoga</small></div></article>
                        <article class="hombre-card hombre-item"><img src="https://images.unsplash.com/photo-1460353581641-37baddab0fa2?q=80&w=1200" alt="Camiseta para hombre"><div class="meta"><p>Camisetas</p><h3>Oversize, dry-fit y básica</h3><small>Algodón · tie-dye · manga larga</small></div></article>
                    </div>
                </div>
            </div>
        </section>

        <section id="gallery" class="px-6 md:px-12 lg:px-20 py-20 reveal">
            <h2 class="text-3xl md:text-5xl font-medium tracking-tight mb-10">Mujer</h2>
            <div class="mujer-marquee">
                <div class="mujer-marquee-track">
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?q=80&w=900" alt="Camisas para mujer"><div class="meta"><p>Camisas</p><h3>Corte elegante y versátil</h3><small>Básicas, estampadas y polo disponibles</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1475180098004-ca77a66827be?q=80&w=900" alt="Pantalones para mujer"><div class="meta"><p>Pantalones</p><h3>Denim, wide leg y jogger</h3><small>Jean mom fit, legging tiro alto y más</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=900" alt="Bolso de mano para mujer"><div class="meta"><p>Accesorios</p><h3>Bolsos tote y riñoneras</h3><small>Lona estampada · cuero sintético</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1635767798638-3e25273a8236?q=80&w=900" alt="Cadenas para mujer"><div class="meta"><p>Accesorios</p><h3>Bufandas y pañuelos</h3><small>Lana mixta · seda estampada</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=900" alt="Joyería para mujer"><div class="meta"><p>Accesorios</p><h3>Gorras y gorros beanie</h3><small>Trucker denim · invierno tejido</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1602173574767-37ac01994b2a?q=80&w=900" alt="Pulseras para mujer"><div class="meta"><p>Accesorios</p><h3>Cinturones y guantes</h3><small>Cuero sintético · touch screen</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=900" alt="Sacos para mujer"><div class="meta"><p>Chaquetas</p><h3>Cardigan y bomber</h3><small>Lana crudo · negro satinado</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?q=80&w=900" alt="Vestidos para mujer"><div class="meta"><p>Vestidos</p><h3>Midi, largo y tubo</h3><small>Lino terracota · satinado · casual</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?q=80&w=900" alt="Outfit femenino"><div class="meta"><p>Ropa deportiva</p><h3>Top, legging y conjunto yoga</h3><small>Soporte medio · compresión</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=900" alt="Falda para mujer"><div class="meta"><p>Vestidos</p><h3>Faldas midi y lápiz</h3><small>Plisada beige · lápiz negra</small></div></article>

                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?q=80&w=900" alt="Camisas para mujer"><div class="meta"><p>Camisas</p><h3>Corte elegante y versátil</h3><small>Básicas, estampadas y polo disponibles</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1475180098004-ca77a66827be?q=80&w=900" alt="Pantalones para mujer"><div class="meta"><p>Pantalones</p><h3>Denim, wide leg y jogger</h3><small>Jean mom fit, legging tiro alto y más</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=900" alt="Bolso de mano para mujer"><div class="meta"><p>Accesorios</p><h3>Bolsos tote y riñoneras</h3><small>Lona estampada · cuero sintético</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1635767798638-3e25273a8236?q=80&w=900" alt="Cadenas para mujer"><div class="meta"><p>Accesorios</p><h3>Bufandas y pañuelos</h3><small>Lana mixta · seda estampada</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=900" alt="Joyería para mujer"><div class="meta"><p>Accesorios</p><h3>Gorras y gorros beanie</h3><small>Trucker denim · invierno tejido</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1602173574767-37ac01994b2a?q=80&w=900" alt="Pulseras para mujer"><div class="meta"><p>Accesorios</p><h3>Cinturones y guantes</h3><small>Cuero sintético · touch screen</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=900" alt="Sacos para mujer"><div class="meta"><p>Chaquetas</p><h3>Cardigan y bomber</h3><small>Lana crudo · negro satinado</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?q=80&w=900" alt="Vestidos para mujer"><div class="meta"><p>Vestidos</p><h3>Midi, largo y tubo</h3><small>Lino terracota · satinado · casual</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?q=80&w=900" alt="Outfit femenino"><div class="meta"><p>Ropa deportiva</p><h3>Top, legging y conjunto yoga</h3><small>Soporte medio · compresión</small></div></article>
                    <article class="hombre-card mujer-item"><img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=900" alt="Falda para mujer"><div class="meta"><p>Vestidos</p><h3>Faldas midi y lápiz</h3><small>Plisada beige · lápiz negra</small></div></article>
                </div>
            </div>
        </section>

        <section id="accessories" class="px-6 md:px-12 lg:px-20 py-20 border-t border-neutral-200 reveal">
            <h2 class="text-3xl md:text-4xl font-medium mb-8">Accesorios</h2>
            <div class="grid md:grid-cols-3 gap-6">

                <article class="scroll-card" style="border:1px solid #e5e7eb;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:18px;cursor:pointer;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#111827';this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'" onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'" onclick="window.location.href='{{ route('store.catalog') }}'">
                    <div style="flex:1;min-width:0">
                        <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0 0 6px">Bolsos de mano</h3>
                        <p style="font-size:13px;color:#6b7280;margin:0 0 14px;line-height:1.5">Tote lona, riñoneras y bolsos de cuero sintético para hombre y mujer.</p>
                        <p style="font-size:22px;font-weight:700;color:#111827;margin:0">Desde $ 45.900 <span style="font-size:13px;font-weight:400;color:#9ca3af">COP</span></p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?w=200&q=80&fit=crop"
                         alt="Bolsos de mano"
                         style="width:96px;height:96px;object-fit:cover;border-radius:12px;flex-shrink:0">
                </article>

                <article class="scroll-card" style="border:1px solid #e5e7eb;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:18px;cursor:pointer;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#111827';this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'" onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'" onclick="window.location.href='{{ route('store.catalog') }}'">
                    <div style="flex:1;min-width:0">
                        <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0 0 6px">Bufandas y gorros</h3>
                        <p style="font-size:13px;color:#6b7280;margin:0 0 14px;line-height:1.5">Lana mixta, pañuelos de seda y gorros beanie para cada temporada.</p>
                        <p style="font-size:22px;font-weight:700;color:#111827;margin:0">Desde $ 22.900 <span style="font-size:13px;font-weight:400;color:#9ca3af">COP</span></p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=200&q=80&fit=crop"
                         alt="Bufandas y gorros"
                         style="width:96px;height:96px;object-fit:cover;border-radius:12px;flex-shrink:0">
                </article>

                <article class="scroll-card" style="border:1px solid #e5e7eb;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:18px;cursor:pointer;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#111827';this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'" onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'" onclick="window.location.href='{{ route('store.catalog') }}'">
                    <div style="flex:1;min-width:0">
                        <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0 0 6px">Cinturones y guantes</h3>
                        <p style="font-size:13px;color:#6b7280;margin:0 0 14px;line-height:1.5">Cuero sintético marrón, guantes touch screen y medias deportivas pack x3.</p>
                        <p style="font-size:22px;font-weight:700;color:#111827;margin:0">Desde $ 19.900 <span style="font-size:13px;font-weight:400;color:#9ca3af">COP</span></p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=200&q=80&fit=crop"
                         alt="Cinturones y guantes"
                         style="width:96px;height:96px;object-fit:cover;border-radius:12px;flex-shrink:0">
                </article>

            </div>
        </section>

        <section class="bg-white border-t border-neutral-200 reveal">
            <div class="grid grid-cols-2 md:grid-cols-4">
                <div class="p-8 text-center border-b md:border-b-0 md:border-r border-neutral-200"><p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Nueva colección</p><p class="text-3xl">2026</p></div>
                <div class="p-8 text-center border-b md:border-b-0 md:border-r border-neutral-200"><p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Prendas destacadas</p><p class="text-3xl">120+</p></div>
                <div class="p-8 text-center border-r border-neutral-200"><p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Marcas aliadas</p><p class="text-3xl">18</p></div>
                <div class="p-8 text-center"><p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Nuevos ingresos</p><p class="text-3xl">Semanal</p></div>
            </div>
            <div class="relative aspect-[16/8] w-full overflow-hidden">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2000" alt="Banner editorial de prendas para hombre" class="absolute inset-0 w-full h-full object-cover">
            </div>
        </section>

        <section id="about" class="bg-white reveal">
            <div class="px-6 md:px-12 lg:px-20 py-24 md:py-32">
                <p class="max-w-5xl mx-auto text-2xl md:text-4xl leading-relaxed">
                    Moda y estilo para cada ocasión: colecciones para hombre y mujer con prendas versátiles y accesorios que marcan diferencia.
                </p>
            </div>
            <div class="relative aspect-[16/8] w-full">
                <img src="https://images.unsplash.com/photo-1516826957135-700dedea698c?q=80&w=2000" alt="Imagen de colección de moda masculina con compras" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/70 to-transparent"></div>
            </div>
        </section>
    </main>

    <footer class="border-t border-neutral-200 px-6 md:px-12 lg:px-20 py-10 text-sm text-neutral-600">
        <div class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
            <p>2026 POSS ATELIER. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-black">Instagram</a>
                <a href="#" class="hover:text-black">Facebook</a>
                <a href="#" class="hover:text-black">TikTok</a>
            </div>
        </div>
    </footer>

    <script>
        // ── Hacer clickeables todas las tarjetas → van a la tienda ───────
        const TIENDA = '{{ route("store.catalog") }}';

        // Tarjetas de Novedades (2 artículos grandes)
        document.querySelectorAll('#products .scroll-card').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', () => window.location.href = TIENDA);
        });

        // Carrusel Hombre y Mujer
        document.querySelectorAll('.hombre-card').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', () => window.location.href = TIENDA);
        });

        // Tarjetas de Accesorios
        document.querySelectorAll('#accessories .scroll-card').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', () => window.location.href = TIENDA);
        });

        const header    = document.getElementById('store-header');
        const reveals   = document.querySelectorAll('.reveal');
        const scrollCards = document.querySelectorAll('.scroll-card');
        const heroTitle = document.getElementById('hero-center-title');

        const onScroll = () => {
            if (!header) return;
            if (window.scrollY > 40) header.classList.add('header-solid');
            else header.classList.remove('header-solid');

            if (heroTitle) {
                if (window.scrollY > 70) heroTitle.classList.add('is-hidden');
                else heroTitle.classList.remove('is-hidden');
            }

            const trigger = window.innerHeight * 0.88;
            reveals.forEach((el) => {
                const rect = el.getBoundingClientRect();
                if (rect.top < trigger) el.classList.add('is-visible');
            });

            scrollCards.forEach((card, index) => {
                const rect = card.getBoundingClientRect();
                const start = window.innerHeight * 0.95;
                const progress = Math.max(0, Math.min(1, (start - rect.top) / (window.innerHeight * 0.9)));
                const translate = 34 - (progress * 34);
                const scale = 0.97 + (progress * 0.03);
                card.style.transform = `translateY(${translate}px) scale(${scale})`;
                card.style.opacity = `${0.88 + (progress * 0.12)}`;
                if (progress > 0.12) card.classList.add('is-visible');
            });
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    </script>
</body>
</html>
