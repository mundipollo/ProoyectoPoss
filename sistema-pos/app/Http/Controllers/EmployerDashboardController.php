<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EmployerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        abort_unless($user?->hasRole('vendedor') || $user?->hasRole('empleador'), 403);

        $ventasHoy   = $this->ventasHoy();
        $ventasMes   = $this->ventasMes();
        $recientes   = $this->ventasRecientes();
        $porDia      = $this->ventasPorDia();

        return view('employer.dashboard', compact('ventasHoy', 'ventasMes', 'recientes', 'porDia'));
    }

    // ── Hoy ────────────────────────────────────────────────────────
    private function ventasHoy(): array
    {
        if (! Schema::hasTable('sales')) {
            return ['cantidad' => 0, 'total' => 0];
        }

        $q = DB::table('sales')
            ->where('estado', 'pagada')
            ->whereDate('fecha', Carbon::today());

        return [
            'cantidad' => (int)  $q->count(),
            'total'    => (float) $q->sum('total'),
        ];
    }

    // ── Mes actual ─────────────────────────────────────────────────
    private function ventasMes(): array
    {
        if (! Schema::hasTable('sales')) {
            return ['cantidad' => 0, 'total' => 0];
        }

        $q = DB::table('sales')
            ->where('estado', 'pagada')
            ->whereBetween('fecha', [
                Carbon::now()->startOfMonth(),
                Carbon::now(),
            ]);

        return [
            'cantidad' => (int)  $q->count(),
            'total'    => (float) $q->sum('total'),
        ];
    }

    // ── 10 ventas más recientes ─────────────────────────────────────
    private function ventasRecientes(): array
    {
        if (! Schema::hasTable('sales')) {
            return [];
        }

        return DB::table('sales as s')
            ->leftJoin('users as u', 'u.id', '=', 's.user_id')
            ->select(
                's.numero_venta',
                's.fecha',
                's.total',
                's.estado',
                DB::raw("
                    CASE
                        WHEN s.observaciones LIKE 'Venta POS - %'
                            THEN TRIM(SUBSTRING(s.observaciones FROM 13))
                        WHEN s.observaciones = 'Venta POS' THEN 'Mostrador'
                        ELSE COALESCE(u.name, 'Cliente')
                    END as vendedor
                "),
                DB::raw("(SELECT metodo FROM sale_payments WHERE sale_id = s.id ORDER BY id DESC LIMIT 1) as metodo")
            )
            ->orderByDesc('s.fecha')
            ->limit(10)
            ->get()
            ->map(fn ($r) => [
                'numero_venta' => $r->numero_venta,
                'fecha'        => $r->fecha,
                'total'        => (float) $r->total,
                'estado'       => $r->estado,
                'vendedor'     => $r->vendedor,
                'metodo'       => $r->metodo ?? '—',
            ])
            ->all();
    }

    // ── Ventas por día (últimos 7 días) ────────────────────────────
    private function ventasPorDia(): array
    {
        if (! Schema::hasTable('sales')) {
            return [];
        }

        $rows = DB::table('sales')
            ->where('estado', 'pagada')
            ->whereBetween('fecha', [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now(),
            ])
            ->selectRaw('DATE(fecha) as dia, COUNT(*) as cantidad, SUM(total) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->keyBy('dia');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->locale('es')->isoFormat('ddd D');
            $row   = $rows->get($date);
            $result[] = [
                'dia'      => $label,
                'cantidad' => $row ? (int)  $row->cantidad : 0,
                'total'    => $row ? (float) $row->total   : 0,
            ];
        }

        return $result;
    }
}
