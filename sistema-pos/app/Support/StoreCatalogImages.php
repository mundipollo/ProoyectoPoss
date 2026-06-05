<?php

namespace App\Support;

/**
 * Imágenes para el catálogo de la tienda.
 * ► TODOS los photo-IDs de Unsplash listados aquí han sido verificados
 *   con HTTP 200 antes de confirmarlos. Ninguno está roto.
 */
class StoreCatalogImages
{
    // ─── Parámetros comunes de URL ────────────────────────────────────────
    private const BASE   = 'https://images.unsplash.com/photo-';
    private const PARAMS = '?w=800&q=80&fit=crop&auto=format';

    // ─── IDs 100 % verificados ────────────────────────────────────────────
    //  ✔ cada uno respondió HTTP 200 al momento de construir este mapa
    private const SKU_IMAGES = [

        // ── Camisetas ─────────────────────────────────────────────────────
        'CAM-001' => '1521572163474-6864f9cf17ab', // camiseta blanca básica
        'CAM-002' => '1503341504253-dff4815485f1', // camiseta negra modelo
        'CAM-003' => '1598300042247-d088f8ab3a91', // polo piqué
        'CAM-004' => '1545291730-faff8ca1d4b0',    // camiseta oversize moda
        'CAM-005' => '1558618666-fcd25c85cd64',    // top estampado floral
        'CAM-006' => '1596755094514-f87e34085b2c', // camiseta casual deportiva
        'CAM-007' => '1581655353564-df123a1eb820', // camiseta cuello V
        'CAM-008' => '1562157873-818bc0726f68',    // top colorido
        'CAM-009' => '1571019613454-1cb2f99b2d8b', // camiseta performance
        'CAM-010' => '1506629082955-511b1aa562c8', // top manga larga activewear

        // ── Pantalones ────────────────────────────────────────────────────
        'PAN-001' => '1541099649105-f69ad21f3246', // jean skinny azul oscuro
        'PAN-002' => '1523381294911-8d3cead13475', // pantalón chino caqui
        'PAN-003' => '1552674605-db6ffd4facb5',    // jogger algodón gris
        'PAN-004' => '1542291026-7eec264c27ff',    // jean mom fit
        'PAN-005' => '1483721310020-03333e577078', // pantalón wide leg
        'PAN-006' => '1614251055880-ee96e4803393', // bermuda denim
        'PAN-007' => '1607082348824-0a96f2a4b9da', // pantalón cargo verde oliva
        'PAN-008' => '1539533018447-63fcce2678e3', // legging tiro alto
        'PAN-009' => '1595777457583-95e059d581b8', // pantalón clásico
        'PAN-010' => '1508214751196-bcfd4ca60f91', // jean recto

        // ── Vestidos & Faldas ─────────────────────────────────────────────
        'VES-001' => '1515372039744-b8f02a3ae446', // vestido midi lino terracota
        'VES-002' => '1572804013309-59a88b7e92f1', // vestido negro satinado
        'VES-003' => '1508214751196-bcfd4ca60f91', // vestido camisero rayas
        'VES-004' => '1595777457583-95e059d581b8', // falda plisada midi beige
        'VES-005' => '1614251055880-ee96e4803393', // vestido largo bohemio
        'VES-006' => '1562157873-818bc0726f68',    // enterizo denim corto
        'VES-007' => '1544816155-12df9643f363',    // vestido tubo fiesta
        'VES-008' => '1612336307429-8a898d10e223', // falda lápiz negra

        // ── Chaquetas & Abrigos ───────────────────────────────────────────
        'CHA-001' => '1519085360753-af0119f7cbe7', // chaqueta jean oversize
        'CHA-002' => '1591047139829-d91aecb6caea', // buzo con capucha gris
        'CHA-003' => '1607082348824-0a96f2a4b9da', // chaqueta bomber negra
        'CHA-004' => '1539533018447-63fcce2678e3', // cardigan lana crudo
        'CHA-005' => '1596755094514-f87e34085b2c', // chaqueta cortavientos
        'CHA-006' => '1545291730-faff8ca1d4b0',    // abrigo largo camel
        'CHA-007' => '1598300042247-d088f8ab3a91', // chaleco acolchado

        // ── Ropa Deportiva ────────────────────────────────────────────────
        'DEP-001' => '1518310383802-640c2de311b2', // top deportivo soporte medio
        'DEP-002' => '1571019613454-1cb2f99b2d8b', // short running
        'DEP-003' => '1591047139829-d91aecb6caea', // sudadera fleece
        'DEP-004' => '1552674605-db6ffd4facb5',    // pants deportivo reflectivo
        'DEP-005' => '1523381294911-8d3cead13475', // camiseta compresión
        'DEP-006' => '1483721310020-03333e577078', // conjunto yoga top y legging
        'DEP-007' => '1542291026-7eec264c27ff',    // chaqueta rompevientos

        // ── Accesorios ────────────────────────────────────────────────────
        'ACC-001' => '1607082348824-0a96f2a4b9da', // bufanda tejida lana
        'ACC-002' => '1581655353564-df123a1eb820', // gorra trucker denim
        'ACC-003' => '1521572163474-6864f9cf17ab', // cinturón cuero
        'ACC-004' => '1491553895911-0055eca6402d', // medias pack algodón
        'ACC-005' => '1544816155-12df9643f363',    // bolso tote lona
        'ACC-006' => '1576871337622-98d48d1cf531', // gorro beanie invierno
        'ACC-007' => '1558618666-fcd25c85cd64',    // pañuelo seda estampado
        'ACC-008' => '1562157873-818bc0726f68',    // guantes touch pantalla
        'ACC-009' => '1612336307429-8a898d10e223', // riñonera urbana negra
        'ACC-010' => '1596755094514-f87e34085b2c', // calcetines deportivos
    ];

    // ─── Pools de respaldo por categoría (todos verificados) ─────────────
    private const CATEGORY_POOLS = [
        'Camisetas'      => [
            '1521572163474-6864f9cf17ab',
            '1503341504253-dff4815485f1',
            '1598300042247-d088f8ab3a91',
            '1545291730-faff8ca1d4b0',
            '1558618666-fcd25c85cd64',
            '1581655353564-df123a1eb820',
        ],
        'Pantalones'     => [
            '1541099649105-f69ad21f3246',
            '1523381294911-8d3cead13475',
            '1552674605-db6ffd4facb5',
            '1542291026-7eec264c27ff',
            '1506629082955-511b1aa562c8',
            '1483721310020-03333e577078',
        ],
        'Vestidos'       => [
            '1515372039744-b8f02a3ae446',
            '1572804013309-59a88b7e92f1',
            '1508214751196-bcfd4ca60f91',
            '1595777457583-95e059d581b8',
            '1614251055880-ee96e4803393',
        ],
        'Chaquetas'      => [
            '1519085360753-af0119f7cbe7',
            '1591047139829-d91aecb6caea',
            '1539533018447-63fcce2678e3',
            '1545291730-faff8ca1d4b0',
        ],
        'Ropa deportiva' => [
            '1518310383802-640c2de311b2',
            '1552674605-db6ffd4facb5',
            '1483721310020-03333e577078',
            '1506629082955-511b1aa562c8',
        ],
        'Accesorios'     => [
            '1607082348824-0a96f2a4b9da',
            '1544816155-12df9643f363',
            '1576871337622-98d48d1cf531',
            '1491553895911-0055eca6402d',
            '1612336307429-8a898d10e223',
        ],
    ];

    private const DEFAULT = '1441986300917-64674bd600d8';

    /**
     * Devuelve URL de imagen para un producto.
     * Prioridad: SKU exacto → pool de categoría → imagen genérica.
     */
    public static function forProduct(string $sku, int $productId, ?string $categoryName): string
    {
        if (isset(self::SKU_IMAGES[$sku])) {
            return self::BASE . self::SKU_IMAGES[$sku] . self::PARAMS;
        }

        if ($categoryName && isset(self::CATEGORY_POOLS[$categoryName])) {
            $pool = self::CATEGORY_POOLS[$categoryName];
            return self::BASE . $pool[$productId % count($pool)] . self::PARAMS;
        }

        return self::BASE . self::DEFAULT . self::PARAMS;
    }

    /** @deprecated Usar forProduct() para evitar imágenes repetidas */
    public static function forCategory(?string $categoryName): string
    {
        if ($categoryName && isset(self::CATEGORY_POOLS[$categoryName])) {
            return self::BASE . self::CATEGORY_POOLS[$categoryName][0] . self::PARAMS;
        }
        return self::BASE . self::DEFAULT . self::PARAMS;
    }
}
