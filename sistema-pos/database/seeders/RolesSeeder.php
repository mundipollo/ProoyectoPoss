<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->upsert([
            ['nombre' => 'admin', 'descripcion' => 'Control total del sistema'],
            ['nombre' => 'vendedor', 'descripcion' => 'Puede registrar ventas y consultar inventario'],
            ['nombre' => 'consulta', 'descripcion' => 'Solo consulta de inventario'],
            ['nombre' => 'cliente', 'descripcion' => 'Comprador en la tienda en línea'],
        ], ['nombre'], ['descripcion']);
    }
}
