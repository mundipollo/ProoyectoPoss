<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'nombre',
        'descripcion',
        'imagen',
        'category_id',
        'brand_id',
        'costo',
        'precio',
        'stock_actual',
        'stock_minimo',
        'estado',
        'genero',
        'tallas',
    ];

    protected $casts = [
        'tallas' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
