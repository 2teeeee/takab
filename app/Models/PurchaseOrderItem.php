<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'production_requirement_id',
        'component_product_id',
        'quantity',
        'received_quantity',
        'unit_price',
        'total_price',
        'unit',
        'note',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'received_quantity' => 'decimal:4',
        'unit_price' => 'integer',
        'total_price' => 'integer',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(
            PurchaseOrder::class
        );
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(
            ProductionRequirement::class,
            'production_requirement_id'
        );
    }

    public function componentProduct(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'component_product_id'
        );
    }
}
