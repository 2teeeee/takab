<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletWithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'amount',
        'card_number',
        'account_number',
        'sheba_number',
        'account_holder_name',
        'status',
        'description',
        'admin_note',
        'processed_by',
        'processed_at',
        'paid_at',
        'payment_tracking_code',
    ];

    protected $casts = [
        'amount' => 'integer',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }
}