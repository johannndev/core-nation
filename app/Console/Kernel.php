<?php

namespace App\Console;

use App\Helpers\CronHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $crons = CronHelper::getCachedCrons()->where('status', 1); // hanya aktif

        foreach ($crons as $cron) {
            match (true) {
                str_starts_with($cron->schedule, 'dailyAt:') =>
                    $schedule->command($cron->command)->dailyAt(str_replace('dailyAt:', '', $cron->schedule)),

                $cron->schedule === 'daily' =>
                    $schedule->command($cron->command)->daily(),

                $cron->schedule === 'hourly' =>
                    $schedule->command($cron->command)->hourly(),

                $cron->schedule === 'everyMinute' =>
                    $schedule->command($cron->command)->everyMinute(),

                default => null,
            };
        }

        $schedule->command('jubelio:process-orders')->everyMinute();
        $schedule->command('jubelio:item-update')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
