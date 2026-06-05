<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $q         = trim($request->input('q', ''));
        $genero    = $request->input('genero', '');
        $categoria = $request->input('categoria', '');

        $categories = Category::orderBy('nombre')->pluck('nombre');

        $products = Product::with(['category', 'brand'])
            ->when($q !== '', function ($query) use ($q) {
                $like = '%' . $q . '%';
                $query->where(function ($sub) use ($like) {
                    $sub->where('nombre', 'like', $like)
                        ->orWhere('sku',    'like', $like);
                });
            })
            ->when(in_array($genero, ['hombre', 'mujer']), function ($query) use ($genero) {
                $query->whereIn('genero', [$genero, 'unisex']);
            })
            ->when($categoria !== '', function ($query) use ($categoria) {
                $query->whereHas('category', fn ($q) => $q->where('nombre', $categoria));
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('products.index', compact('products', 'q', 'genero', 'categoria', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('nombre')->get();
        $brands     = Brand::orderBy('nombre')->get();

        return view('products.create', compact('categories', 'brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku'          => ['required', 'string', 'max:50', 'unique:products,sku'],
            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['nullable', 'string'],
            'imagen'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'category_id'  => ['required', 'exists:categories,id'],
            'brand_id'     => ['nullable', 'exists:brands,id'],
            'costo'        => ['required', 'numeric', 'min:0'],
            'precio'       => ['required', 'numeric', 'min:0'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'estado'       => ['required', 'in:activo,inactivo'],
            'genero'       => ['nullable', 'in:hombre,mujer,unisex'],
            'tallas'       => ['nullable', 'array'],
            'tallas.*'     => ['in:XS,S,M,L,XL'],
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('products', 'public');
        }

        $validated['genero'] = $validated['genero'] ?? 'unisex';
        // Si no se envían tallas (ej. accesorios), guardar null
        $validated['tallas'] = !empty($validated['tallas']) ? $validated['tallas'] : null;

        Product::create($validated);
        Cache::forget('pos_products');

        return redirect()
            ->route('products.index')
            ->with('status', 'Producto creado correctamente.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('nombre')->get();
        $brands     = Brand::orderBy('nombre')->get();

        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'sku'          => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['nullable', 'string'],
            'imagen'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'category_id'  => ['required', 'exists:categories,id'],
            'brand_id'     => ['nullable', 'exists:brands,id'],
            'costo'        => ['required', 'numeric', 'min:0'],
            'precio'       => ['required', 'numeric', 'min:0'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'estado'       => ['required', 'in:activo,inactivo'],
            'genero'       => ['nullable', 'in:hombre,mujer,unisex'],
            'tallas'       => ['nullable', 'array'],
            'tallas.*'     => ['in:XS,S,M,L,XL'],
        ]);

        $validated['genero'] = $validated['genero'] ?? 'unisex';
        $validated['tallas'] = !empty($validated['tallas']) ? $validated['tallas'] : null;

        if ($request->hasFile('imagen')) {
            // Borrar imagen anterior si existe
            if ($product->imagen) {
                Storage::disk('public')->delete($product->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('products', 'public');
        }

        // Si se marcó "quitar imagen"
        if ($request->boolean('quitar_imagen') && $product->imagen) {
            Storage::disk('public')->delete($product->imagen);
            $validated['imagen'] = null;
        }

        $product->update($validated);
        Cache::forget('pos_products');

        return redirect()
            ->route('products.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->imagen) {
            Storage::disk('public')->delete($product->imagen);
        }

        $product->delete();
        Cache::forget('pos_products');

        return redirect()
            ->route('products.index')
            ->with('status', 'Producto eliminado correctamente.');
    }
}
