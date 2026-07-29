<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Letter extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'body',
        'priority',
        'status',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(LetterReference::class);
    }

    public function receivers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'letter_receivers'
        )
            ->withPivot(['status', 'read_at'])
            ->withTimestamps();
    }

    public function receiverItems(): HasMany
    {
        return $this->hasMany(LetterReceiver::class);
    }

    public function getUrlAttribute(): string
    {
        return route('admin.letters.show', $this);
    }
}
