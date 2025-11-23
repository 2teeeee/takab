<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodicService extends Model
{
    use HasFactory;

    protected $fillable = [
        'install_request_id',
        'last_service_date',
        'next_service_date',
        'notified',
    ];

    protected $casts = [
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'notified' => 'boolean',
    ];

    // --- روابط ---
    public function installRequest(): BelongsTo
    {
        return $this->belongsTo(InstallRequest::class);
    }

    // محاسبه خودکار سرویس بعدی در صورت تغییر تاریخ سرویس قبلی
    public static function boot(): void
    {
        parent::boot();

        static::saving(function ($service) {
            if ($service->last_service_date && !$service->next_service_date) {
                $service->next_service_date = Carbon::parse($service->last_service_date)->addMonths(6);
            }
        });
    }
}
