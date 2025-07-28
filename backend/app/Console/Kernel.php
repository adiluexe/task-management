<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clean up tasks older than 30 days, run daily at 2 AM
        $schedule->command('tasks:cleanup --days=30')
            ->daily()
            ->at('02:00')
            ->withoutOverlapping()
            ->emailOutputOnFailure(env('ADMIN_EMAIL', 'admin@example.com'));

        // You can add more scheduled tasks here
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
