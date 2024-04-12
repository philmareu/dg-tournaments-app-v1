<?php

namespace DGTournaments\Console;

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
        \Bugsnag\BugsnagLaravel\Commands\DeployCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tournaments:update')->dailyAt('13:00');
        $schedule->command('courses:update')->dailyAt('14:00');
//        $schedule->command('search:check-saved-searches')->twiceDaily(9, 5);
        $schedule->command('ratings:update')->twiceDaily(10, 6);
        $schedule->command('maintenance:claim-requests')->hourly();
        $schedule->command('maintenance:check-api-fields')->daily();
        $schedule->command('search:remove-past')->twiceDaily(3, 15);
        $schedule->command('maintenance:flag-tournaments')->daily();
        $schedule->command('registration:check')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
