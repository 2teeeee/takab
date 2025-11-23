<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InstallRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_model',
        'serial_number',
        'address',
        'status',
        'installation_date',
    ];

    protected $casts = [
        'installation_date' => 'datetime',
    ];

    // --- روابط ---
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(InstallSchedule::class);
    }

    public function periodicService(): HasOne
    {
        return $this->hasOne(PeriodicService::class);
    }
}
