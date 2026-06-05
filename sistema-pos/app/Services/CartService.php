<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'store_cart';

    /**
     * Devuelve los items del carrito.
     * Formato: { product_id => { qty: N, talla: 'M'|null } }
     */
    public function items(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return (int) collect($this->items())->sum(fn ($i) => $this->qty($i));
    }

    public function add(Product $product, int $quantity = 1, ?string $talla = null): void
    {
        $cart    = $this->items();
        $current = $cart[$product->id] ?? ['qty' => 0, 'talla' => null];

        $cart[$product->id] = [
            'qty'   => $this->qty($current) + max(1, $quantity),
            'talla' => $talla ?? $this->talla($current),
        ];

        session([self::SESSION_KEY => $cart]);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->items();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $current = $cart[$productId] ?? ['qty' => 0, 'talla' => null];
            $cart[$productId] = [
                'qty'   => $quantity,
                'talla' => $this->talla($current),
            ];
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[$productId]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @return Collection<int, object{product: Product, quantity: int, talla: ?string, subtotal: float}>
     */
    public function lines(): Collection
    {
        $ids = array_keys($this->items());

        if ($ids === []) {
            return collect();
        }

        $products = Product::with('category')
            ->whereIn('id', $ids)
            ->where('estado', 'activo')
            ->get()
            ->keyBy('id');

        return collect($this->items())
            ->map(function ($item, int $productId) use ($products) {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $qty = $this->qty($item);

                return (object) [
                    'product'  => $product,
                    'quantity' => $qty,
                    'talla'    => $this->talla($item),
                    'subtotal' => (float) $product->precio * $qty,
                ];
            })
            ->filter()
            ->values();
    }

    public function total(): float
    {
        return (float) $this->lines()->sum('subtotal');
    }

    // ── Helpers internos ──────────────────────────────────────────────────
    private function qty(mixed $item): int
    {
        return is_array($item) ? (int) ($item['qty'] ?? 0) : (int) $item;
    }

    private function talla(mixed $item): ?string
    {
        return is_array($item) ? ($item['talla'] ?? null) : null;
    }
}
