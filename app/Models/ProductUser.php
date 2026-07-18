<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUser extends Model
{
    protected $table = 'product_user';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function increase($userId, $productId, $quantity)
    {
        $inventory = static::firstOrNew([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        $inventory->quantity += $quantity;
        $inventory->save();

        return $inventory;
    }
}
