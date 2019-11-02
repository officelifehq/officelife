<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Employee\Holiday\ProcessDailyTimeOffBalance;

class CalculateTimeOffBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The date this event should be registered.
     */
    public $date;

    /**
     * The employee to run this calulation against.
     */
    public $employee;

    /**
     * Create a new job instance.
     *
     * @param Employee $employee
     * @param string $date
     * @return void
     */
    public function __construct(Employee $employee, string $date)
    {
        $this->employee = $employee;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new ProcessDailyTimeOffBalance)->execute([
            'employee_id' => $this->employee->id,
            'date' => $this->date,
        ]);
    }
}
