<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('users:blocked')->everyThirtyMinutes();
        $schedule->command('users:late-cancelled')->everyThirtyMinutes();
        $schedule->command('coderstm:users-hold')->dailyAt('05:00');
        $schedule->command('coderstm:subscriptions-cancel')->dailyAt('05:00');
        $schedule->command('coderstm:subscriptions-canceled')->dailyAt('05:00');
        $schedule->command('coderstm:subscriptions-expired')->dailyAt('04:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
