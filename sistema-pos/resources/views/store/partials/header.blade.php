<header id="store-header" class="header-shell fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-4xl rounded-full {{ ($headerSolid ?? false) ? 'header-solid' : '' }}">
    <div class="px-4 py-3 md:px-6 flex items-center justify-between gap-2">
        <a href="{{ route('home') }}" class="brand-link font-medium tracking-tight text-sm md:text-base shrink-0">POSS ATELIER</a>
        <nav class="hidden md:flex items-center gap-6 lg:gap-8 text-sm font-medium">
            <a href="{{ route('home') }}" class="nav-link hover:text-black">Inicio</a>
            <a href="{{ route('store.catalog') }}" class="nav-link hover:text-black {{ request()->routeIs('store.catalog') ? '!text-black' : '' }}">Tienda</a>
            @auth
                @if (auth()->user()->isCliente())
                    <a href="{{ route('store.cart') }}" class="nav-link hover:text-black {{ request()->routeIs('store.cart') ? '!text-black' : '' }}">
                        Carrito<span id="cart-count-badge"{{ ($cartCount ?? 0) < 1 ? ' style="display:none"' : '' }}> ({{ $cartCount ?? 0 }})</span>
                    </a>
                @endif
            @endauth
        </nav>
        <div class="flex items-center gap-2 md:gap-3 shrink-0">
            @auth
                @if (auth()->user()->isCliente())
                    <a href="{{ route('store.cart') }}" class="pill-link md:hidden text-sm px-3 py-2 rounded-full border transition">Carrito</a>
                    <form method="POST" action="{{ route('client.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="pill-link text-sm px-4 py-2 rounded-full border transition">Salir</button>
                    </form>
                @elseif (auth()->user()->isStaff())
                    <a href="{{ route('dashboard') }}" class="pill-link text-sm px-4 py-2 rounded-full border transition">Panel</a>
                @endif
            @else
                <a href="{{ route('client.login') }}" class="pill-link text-sm px-4 py-2 rounded-full border transition">Login</a>
                <a href="{{ route('client.register') }}" class="pill-link hidden sm:inline text-sm px-4 py-2 rounded-full border transition">Registro</a>
            @endauth
            @unless (request()->routeIs('store.catalog'))
                <a href="{{ route('store.catalog') }}" class="pill-link text-sm px-4 py-2 rounded-full border transition">Comprar</a>
            @endunless
        </div>
    </div>
</header>
