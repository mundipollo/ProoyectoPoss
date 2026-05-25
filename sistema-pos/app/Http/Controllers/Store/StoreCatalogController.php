<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreCatalogController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand'])
            ->where('estado', 'activo')
            ->orderBy('nombre');

        if ($request->filled('categoria')) {
            $query->whereHas('category', fn ($q) => $q->where('nombre', $request->string('categoria')));
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', $term)
                    ->orWhere('sku', 'like', $term);
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('nombre')->pluck('nombre');

        return view('store.catalog', [
            'products' => $products,
            'categories' => $categories,
            'cartCount' => $this->cart->count(),
        ]);
    }
}
