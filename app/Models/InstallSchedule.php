<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'installer_id',
        'install_request_id',
        'scheduled_date',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    // --- روابط ---
    public function installer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'installer_id');
    }

    public function installRequest(): BelongsTo
    {
        return $this->belongsTo(InstallRequest::class);
    }
}
