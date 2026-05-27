<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EmployerPosController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        abort_unless($user?->isStaff(), 403);

        $products  = $this->products();
        $customers = $this->customers();

        return view('employer.pos', compact('products', 'customers'));
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
                'id'           => (int) $row->id,
                'sku'          => $row->sku,
                'nombre'       => $row->nombre,
                'precio'       => (float) $row->precio,
                'stock_actual' => (int) $row->stock_actual,
            ])
            ->all();
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
                'id'               => (int) $row->id,
                'nombre'           => $row->nombre,
                'numero_documento' => $row->numero_documento,
                'telefono'         => $row->telefono,
            ])
            ->all();
    }
}
