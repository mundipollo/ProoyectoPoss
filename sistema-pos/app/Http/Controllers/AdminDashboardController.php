<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        $stats = [
            'products'   => $this->safeCount('products'),
            'categories' => $this->safeCount('categories'),
            'brands'     => $this->safeCount('brands'),
            'customers'  => $this->countClientes(),
            'sales'      => $this->safeCount('sales'),
        ];

        $sales = $this->salesSummary();
        $topProducts = $this->topProducts();
        $lowStockProducts = $this->lowStockProducts();
        $recentSales = $this->recentSales();
        $paymentMethods = $this->paymentMethodsSummary();

        return view(
            'admin.dashboard',
            compact('stats', 'sales', 'topProducts', 'lowStockProducts', 'recentSales', 'paymentMethods')
        );
    }

    private function safeCount(string $table): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        return DB::table($table)->count();
    }

    private function salesSummary(): array
    {
        if (! Schema::hasTable('sales')) {
            return [
                'today_total' => 0,
                'month_total' => 0,
                'ticket_promedio_hoy' => 0,
                'ventas_hoy' => 0,
            ];
        }

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $todayQuery = DB::table('sales')
            ->where('estado', 'pagada')
            ->whereDate('fecha', $today);
        $monthQuery = DB::table('sales')
            ->where('estado', 'pagada')
            ->whereBetween('fecha', [$monthStart, Carbon::now()]);

        $ventasHoy = (int) $todayQuery->count();
        $totalHoy = (float) $todayQuery->sum('total');
        $totalMes = (float) $monthQuery->sum('total');

        return [
            'today_total' => $totalHoy,
            'month_total' => $totalMes,
            'ticket_promedio_hoy' => $ventasHoy > 0 ? $totalHoy / $ventasHoy : 0,
            'ventas_hoy' => $ventasHoy,
        ];
    }

    private function countClientes(): int
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return 0;
        }

        return DB::table('users')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.nombre', 'cliente')
            ->count();
    }

    private function topProducts(): array
    {
        if (! Schema::hasTable('sale_items') || ! Schema::hasTable('products')) {
            return [];
        }

        return DB::table('sale_items as si')
            ->join('sales as s', 's.id', '=', 'si.sale_id')
            ->join('products as p', 'p.id', '=', 'si.product_id')
            ->where('s.estado', 'pagada')
            ->select('p.nombre', DB::raw('SUM(si.cantidad) as unidades_vendidas'), DB::raw('SUM(si.subtotal) as total_vendido'))
            ->groupBy('p.id', 'p.nombre')
            ->orderByDesc('unidades_vendidas')
            ->limit(7)
            ->get()
            ->map(fn ($row) => [
                'nombre'           => $row->nombre,
                'unidades_vendidas' => (int) $row->unidades_vendidas,
                'total_vendido'    => (float) $row->total_vendido,
            ])
            ->all();
    }

    private function lowStockProducts(): array
    {
        if (! Schema::hasTable('products')) {
            return [];
        }

        return DB::table('products')
            ->select('sku', 'nombre', 'stock_actual', 'stock_minimo')
            ->where('estado', 'activo')
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'sku' => $row->sku,
                'nombre' => $row->nombre,
                'stock_actual' => (int) $row->stock_actual,
                'stock_minimo' => (int) $row->stock_minimo,
            ])
            ->all();
    }

    private function recentSales(): array
    {
        if (! Schema::hasTable('sales')) {
            return [];
        }

        return DB::table('sales as s')
            ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('users as u', 'u.id', '=', 's.user_id')
            ->select('s.numero_venta', 's.fecha', 's.total', 's.estado', DB::raw('COALESCE(c.nombre, u.name, \'Cliente\') as cliente'))
            ->orderByDesc('s.fecha')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'numero_venta' => $row->numero_venta,
                'fecha'        => $row->fecha,
                'total'        => (float) $row->total,
                'estado'       => $row->estado,
                'cliente'      => $row->cliente,
            ])
            ->all();
    }

    private function paymentMethodsSummary(): array
    {
        if (! Schema::hasTable('sale_payments')) {
            return [];
        }

        return DB::table('sale_payments')
            ->select('metodo', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(monto) as total'))
            ->groupBy('metodo')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'metodo' => $row->metodo,
                'cantidad' => (int) $row->cantidad,
                'total' => (float) $row->total,
            ])
            ->all();
    }
}
