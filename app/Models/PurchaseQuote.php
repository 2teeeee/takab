<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseQuote extends Model
{
    protected $fillable = [
        'purchase_request_id',
        'supplier_id',
        'available_quantity',
        'unit_price',
        'total_price',
        'delivery_days',
        'note',
        'quoted_at',
    ];

    protected $casts = [
        'available_quantity' => 'decimal:4',
        'unit_price' => 'integer',
        'total_price' => 'integer',
        'delivery_days' => 'integer',
        'quoted_at' => 'datetime',
    ];

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(
            PurchaseRequest::class
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'supplier_id'
        );
    }
}
