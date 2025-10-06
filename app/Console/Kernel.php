<?php

namespace App\Console;

use App\Jobs\DepositReleaseEligibilityJob;
use App\Jobs\GenerateRecurringInvoicesJob;
use App\Jobs\MarkOverdueInvoicesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new GenerateRecurringInvoicesJob())->dailyAt('06:00');
        $schedule->job(new MarkOverdueInvoicesJob())->dailyAt('07:00');
        $schedule->job(new DepositReleaseEligibilityJob())->dailyAt('08:00');

        // Example cron entry: * * * * * php artisan schedule:run
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
