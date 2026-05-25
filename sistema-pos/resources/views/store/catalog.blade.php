@extends('store.layout')

@section('title', 'Tienda')

@section('content')
    <section class="store-hero">
        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2000" alt="Tienda Poss Atelier" class="store-hero-bg">
        <div class="store-hero-overlay"></div>
        <div class="store-hero-content">
            @include('store.partials.hero-heading', [
                'title' => 'Tienda',
                'subtitle' => 'Explora todo nuestro catálogo de textiles y moda.',
            ])
        </div>
    </section>

    <section class="px-6 md:px-12 lg:px-20 py-12 md:py-16 reveal">
        <form method="GET" action="{{ route('store.catalog') }}" class="mb-10 flex flex-col md:flex-row gap-3 max-w-4xl">
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Buscar por nombre o SKU..."
                class="flex-1 rounded-full border border-neutral-300 px-5 py-3 text-sm bg-white focus:outline-none focus:border-neutral-900"
            >
            <select name="categoria" class="rounded-full border border-neutral-300 px-5 py-3 text-sm min-w-[200px] bg-white focus:outline-none focus:border-neutral-900">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(request('categoria') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            <button type="submit" class="pill-btn pill-btn-primary px-8">Filtrar</button>
        </form>

        @guest
            <div class="mb-8 p-4 md:p-5 rounded-2xl border border-neutral-200 bg-white/80 text-neutral-700 text-sm md:text-base max-w-3xl">
                <a href="{{ route('client.login') }}" class="font-medium text-neutral-900 underline underline-offset-2">Inicia sesión como cliente</a>
                o
                <a href="{{ route('client.register') }}" class="font-medium text-neutral-900 underline underline-offset-2">crea una cuenta</a>
                para agregar productos al carrito.
            </div>
        @endguest

        @auth
            @if (! auth()->user()->isCliente())
                <div class="mb-8 p-4 md:p-5 rounded-2xl border border-neutral-200 text-neutral-700 text-sm md:text-base max-w-3xl">
                    Tu sesión es de personal. Para comprar, cierra sesión y entra con una cuenta de cliente.
                </div>
            @endif
        @endauth

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <article class="scroll-card product-card flex flex-col">
                    <div class="thumb">
                        <img
                            src="{{ \App\Support\StoreCatalogImages::forCategory($product->category->nombre) }}"
                            alt="{{ $product->nombre }}"
                            loading="lazy"
                        >
                        <span class="thumb-label">{{ $product->category->nombre }}</span>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <p class="text-xs uppercase tracking-widest text-neutral-500 mb-1">{{ $product->sku }}</p>
                        <h2 class="font-medium text-lg leading-snug mb-1">{{ $product->nombre }}</h2>
                        @if ($product->brand)
                            <p class="text-sm text-neutral-500 mb-3">{{ $product->brand->nombre }}</p>
                        @endif
                        <p class="text-2xl font-medium tracking-tight mt-auto">${{ number_format($product->precio, 0, ',', '.') }} <span class="text-xs text-neutral-500 font-normal">COP</span></p>
                        <p class="text-xs text-neutral-500 mt-1">Stock: {{ $product->stock_actual }}</p>

                        <div class="mt-4 pt-4 border-t border-neutral-100">
                            @auth
                                @if (auth()->user()->isCliente())
                                    @if ($product->stock_actual > 0)
                                        <form method="POST" action="{{ route('store.cart.add', $product) }}" class="flex gap-2 js-add-form">
                                            @csrf
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_actual }}" class="w-14 rounded-full border border-neutral-300 px-2 py-2 text-sm text-center">
                                            <button type="submit" class="flex-1 pill-btn pill-btn-primary text-sm py-2">
                                                Agregar
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-sm text-neutral-500">Agotado</p>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('client.login') }}" class="block w-full text-center pill-btn text-sm py-2.5">
                                    Ingresar para comprar
                                </a>
                            @endauth
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center text-neutral-500 text-lg">
                    No hay productos disponibles con estos filtros.
                </div>
            @endforelse
        </div>

        <div class="mt-12 flex justify-center">
            {{ $products->links() }}
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function showToast(message, type) {
        const old = document.getElementById('ajax-toast');
        if (old) old.remove();

        const wrap = document.createElement('div');
        wrap.id = 'ajax-toast';
        wrap.style.cssText = 'position:fixed;top:6rem;left:50%;transform:translateX(-50%);z-index:9999;width:92%;max-width:28rem;transition:opacity .3s';

        const inner = document.createElement('div');
        inner.className = 'store-alert text-center';
        inner.style.color = type === 'error' ? '#991b1b' : '#166534';
        inner.textContent = message;

        wrap.appendChild(inner);
        document.body.appendChild(wrap);

        setTimeout(function () {
            wrap.style.opacity = '0';
            setTimeout(function () { wrap.remove(); }, 300);
        }, 3000);
    }

    function updateCartCount(count) {
        const badge = document.getElementById('cart-count-badge');
        if (!badge) return;
        if (count > 0) {
            badge.textContent = ' (' + count + ')';
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }
    }

    document.querySelectorAll('.js-add-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Agregando…';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: new URLSearchParams(new FormData(form)).toString(),
                });

                const data = await res.json();

                if (res.ok) {
                    showToast(data.status || '¡Agregado con éxito!', 'success');
                    updateCartCount(data.cartCount);
                    const qty = form.querySelector('input[name="quantity"]');
                    if (qty) qty.value = 1;
                } else {
                    showToast(data.error || 'No se pudo agregar el producto.', 'error');
                }
            } catch (_) {
                showToast('Error de conexión. Intenta de nuevo.', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });
    });
});
</script>
@endpush
