<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'main_price',
        'sell_price',
        'category_id',
        'is_assembly_enabled',
        'is_main_sale',
        'is_assembled',
        'assembled_parts',
        'slug',
    ];

    protected $casts = [
        'assembled_parts' => 'array',
        'is_assembly_enabled' => 'boolean',
        'is_main_sale' => 'boolean',
        'is_assembled' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(ProductTranslation::class)
            ->where('locale', app()->getLocale());
    }

}
