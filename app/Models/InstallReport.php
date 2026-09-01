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
        'status',
        'approved_by',
        'approved_at',
        'admin_note',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'approved_at' => 'datetime',
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

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}
