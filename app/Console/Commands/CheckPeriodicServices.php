<?php

namespace App\Console\Commands;

use App\Models\PeriodicService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckPeriodicServices extends Command
{
    protected $signature = 'app:check-periodic-services';

    protected $description = 'Command description';

    public function handle(): void
    {
        $services = PeriodicService::where('next_service_date', '<=', now())
            ->where('notified', false)
            ->get();

        foreach ($services as $service) {
            $user = $service->installRequest->user;

            // ارسال ایمیل یا اعلان
//            Notification::send($user, new PeriodicServiceReminder($service));

            $service->update(['notified' => true]);
        }

        $this->info("Checked and notified users for periodic services.");
    }
}
