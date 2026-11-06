<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'title',
    ];

    protected $casts = [
        'value' => 'integer',
    ];

    public static function getValue(string $key, int $default = 0): int
    {
        return (int) static::where('key', $key)
            ->value('value') ?? $default;
    }
}
