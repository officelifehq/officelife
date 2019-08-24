<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Company\Team;
use Illuminate\Bus\Queueable;
use App\Services\Logs\LogTeamAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessTeamMorale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The parameter of the job.
     *
     * @var array
     */
    public $parameters;

    /**
     * Create a new job instance.
     *
     * @param array $parameters
     * @return void
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get all the emotion ratings for each morale of each employee of the
        // given company at the given date
        $employeesWithMorale = Employee::whereHas('morale', function ($query) {
            $query->whereDate('created_at', $this->date);
        })->where('company_id', $this->parameters['company_id'])
        ->get();

        // calculate the average of all the morale
        $sum = 0;
        foreach ($employees as $employee) {
            $sum = $sum + $employee
        }

        // save the result
    }
}
