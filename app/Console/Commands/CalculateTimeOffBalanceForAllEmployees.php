<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Company\Employee;
use App\Jobs\CalculateTimeOffBalance;

class CalculateTimeOffBalanceForAllEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timeoff:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the permission of the employee';

    /**
     * The date this event should be registered.
     *
     */
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
        parent::__construct();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Employee::select('id')->chunk(100, function ($employees) {
            $employees->each(function (Employee $employee) {
                CalculateTimeOffBalance::dispatch([
                    'employee_id' => $employee->id,
                    'date' => $this->date,
                ])->onQueue('low');
            });
        });
    }
}
