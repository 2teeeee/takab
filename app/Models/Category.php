<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'title',
        'keywords',
        'description',
        'is_assembly_enabled',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
