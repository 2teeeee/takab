<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'status',
        'total',
        'reference_id',
        'authority',
        'moarefStore_id',
        'moaref_id',
        'seller_id',
        'seller_role',
        'discount',
        'final_total'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moarefStore(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moarefStore_id');
    }

    public function moaref(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moaref_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

