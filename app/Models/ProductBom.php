<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBom extends Model
{
    protected $fillable = [
        'product_id',
        'component_product_id',
        'quantity',
        'unit',
        'unit_price',
        'is_active',
        'note',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * محصول نهایی / دستگاه
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * قطعه / ماده اولیه
     */
    public function componentProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }

    /**
     * تأمین‌کنندگان این قطعه
     */
    public function suppliers(): HasMany
    {
        return $this->hasMany(ProductBomSupplier::class);
    }

    /**
     * نیازمندی‌های تولید
     */
    public function requirements(): HasMany
    {
        return $this->hasMany(ProductionRequirement::class);
    }

    /**
     * تأمین‌کننده پیش‌فرض
     */
    public function defaultSupplier()
    {
        return $this->hasOne(ProductBomSupplier::class)
            ->where('is_default', true)
            ->where('is_active', true);
    }
}