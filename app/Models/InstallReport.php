<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallReport extends Model
{
    protected $fillable = [
        'install_schedule_id',
        'installer_id',
        'completed',
        'description',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(
            InstallSchedule::class,
            'install_schedule_id'
        );
    }

    public function installer(): BelongsTo
    {
        return $this->belongsTo(
            Installer::class,
            'installer_id'
        );
    }
}
