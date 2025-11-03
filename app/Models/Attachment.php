<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'letter_id',
        'file_path',
        'file_name',
    ];

    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    public function getFullPathAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
