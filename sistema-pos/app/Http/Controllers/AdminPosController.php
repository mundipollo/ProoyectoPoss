<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPosController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        $products = $this->products();

        // Versión liviana para el JSON inline del JS (sin stock_actual)
        $productsJs = array_map(
            fn ($p) => ['id' => $p['id'], 'nombre' => $p['nombre'], 'sku' => $p['sku'], 'precio' => $p['precio']],
            $products
        );

        return view('admin.pos', [
            'products'   => $products,
            'productsJs' => $productsJs,
            'customers'  => $this->customers(),
        ]);
    }

    public function vender(Request $request): JsonResponse
    {
        abort_unless(Auth::user()?->isStaff(), 403);

        $validated = $request->validate([
            'items'           => ['required', 'array', 'min:1'],
            'items.*.id'      => ['required', 'integer', 'exists:products,id'],
            'items.*.nombre'  => ['required', 'string'],
            'items.*.cantidad'=> ['required', 'integer', 'min:1'],
            'items.*.precio'  => ['required', 'numeric', 'min:0'],
            'items.*.subtotal'=> ['required', 'numeric', 'min:0'],
            'payment_method'  => ['required', 'in:tarjeta,pse,nequi'],
            'cliente'         => ['nullable', 'string', 'max:150'],
        ]);

        $items = collect($validated['items']);

        // ── Verificar stock con un solo batch query ────────────────────────
        $ids      = $items->pluck('id')->map(fn ($id) => (int) $id)->all();
        $stockMap = DB::table('products')
            ->whereIn('id', $ids)
            ->pluck('stock_actual', 'id')   // {id => stock_actual}
            ->map(fn ($s) => (int) $s);

        foreach ($items as $item) {
            $stock = $stockMap->get((int) $item['id']);
            if ($stock === null || $stock < (int) $item['cantidad']) {
                return response()->json(
                    ['error' => "Sin stock suficiente para \"{$item['nombre']}\"."],
                    422
                );
            }
        }

        $total   = $items->sum('subtotal');
        $baseIva = round($total / 1.19, 2);
        $iva     = round($total - $baseIva, 2);
        $ref     = 'POS-' . strtoupper(Str::random(8));
        $now     = now();
        $userId  = Auth::id();

        DB::transaction(function () use ($items, $ids, $ref, $validated, $baseIva, $iva, $total, $userId, $now): void {
            $saleId = DB::table('sales')->insertGetId([
                'numero_venta'  => $ref,
                'customer_id'   => null,
                'user_id'       => $userId,
                'fecha'         => $now,
                'subtotal'      => $baseIva,
                'descuento'     => 0,
                'impuesto'      => $iva,
                'total'         => $total,
                'estado'        => 'pagada',
                'observaciones' => 'Venta POS' . ($validated['cliente'] ? ' - ' . $validated['cliente'] : ''),
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            $rows = [];
            foreach ($items as $item) {
                // Decremento directo por ID — sin depender de colección Eloquent
                DB::table('products')
                    ->where('id', (int) $item['id'])
                    ->decrement('stock_actual', (int) $item['cantidad']);

                $rows[] = [
                    'sale_id'         => $saleId,
                    'product_id'      => (int) $item['id'],
                    'nombre'          => $item['nombre'],
                    'cantidad'        => (int) $item['cantidad'],
                    'precio_unitario' => (float) $item['precio'],
                    'subtotal'        => (float) $item['subtotal'],
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }

            DB::table('sale_items')->insert($rows);

            DB::table('sale_payments')->insert([
                'sale_id'    => $saleId,
                'metodo'     => $validated['payment_method'],
                'monto'      => $total,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            Cache::forget('pos_products');
        });

        return response()->json([
            'reference' => $ref,
            'cliente'   => $validated['cliente'] ?: 'Mostrador',
            'items'     => $items->values()->all(),
            'base_iva'  => $baseIva,
            'iva'       => $iva,
            'total'     => $total,
            'method'    => $validated['payment_method'],
            'paid_at'   => $now->format('d/m/Y H:i'),
        ]);
    }

    // ── Productos: cacheados 5 min, solo con stock disponible ─────────────
    private function products(): array
    {
        return Cache::remember('pos_products', 300, function () {
            return DB::table('products')
                ->select('id', 'sku', 'nombre', 'precio', 'stock_actual')
                ->where('estado', 'activo')
                ->where('stock_actual', '>', 0)   // solo vendibles en POS
                ->orderBy('nombre')
                ->limit(300)                       // reducido de 500 → 300
                ->get()
                ->map(fn ($r) => [
                    'id'           => (int) $r->id,
                    'sku'          => $r->sku,
                    'nombre'       => $r->nombre,
                    'precio'       => (float) $r->precio,
                    'stock_actual' => (int) $r->stock_actual,
                ])
                ->all();
        });
    }

    // ── Clientes: cacheados 10 min ─────────────────────────────────────────
    private function customers(): array
    {
        return Cache::remember('pos_customers', 600, function () {
            return DB::table('customers')
                ->select('id', 'nombre', 'numero_documento')
                ->orderBy('nombre')
                ->limit(300)
                ->get()
                ->map(fn ($r) => [
                    'id'               => (int) $r->id,
                    'nombre'           => $r->nombre,
                    'numero_documento' => $r->numero_documento,
                ])
                ->all();
        });
    }
}
