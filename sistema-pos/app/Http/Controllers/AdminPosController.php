<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPosController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        $customers = $this->customers();
        $products = $this->products();

        return view('admin.pos', compact('customers', 'products'));
    }

    public function vender(Request $request): JsonResponse
    {
        abort_unless(Auth::user()?->isStaff(), 403);

        $validated = $request->validate([
            'items'          => ['required', 'array', 'min:1'],
            'items.*.id'     => ['required', 'integer', 'exists:products,id'],
            'items.*.nombre' => ['required', 'string'],
            'items.*.cantidad'=> ['required', 'integer', 'min:1'],
            'items.*.precio' => ['required', 'numeric', 'min:0'],
            'items.*.subtotal'=> ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:tarjeta,pse,nequi'],
            'cliente'        => ['nullable', 'string', 'max:150'],
        ]);

        $items   = collect($validated['items']);
        $total   = $items->sum('subtotal');
        $baseIva = round($total / 1.19, 2);
        $iva     = round($total - $baseIva, 2);
        $ref     = 'POS-' . strtoupper(Str::random(8));
        $now     = now();
        $userId  = Auth::id();

        // Verificar stock
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock_actual < $item['cantidad']) {
                return response()->json([
                    'error' => "Sin stock suficiente para \"{$item['nombre']}\"."
                ], 422);
            }
        }

        DB::transaction(function () use ($items, $ref, $validated, $baseIva, $iva, $total, $userId, $now): void {
            $saleId = DB::table('sales')->insertGetId([
                'numero_venta'   => $ref,
                'customer_id'    => null,
                'user_id'        => $userId,
                'fecha'          => $now,
                'subtotal'       => $baseIva,
                'descuento'      => 0,
                'impuesto'       => $iva,
                'total'          => $total,
                'estado'         => 'pagada',
                'observaciones'  => 'Venta POS' . ($validated['cliente'] ? ' - ' . $validated['cliente'] : ''),
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            $rows = [];
            foreach ($items as $item) {
                Product::whereKey($item['id'])->decrement('stock_actual', $item['cantidad']);
                $rows[] = [
                    'sale_id'         => $saleId,
                    'product_id'      => $item['id'],
                    'nombre'          => $item['nombre'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal'        => $item['subtotal'],
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
        });

        return response()->json([
            'reference'  => $ref,
            'cliente'    => $validated['cliente'] ?: 'Mostrador',
            'items'      => $items->values()->all(),
            'base_iva'   => $baseIva,
            'iva'        => $iva,
            'total'      => $total,
            'method'     => $validated['payment_method'],
            'paid_at'    => $now->format('d/m/Y H:i'),
        ]);
    }

    private function customers(): array
    {
        if (! Schema::hasTable('customers')) {
            return [];
        }

        return DB::table('customers')
            ->select('id', 'nombre', 'numero_documento', 'telefono')
            ->orderBy('nombre')
            ->limit(300)
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'nombre' => $row->nombre,
                'numero_documento' => $row->numero_documento,
                'telefono' => $row->telefono,
            ])
            ->all();
    }

    private function products(): array
    {
        if (! Schema::hasTable('products')) {
            return [];
        }

        return DB::table('products')
            ->select('id', 'sku', 'nombre', 'precio', 'stock_actual')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->limit(500)
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'sku' => $row->sku,
                'nombre' => $row->nombre,
                'precio' => (float) $row->precio,
                'stock_actual' => (int) $row->stock_actual,
            ])
            ->all();
    }
}
