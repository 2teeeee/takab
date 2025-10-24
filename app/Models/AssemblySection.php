<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AssemblySection extends Model
{
    protected $fillable = ['title', 'is_active'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'assembly_section_products');
    }
}
