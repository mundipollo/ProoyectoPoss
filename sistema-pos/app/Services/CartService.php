<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'store_cart';

    /**
     * @return array<int, int> product_id => quantity
     */
    public function items(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return (int) array_sum($this->items());
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->items();
        $cart[$product->id] = ($cart[$product->id] ?? 0) + max(1, $quantity);
        session([self::SESSION_KEY => $cart]);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->items();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
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
     * @return Collection<int, object{product: Product, quantity: int, subtotal: float}>
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
            ->map(function (int $quantity, int $productId) use ($products) {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                return (object) [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => (float) $product->precio * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function total(): float
    {
        return (float) $this->lines()->sum('subtotal');
    }
}
