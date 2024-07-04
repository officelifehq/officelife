<?php

namespace App\Jobs;

use App\Models\Company\Employee;
use App\Services\Company\Employee\Holiday\ProcessDailyTimeOffBalance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateTimeOffBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The date this event should be registered.
     */
    public string $date;

    /**
     * The employee to run this calulation against.
     */
    public Employee $employee;

    /**
     * Create a new job instance.
     */
    public function __construct(Employee $employee, string $date)
    {
        $this->employee = $employee;
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new ProcessDailyTimeOffBalance)->execute([
            'employee_id' => $this->employee->id,
            'date' => $this->date,
        ]);
    }
}
