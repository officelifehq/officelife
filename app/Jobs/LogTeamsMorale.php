<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogTeamsMorale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $date;

    /**
     * Create a new job instance.
     *
     * @param Carbon $date
     * @return void
     */
    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * Log the morale of all teams by looking at the current day and all the
     * employees of those teams.
     * This job is meant to be executed every day at 11pm (UTC).
     *
     * @return void
     */
    public function handle()
    {
        $employeesWithMorale = Employee::whereHas('morale', function ($query) {
            $query->whereDate('created_at', $this->date);
        })->get();

        foreach ($employeesWithoutLogs as $employee) {
            $employee->consecutive_worklog_missed = $employee->consecutive_worklog_missed + 1;
            $employee->save();
        }
    }
}
