<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallSchedule extends Model
{
    protected $fillable = [
        'installer_id',
        'install_request_id',
        'scheduled_date',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function installer()
    {
        return $this->belongsTo(Installer::class);
    }

    public function installRequest()
    {
        return $this->belongsTo(
            InstallRequest::class,
            'install_request_id'
        );
    }
}
