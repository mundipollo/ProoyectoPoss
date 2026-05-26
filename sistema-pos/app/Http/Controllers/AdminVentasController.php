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
        $estado = $request->input('estado', '');
        $metodo = $request->input('metodo', '');

        // ── Consulta principal ──────────────────────────────────────────
        // Usamos subqueries en el SELECT para metodo y num_productos,
        // evitando conflictos de LEFT JOIN con los filtros WHERE.
        $query = DB::table('sales as s')
            ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('users as u',     'u.id', '=', 's.user_id')
            ->select(
                's.id',
                's.numero_venta',
                's.fecha',
                's.subtotal',
                's.impuesto',
                's.total',
                's.estado',
                's.observaciones',
                DB::raw("COALESCE(c.nombre, u.name, 'Cliente') as cliente"),
                DB::raw("COALESCE(u.email, c.email, '') as email"),
                DB::raw("(SELECT metodo FROM sale_payments WHERE sale_id = s.id ORDER BY id DESC LIMIT 1) as metodo"),
                DB::raw("(SELECT COUNT(*) FROM sale_items WHERE sale_id = s.id) as num_productos")
            )
            ->orderByDesc('s.fecha');

        // ── Filtro por texto ────────────────────────────────────────────
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('s.numero_venta', 'like', "%{$q}%")
                    ->orWhere('c.nombre',     'like', "%{$q}%")
                    ->orWhere('u.name',       'like', "%{$q}%")
                    ->orWhere('u.email',      'like', "%{$q}%");
            });
        }

        // ── Filtro por estado ───────────────────────────────────────────
        if ($estado !== '') {
            $query->where('s.estado', $estado);
        }

        // ── Filtro por método de pago (subquery EXISTS) ─────────────────
        if ($metodo !== '') {
            $query->whereExists(function ($sub) use ($metodo) {
                $sub->select(DB::raw(1))
                    ->from('sale_payments')
                    ->whereColumn('sale_id', 's.id')
                    ->where('metodo', $metodo);
            });
        }

        $ventas = $query->paginate(20)->appends($request->query());

        // ── Resumen de tarjetas (siempre sobre ventas pagadas) ──────────
        $totalesBase = DB::table('sales')->where('estado', 'pagada');

        if ($q !== '') {
            $totalesBase->where(function ($sub) use ($q) {
                $ids = DB::table('users')->where('name', 'like', "%{$q}%")->pluck('id');
                $sub->where('numero_venta', 'like', "%{$q}%")
                    ->orWhereIn('user_id', $ids);
            });
        }

        if ($metodo !== '') {
            $totalesBase->whereExists(function ($sub) use ($metodo) {
                $sub->select(DB::raw(1))
                    ->from('sale_payments')
                    ->whereColumn('sale_id', 'sales.id')
                    ->where('metodo', $metodo);
            });
        }

        $resumen = [
            'total_ventas'   => $totalesBase->count(),
            'total_ingresos' => (float) $totalesBase->sum('total'),
        ];

        return view('admin.ventas.index', compact('ventas', 'q', 'estado', 'metodo', 'resumen'));
    }

    public function show(int $id): View
    {
        $venta = DB::table('sales as s')
            ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('users as u',     'u.id', '=', 's.user_id')
            ->leftJoin('sale_payments as sp', 'sp.sale_id', '=', 's.id')
            ->select(
                's.*',
                DB::raw("COALESCE(c.nombre, u.name, 'Cliente') as cliente"),
                DB::raw("COALESCE(u.email, c.email, '') as email"),
                DB::raw("COALESCE(sp.metodo, '—') as metodo"),
                'sp.monto as pago_monto'
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
