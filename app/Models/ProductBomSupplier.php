<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductBomSupplier extends Model
{
    protected $fillable = [
        'product_bom_id',
        'supplier_id',
        'unit_price',
        'is_default',
        'is_active',
        'note',
    ];

    protected $casts = [
        'unit_price' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function productBom(): BelongsTo
    {
        return $this->belongsTo(ProductBom::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}