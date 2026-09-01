<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InstallRequest extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'wholesaler_id',
        'device_model',
        'serial_number',
        'address',
        'status',
        'installation_date',
        'description',
    ];

    protected $casts = [
        'installation_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wholesaler_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(InstallSchedule::class);
    }
}
