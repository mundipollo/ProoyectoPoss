<?php

namespace App\Support;

class StoreCatalogImages
{
    /**
     * @var array<string, string>
     */
    private const CATEGORY_IMAGES = [
        'Camisetas' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=800',
        'Pantalones' => 'https://images.unsplash.com/photo-1473966968600-fa801b279a0a?q=80&w=800',
        'Accesorios' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800',
        'Vestidos' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?q=80&w=800',
        'Chaquetas' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=800',
        'Ropa deportiva' => 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?q=80&w=800',
    ];

    private const DEFAULT_IMAGE = 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800';

    public static function forCategory(?string $categoryName): string
    {
        if ($categoryName === null) {
            return self::DEFAULT_IMAGE;
        }

        return self::CATEGORY_IMAGES[$categoryName] ?? self::DEFAULT_IMAGE;
    }
}
