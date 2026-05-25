<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * @var array<string, string>
     */
    private array $extraCategories = [
        'Vestidos' => 'Vestidos, faldas y enterizos',
        'Chaquetas' => 'Chaquetas, buzos y abrigos',
        'Ropa deportiva' => 'Leggings, shorts y prendas deportivas',
    ];

    /**
     * @return array<int, array{sku: string, nombre: string, categoria: string, marca: string, precio: float, stock: int, estado: string}>
     */
    private function products(): array
    {
        return [
            ['sku' => 'CAM-001', 'nombre' => 'Camiseta básica algodón blanca', 'categoria' => 'Camisetas', 'marca' => 'Marca propia', 'precio' => 24900, 'stock' => 85, 'estado' => 'activo'],
            ['sku' => 'CAM-002', 'nombre' => 'Camiseta manga corta negra', 'categoria' => 'Camisetas', 'marca' => 'Urbana', 'precio' => 27900, 'stock' => 62, 'estado' => 'activo'],
            ['sku' => 'CAM-003', 'nombre' => 'Polo piqué azul marino', 'categoria' => 'Camisetas', 'marca' => 'Classic Wear', 'precio' => 35900, 'stock' => 40, 'estado' => 'activo'],
            ['sku' => 'CAM-004', 'nombre' => 'Camiseta oversize gris', 'categoria' => 'Camisetas', 'marca' => 'Urbana', 'precio' => 31900, 'stock' => 55, 'estado' => 'activo'],
            ['sku' => 'CAM-005', 'nombre' => 'Camiseta estampada floral', 'categoria' => 'Camisetas', 'marca' => 'Marca propia', 'precio' => 29900, 'stock' => 33, 'estado' => 'activo'],
            ['sku' => 'CAM-006', 'nombre' => 'Camiseta dry-fit deportiva', 'categoria' => 'Camisetas', 'marca' => 'Urbana', 'precio' => 38900, 'stock' => 48, 'estado' => 'activo'],
            ['sku' => 'CAM-007', 'nombre' => 'Camiseta cuello V burdeos', 'categoria' => 'Camisetas', 'marca' => 'Classic Wear', 'precio' => 26900, 'stock' => 27, 'estado' => 'activo'],
            ['sku' => 'CAM-009', 'nombre' => 'Camiseta tie-dye multicolor', 'categoria' => 'Camisetas', 'marca' => 'Urbana', 'precio' => 33900, 'stock' => 22, 'estado' => 'activo'],
            ['sku' => 'CAM-010', 'nombre' => 'Camiseta manga larga beige', 'categoria' => 'Camisetas', 'marca' => 'Classic Wear', 'precio' => 36900, 'stock' => 36, 'estado' => 'activo'],

            ['sku' => 'PAN-001', 'nombre' => 'Jean skinny azul oscuro', 'categoria' => 'Pantalones', 'marca' => 'Urbana', 'precio' => 89900, 'stock' => 44, 'estado' => 'activo'],
            ['sku' => 'PAN-002', 'nombre' => 'Pantalón chino caqui', 'categoria' => 'Pantalones', 'marca' => 'Classic Wear', 'precio' => 75900, 'stock' => 31, 'estado' => 'activo'],
            ['sku' => 'PAN-003', 'nombre' => 'Jogger algodón gris', 'categoria' => 'Pantalones', 'marca' => 'Marca propia', 'precio' => 64900, 'stock' => 58, 'estado' => 'activo'],
            ['sku' => 'PAN-004', 'nombre' => 'Jean mom fit deslavado', 'categoria' => 'Pantalones', 'marca' => 'Urbana', 'precio' => 94900, 'stock' => 26, 'estado' => 'activo'],
            ['sku' => 'PAN-005', 'nombre' => 'Pantalón wide leg negro', 'categoria' => 'Pantalones', 'marca' => 'Classic Wear', 'precio' => 82900, 'stock' => 18, 'estado' => 'activo'],
            ['sku' => 'PAN-006', 'nombre' => 'Bermuda denim claro', 'categoria' => 'Pantalones', 'marca' => 'Marca propia', 'precio' => 54900, 'stock' => 42, 'estado' => 'activo'],
            ['sku' => 'PAN-007', 'nombre' => 'Pantalón cargo verde oliva', 'categoria' => 'Pantalones', 'marca' => 'Urbana', 'precio' => 79900, 'stock' => 15, 'estado' => 'activo'],
            ['sku' => 'PAN-008', 'nombre' => 'Legging tiro alto negro', 'categoria' => 'Pantalones', 'marca' => 'Marca propia', 'precio' => 45900, 'stock' => 67, 'estado' => 'activo'],
            ['sku' => 'PAN-010', 'nombre' => 'Jean recto clásico', 'categoria' => 'Pantalones', 'marca' => 'Urbana', 'precio' => 87900, 'stock' => 39, 'estado' => 'activo'],

            ['sku' => 'VES-001', 'nombre' => 'Vestido midi lino terracota', 'categoria' => 'Vestidos', 'marca' => 'Classic Wear', 'precio' => 119900, 'stock' => 14, 'estado' => 'activo'],
            ['sku' => 'VES-002', 'nombre' => 'Vestido corto satinado negro', 'categoria' => 'Vestidos', 'marca' => 'Urbana', 'precio' => 99900, 'stock' => 21, 'estado' => 'activo'],
            ['sku' => 'VES-003', 'nombre' => 'Vestido camisero rayas', 'categoria' => 'Vestidos', 'marca' => 'Marca propia', 'precio' => 84900, 'stock' => 17, 'estado' => 'activo'],
            ['sku' => 'VES-004', 'nombre' => 'Falda plisada midi beige', 'categoria' => 'Vestidos', 'marca' => 'Classic Wear', 'precio' => 59900, 'stock' => 29, 'estado' => 'activo'],
            ['sku' => 'VES-005', 'nombre' => 'Vestido largo bohemio estampado', 'categoria' => 'Vestidos', 'marca' => 'Urbana', 'precio' => 129900, 'stock' => 9, 'estado' => 'activo'],
            ['sku' => 'VES-006', 'nombre' => 'Enterizo denim corto', 'categoria' => 'Vestidos', 'marca' => 'Marca propia', 'precio' => 109900, 'stock' => 11, 'estado' => 'activo'],
            ['sku' => 'VES-007', 'nombre' => 'Vestido tubo fiesta rojo', 'categoria' => 'Vestidos', 'marca' => 'Classic Wear', 'precio' => 94900, 'stock' => 8, 'estado' => 'activo'],
            ['sku' => 'VES-008', 'nombre' => 'Falda lápiz negra', 'categoria' => 'Vestidos', 'marca' => 'Urbana', 'precio' => 54900, 'stock' => 24, 'estado' => 'activo'],

            ['sku' => 'CHA-001', 'nombre' => 'Chaqueta jean oversize', 'categoria' => 'Chaquetas', 'marca' => 'Urbana', 'precio' => 139900, 'stock' => 20, 'estado' => 'activo'],
            ['sku' => 'CHA-002', 'nombre' => 'Buzo con capucha gris', 'categoria' => 'Chaquetas', 'marca' => 'Marca propia', 'precio' => 89900, 'stock' => 46, 'estado' => 'activo'],
            ['sku' => 'CHA-003', 'nombre' => 'Chaqueta bomber negra', 'categoria' => 'Chaquetas', 'marca' => 'Classic Wear', 'precio' => 149900, 'stock' => 13, 'estado' => 'activo'],
            ['sku' => 'CHA-004', 'nombre' => 'Cardigan lana crudo', 'categoria' => 'Chaquetas', 'marca' => 'Classic Wear', 'precio' => 99900, 'stock' => 16, 'estado' => 'activo'],
            ['sku' => 'CHA-005', 'nombre' => 'Chaqueta cortavientos deportiva', 'categoria' => 'Chaquetas', 'marca' => 'Urbana', 'precio' => 119900, 'stock' => 25, 'estado' => 'activo'],
            ['sku' => 'CHA-006', 'nombre' => 'Abrigo largo paño camel', 'categoria' => 'Chaquetas', 'marca' => 'Classic Wear', 'precio' => 249900, 'stock' => 6, 'estado' => 'activo'],
            ['sku' => 'CHA-007', 'nombre' => 'Chaleco acolchado ultraliviano', 'categoria' => 'Chaquetas', 'marca' => 'Marca propia', 'precio' => 109900, 'stock' => 18, 'estado' => 'activo'],
            ['sku' => 'DEP-001', 'nombre' => 'Top deportivo soporte medio', 'categoria' => 'Ropa deportiva', 'marca' => 'Urbana', 'precio' => 49900, 'stock' => 38, 'estado' => 'activo'],
            ['sku' => 'DEP-002', 'nombre' => 'Short running con bolsillo', 'categoria' => 'Ropa deportiva', 'marca' => 'Marca propia', 'precio' => 42900, 'stock' => 52, 'estado' => 'activo'],
            ['sku' => 'DEP-003', 'nombre' => 'Sudadera fleece media cremallera', 'categoria' => 'Ropa deportiva', 'marca' => 'Urbana', 'precio' => 79900, 'stock' => 30, 'estado' => 'activo'],
            ['sku' => 'DEP-004', 'nombre' => 'Pants deportivo reflectivo', 'categoria' => 'Ropa deportiva', 'marca' => 'Classic Wear', 'precio' => 69900, 'stock' => 23, 'estado' => 'activo'],
            ['sku' => 'DEP-005', 'nombre' => 'Camiseta compresión manga larga', 'categoria' => 'Ropa deportiva', 'marca' => 'Marca propia', 'precio' => 55900, 'stock' => 41, 'estado' => 'activo'],
            ['sku' => 'DEP-006', 'nombre' => 'Conjunto yoga top y legging', 'categoria' => 'Ropa deportiva', 'marca' => 'Urbana', 'precio' => 129900, 'stock' => 14, 'estado' => 'activo'],
            ['sku' => 'DEP-007', 'nombre' => 'Chaqueta rompevientos impermeable', 'categoria' => 'Ropa deportiva', 'marca' => 'Classic Wear', 'precio' => 149900, 'stock' => 10, 'estado' => 'activo'],

            ['sku' => 'ACC-001', 'nombre' => 'Bufanda tejida lana mixta', 'categoria' => 'Accesorios', 'marca' => 'Marca propia', 'precio' => 29900, 'stock' => 45, 'estado' => 'activo'],
            ['sku' => 'ACC-002', 'nombre' => 'Gorra trucker denim', 'categoria' => 'Accesorios', 'marca' => 'Urbana', 'precio' => 34900, 'stock' => 60, 'estado' => 'activo'],
            ['sku' => 'ACC-003', 'nombre' => 'Cinturón cuero sintético marrón', 'categoria' => 'Accesorios', 'marca' => 'Classic Wear', 'precio' => 39900, 'stock' => 34, 'estado' => 'activo'],
            ['sku' => 'ACC-004', 'nombre' => 'Medias pack x3 algodón', 'categoria' => 'Accesorios', 'marca' => 'Marca propia', 'precio' => 19900, 'stock' => 120, 'estado' => 'activo'],
            ['sku' => 'ACC-005', 'nombre' => 'Bolso tote lona estampada', 'categoria' => 'Accesorios', 'marca' => 'Urbana', 'precio' => 45900, 'stock' => 28, 'estado' => 'activo'],
            ['sku' => 'ACC-006', 'nombre' => 'Gorro beanie invierno', 'categoria' => 'Accesorios', 'marca' => 'Marca propia', 'precio' => 24900, 'stock' => 53, 'estado' => 'activo'],
            ['sku' => 'ACC-007', 'nombre' => 'Pañuelo seda estampado', 'categoria' => 'Accesorios', 'marca' => 'Classic Wear', 'precio' => 32900, 'stock' => 16, 'estado' => 'activo'],
            ['sku' => 'ACC-008', 'nombre' => 'Guantes touch pantalla', 'categoria' => 'Accesorios', 'marca' => 'Urbana', 'precio' => 27900, 'stock' => 37, 'estado' => 'activo'],
            ['sku' => 'ACC-009', 'nombre' => 'Riñonera urbana negra', 'categoria' => 'Accesorios', 'marca' => 'Urbana', 'precio' => 49900, 'stock' => 22, 'estado' => 'activo'],
            ['sku' => 'ACC-010', 'nombre' => 'Calcetines deportivos antiampolla', 'categoria' => 'Accesorios', 'marca' => 'Marca propia', 'precio' => 22900, 'stock' => 88, 'estado' => 'activo'],
        ];
    }

    public function run(): void
    {
        foreach ($this->extraCategories as $nombre => $descripcion) {
            Category::query()->firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $descripcion],
            );
        }

        $categories = Category::query()->pluck('id', 'nombre');
        $brands = Brand::query()->pluck('id', 'nombre');

        foreach ($this->products() as $item) {
            $categoryId = $categories[$item['categoria']] ?? null;
            $brandId = $brands[$item['marca']] ?? null;

            if (! $categoryId) {
                continue;
            }

            $precio = $item['precio'];
            $costo = round($precio * (random_int(55, 72) / 100), 2);

            Product::query()->updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'nombre' => $item['nombre'],
                    'descripcion' => 'Prenda textil — '.$item['nombre'].'. Colección temporada actual.',
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'costo' => $costo,
                    'precio' => $precio,
                    'stock_actual' => $item['stock'],
                    'stock_minimo' => max(5, (int) floor($item['stock'] * 0.15)),
                    'estado' => $item['estado'],
                ],
            );
        }
    }
}
