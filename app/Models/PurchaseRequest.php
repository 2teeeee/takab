<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'production_requirement_id',
        'supplier_id',
        'quantity',
        'unit',
        'status',
        'requested_at',
        'deadline',
        'note',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'requested_at' => 'datetime',
        'deadline' => 'date',
    ];

    public function productionRequirement(): BelongsTo
    {
        return $this->belongsTo(
            ProductionRequirement::class
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'supplier_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(
            PurchaseQuote::class
        );
    }
}
