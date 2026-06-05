<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminVentasController extends Controller
{
    public function index(Request $request): View
    {
        $q      = trim($request->input('q', ''));
        $metodo = $request->input('metodo', '');

        // ── Query principal SIN subqueries correlacionados en SELECT ────
        // Los subqueries en SELECT (metodo, num_productos) se cargaban
        // en conflicto con los bindings del whereRaw de búsqueda.
        // Se cargan por separado después de paginar.
        $query = DB::table('sales as s')
            ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('users as u',     'u.id', '=', 's.user_id')
            ->select(
                's.id',
                's.numero_venta',
                's.fecha',
                's.total',
                's.estado',
                's.observaciones',
                DB::raw("
                    CASE
                        WHEN c.nombre IS NOT NULL THEN c.nombre
                        WHEN s.observaciones LIKE 'Venta POS - %'
                            THEN TRIM(SUBSTRING(s.observaciones FROM 13))
                        WHEN s.observaciones = 'Venta POS' THEN 'Mostrador'
                        ELSE COALESCE(u.name, 'Cliente')
                    END AS cliente
                "),
                DB::raw("COALESCE(u.email, c.email, '') AS email")
            )
            ->orderByDesc('s.fecha');

        // ── Búsqueda por nombre / número / correo ───────────────────────
        if ($q !== '') {
            $like = '%' . $q . '%';
            $query->whereRaw(
                "(s.numero_venta LIKE ?
                  OR IFNULL(c.nombre,'') LIKE ?
                  OR IFNULL(u.name,'') LIKE ?
                  OR IFNULL(u.email,'') LIKE ?
                  OR IFNULL(s.observaciones,'') LIKE ?)",
                [$like, $like, $like, $like, $like]
            );
        }

        // ── Filtro por método de pago ───────────────────────────────────
        if ($metodo !== '') {
            $query->whereExists(function ($sub) use ($metodo) {
                $sub->select(DB::raw(1))
                    ->from('sale_payments')
                    ->whereColumn('sale_id', 's.id')
                    ->where('metodo', $metodo);
            });
        }

        // ── Paginación ──────────────────────────────────────────────────
        $ventas = $query->paginate(20)->appends($request->query());

        // ── Enriquecer con metodo y num_productos (batch, sin subqueries)
        $pageIds = $ventas->pluck('id')->filter()->toArray();

        $metodosMap  = [];
        $numProdMap  = [];

        if (! empty($pageIds)) {
            // Último método de pago por venta
            $metodosMap = DB::table('sale_payments')
                ->whereIn('sale_id', $pageIds)
                ->select('sale_id', DB::raw('MAX(metodo) as metodo'))
                ->groupBy('sale_id')
                ->pluck('metodo', 'sale_id')
                ->toArray();

            // Cantidad de ítems por venta
            $numProdMap = DB::table('sale_items')
                ->whereIn('sale_id', $pageIds)
                ->select('sale_id', DB::raw('COUNT(*) as num'))
                ->groupBy('sale_id')
                ->pluck('num', 'sale_id')
                ->toArray();
        }

        // Inyectar datos en los ítems del paginator
        $ventas->getCollection()->transform(function ($item) use ($metodosMap, $numProdMap) {
            $item->metodo        = $metodosMap[$item->id] ?? null;
            $item->num_productos = (int) ($numProdMap[$item->id] ?? 0);
            return $item;
        });

        // ── Resumen: queries completamente independientes ────────────────
        $resumen = [
            'total_ventas'   => (int)   DB::table('sales')->where('estado', 'pagada')->count(),
            'total_ingresos' => (float) DB::table('sales')->where('estado', 'pagada')->sum('total'),
        ];

        return view('admin.ventas.index', compact('ventas', 'q', 'metodo', 'resumen'));
    }

    public function show(int $id): View
    {
        $venta = DB::table('sales as s')
            ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('users as u',     'u.id', '=', 's.user_id')
            ->leftJoin('sale_payments as sp', 'sp.sale_id', '=', 's.id')
            ->select(
                's.*',
                DB::raw("
                    CASE
                        WHEN c.nombre IS NOT NULL THEN c.nombre
                        WHEN s.observaciones LIKE 'Venta POS - %'
                            THEN TRIM(SUBSTRING(s.observaciones FROM 13))
                        WHEN s.observaciones = 'Venta POS' THEN 'Mostrador'
                        ELSE COALESCE(u.name, 'Cliente')
                    END AS cliente
                "),
                DB::raw("COALESCE(u.email, c.email, '') AS email"),
                DB::raw("COALESCE(sp.metodo, '—') AS metodo"),
                'sp.monto AS pago_monto'
            )
            ->where('s.id', $id)
            ->first();

        abort_if(! $venta, 404);

        $items = DB::table('sale_items as si')
            ->leftJoin('products as p', 'p.id', '=', 'si.product_id')
            ->select('si.*', 'p.sku')
            ->where('si.sale_id', $id)
            ->get();

        return view('admin.ventas.show', compact('venta', 'items'));
    }
}
