<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRequirement extends Model
{
    protected $fillable = [
        'production_plan_id',
        'product_bom_id',
        'component_product_id',
        'required_per_unit',
        'required_quantity',
        'available_quantity',
        'purchase_quantity',
        'unit_price',
        'unit',
        'status',
        'note',
    ];

    protected $casts = [
        'required_per_unit' => 'decimal:4',
        'required_quantity' => 'decimal:4',
        'available_quantity' => 'decimal:4',
        'purchase_quantity' => 'decimal:4',
        'unit_price' => 'integer',
    ];

    public function productionPlan(): BelongsTo
    {
        return $this->belongsTo(ProductionPlan::class);
    }

    public function productBom(): BelongsTo
    {
        return $this->belongsTo(ProductBom::class);
    }

    public function componentProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }
}