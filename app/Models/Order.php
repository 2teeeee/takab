<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'from_user_id',
        'wholesaler_id',
        'seller_id',
        'seller_role',
        'moarefStore_id',
        'moaref_id',
        'address',
        'status',
        'status_note',
        'total',
        'discount',
        'final_total',
        'reference_id',
        'authority',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moarefStore(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moarefStore_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wholesaler_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function moaref(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moaref_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(ReferralCommission::class);
    }
}

