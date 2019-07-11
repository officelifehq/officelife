<?php

namespace App\Jobs\Employee;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessWorklogWeeklyStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Calculates the usage of the worklog feature for each employee.
     *
     * @return void
     */
    public function handle()
    {
        $employeesWithLogs = Employee::whereHas('worklogs', function ($query) {
            $query->whereDate('created_at', Carbon::today());
        })->get();

        $allEmployees = Employee::select('id')->get();
        $employeesWithoutLogs = $allEmployees->diff($employeesWithLogs);

        foreach ($employeesWithoutLogs as $employee) {
            $employee->consecutive_worklog_missed = $employee->consecutive_worklog_missed + 1;
            $employee->save();
        }
    }
}
