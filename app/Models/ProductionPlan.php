<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionPlan extends Model
{
    protected $fillable = [
        'product_id',
        'start_date',
        'end_date',
        'quantity',
        'status',
        'note',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'quantity' => 'integer',
    ];

    /**
     * دستگاهی که قرار است تولید شود
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * کاربر ایجادکننده
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * نیازمندی‌های تولید
     */
    public function requirements(): HasMany
    {
        return $this->hasMany(ProductionRequirement::class);
    }
}