<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'carrito');

        if (! in_array($tab, ['carrito', 'pago', 'confirmacion'], true)) {
            $tab = 'carrito';
        }

        if ($tab === 'pago' && $this->cart->lines()->isEmpty()) {
            $tab = 'carrito';
        }

        if ($tab === 'confirmacion' && ! session('store_last_order')) {
            $tab = $this->cart->lines()->isEmpty() ? 'carrito' : 'pago';
        }

        return view('store.cart', [
            'lines' => $this->cart->lines(),
            'total' => $this->cart->total(),
            'cartCount' => $this->cart->count(),
            'activeTab' => $tab,
            'lastOrder' => session('store_last_order'),
        ]);
    }

    public function pay(Request $request): RedirectResponse
    {
        $lines = $this->cart->lines();

        if ($lines->isEmpty()) {
            return redirect()
                ->route('store.cart')
                ->with('error', 'Tu carrito está vacío.');
        }

        $validated = $request->validate([
            'card_name' => ['required', 'string', 'max:120'],
            'card_number' => ['required', 'string', 'regex:/^[0-9\s]{13,19}$/'],
            'card_expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
            'card_cvv' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
            'payment_method' => ['required', 'in:tarjeta,pse,nequi'],
        ]);

        foreach ($lines as $line) {
            if ($line->quantity > $line->product->stock_actual) {
                return redirect()
                    ->route('store.cart', ['tab' => 'carrito'])
                    ->with('error', 'No hay stock suficiente para "'.$line->product->nombre.'".');
            }
        }

        $total     = $this->cart->total();
        $baseIva   = round($total / 1.19, 2);
        $iva       = round($total - $baseIva, 2);
        $reference = 'PA-'.strtoupper(Str::random(8));
        $userId    = auth()->id();
        $now       = now();

        DB::transaction(function () use ($lines, $reference, $validated, $baseIva, $iva, $total, $userId, $now): void {
            foreach ($lines as $line) {
                Product::query()
                    ->whereKey($line->product->id)
                    ->decrement('stock_actual', $line->quantity);
            }

            $saleId = DB::table('sales')->insertGetId([
                'numero_venta'  => $reference,
                'customer_id'   => null,
                'user_id'       => $userId,
                'fecha'         => $now,
                'subtotal'      => $baseIva,
                'descuento'     => 0,
                'impuesto'      => $iva,
                'total'         => $total,
                'estado'        => 'pagada',
                'observaciones' => 'Venta web',
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            $items = [];
            foreach ($lines as $line) {
                $items[] = [
                    'sale_id'         => $saleId,
                    'product_id'      => $line->product->id,
                    'nombre'          => $line->product->nombre
                                         . ($line->talla ? ' (Talla '.$line->talla.')' : ''),
                    'cantidad'        => $line->quantity,
                    'precio_unitario' => $line->product->precio,
                    'subtotal'        => $line->subtotal,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
            DB::table('sale_items')->insert($items);

            DB::table('sale_payments')->insert([
                'sale_id'    => $saleId,
                'metodo'     => $validated['payment_method'],
                'monto'      => $total,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        session([
            'store_last_order' => [
                'reference' => $reference,
                'client'    => auth()->user()->name,
                'lines'     => $lines->map(fn ($l) => [
                    'nombre'   => $l->product->nombre,
                    'sku'      => $l->product->sku,
                    'cantidad' => $l->quantity,
                    'precio'   => $l->product->precio,
                    'subtotal' => $l->subtotal,
                ])->values()->all(),
                'base_iva'  => $baseIva,
                'iva'       => $iva,
                'total'     => $total,
                'method'    => $validated['payment_method'],
                'paid_at'   => $now->format('d/m/Y H:i'),
            ],
        ]);

        $this->cart->clear();

        return redirect()
            ->route('store.cart', ['tab' => 'confirmacion'])
            ->with('status', 'Pago simulado completado correctamente.');
    }

    /** Categorías que NO necesitan talla */
    private const SIN_TALLA = ['Accesorios'];

    public function add(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        if ($product->estado !== 'activo' || $product->stock_actual < 1) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Este producto no está disponible.'], 422);
            }

            return back()->with('error', 'Este producto no está disponible.');
        }

        // Validar talla: solo si el producto tiene tallas configuradas en BD
        $categoriaNombre = $product->category?->nombre ?? '';
        $esPrenda        = ! in_array($categoriaNombre, self::SIN_TALLA, true);
        $tallasProd      = $product->tallas ?? [];
        if (is_string($tallasProd)) $tallasProd = json_decode($tallasProd, true) ?? [];
        $necesitaTalla   = $esPrenda && count($tallasProd) > 0;
        $talla           = $request->input('talla');

        if ($necesitaTalla && empty($talla)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Por favor selecciona una talla antes de agregar.'], 422);
            }
            return back()->with('error', 'Por favor selecciona una talla antes de agregar.');
        }

        if ($talla && ! in_array($talla, ['XS','S','M','L','XL'], true)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Talla no válida.'], 422);
            }
            return back()->with('error', 'Talla no válida.');
        }

        $quantity = (int) $request->input('quantity', 1);
        $current  = $this->cart->items()[$product->id] ?? ['qty' => 0];
        $inCart   = (is_array($current) ? ($current['qty'] ?? 0) : (int) $current) + max(1, $quantity);

        if ($inCart > $product->stock_actual) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No hay suficiente stock disponible.'], 422);
            }

            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        $this->cart->add($product, $quantity, $talla ?: null);
        session()->forget('store_last_order');

        if ($request->expectsJson()) {
            return response()->json([
                'status'    => '¡Producto agregado al carrito!',
                'cartCount' => $this->cart->count(),
            ]);
        }

        return back()->with('status', 'Producto agregado al carrito.');
    }

    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity > $product->stock_actual) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'La cantidad supera el stock disponible.'], 422);
            }

            return back()->with('error', 'La cantidad supera el stock disponible.');
        }

        $this->cart->updateQuantity($product->id, $quantity);

        if ($request->expectsJson()) {
            return response()->json([
                'subtotal'  => $product->precio * $quantity,
                'total'     => $this->cart->total(),
                'cartCount' => $this->cart->count(),
            ]);
        }

        return redirect()->route('store.cart')->with('status', 'Carrito actualizado.');
    }

    public function remove(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $this->cart->remove($product->id);

        if ($request->expectsJson()) {
            return response()->json([
                'total'     => $this->cart->total(),
                'cartCount' => $this->cart->count(),
                'isEmpty'   => $this->cart->lines()->isEmpty(),
            ]);
        }

        return redirect()->route('store.cart')->with('status', 'Producto eliminado del carrito.');
    }
}
