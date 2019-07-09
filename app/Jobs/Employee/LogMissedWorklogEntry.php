<?php

namespace App\Jobs\Employee;

use Illuminate\Bus\Queueable;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogMissedWorklogEntry implements ShouldQueue
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $employees = Employee::whereHas('worklogs', function ($query) {
            $query->whereDate('created_at', Carbon::today());
        })->get();

        foreach ($employees as $employee) {
            $employee->consecutive_worklog_missed = $employee->consecutive_worklog_missed + 1;
            $employee->save();
        }
    }
}
