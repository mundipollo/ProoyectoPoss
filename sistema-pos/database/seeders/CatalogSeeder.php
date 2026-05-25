<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->upsert([
            ['nombre' => 'Camisetas', 'descripcion' => 'Camisetas de temporada'],
            ['nombre' => 'Pantalones', 'descripcion' => 'Jeans y pantalones casuales'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Complementos y accesorios'],
        ], ['nombre'], ['descripcion']);

        Brand::query()->upsert([
            ['nombre' => 'Marca propia'],
            ['nombre' => 'Urbana'],
            ['nombre' => 'Classic Wear'],
        ], ['nombre'], ['nombre']);
    }
}
