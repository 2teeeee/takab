<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'title',
        'small_text',
        'large_text',
        'slug',
        'keywords',
        'description',
        'main_price',
        'sell_price',
        'seller_price',
        'category_id',
        'is_assembly_enabled',
        'is_main_sale',
        'is_assembled',
        'assembled_parts',
    ];

    protected $casts = [
        'assembled_parts' => 'array',
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

}
