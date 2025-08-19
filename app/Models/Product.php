<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
