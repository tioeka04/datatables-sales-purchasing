<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Elektronik',
        'Alat Tulis',
        'Perabot',
        'Makanan & Minuman',
        'Peralatan Rumah Tangga',
        'Otomotif',
    ];

    protected $fillable = [
        'product_code',
        'product_name',
        'category',
        'supplier_id',
        'purchase_price',
        'price',
        'stock',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
