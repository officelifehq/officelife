<?php

namespace App\Console;

use Carbon\Carbon;
use App\Jobs\LogTeamsMorale;
use App\Jobs\LogCompaniesMorale;
use App\Jobs\LogMissedWorklogEntry;
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
        'App\Console\Commands\LangGenerate',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $midnight = '23:00';

        $schedule->job(new LogMissedWorklogEntry(Carbon::today()))->dailyAt($midnight);
        $schedule->job(new LogCompaniesMorale(Carbon::today()))->dailyAt($midnight);
        $schedule->job(new LogTeamsMorale(Carbon::today()))->dailyAt($midnight);

        $schedule->command('timeoff:calculate '.Carbon::today()->format('Y-m-d'))->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
