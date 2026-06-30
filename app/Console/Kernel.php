<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // اجرای دستور بررسی سرویس‌های دوره‌ای هر روز ساعت 8 صبح
        $schedule->command('check:periodic-services')->dailyAt('08:00');
    }
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
