<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Morale;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Company\MoraleCompanyHistory;

class ProcessCompanyMorale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The parameter of the job.
     *
     * @var array
     */
    public array $parameters;

    /**
     * Create a new job instance.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $moraleCompanyHistory = MoraleCompanyHistory::whereBetween('created_at', [
                    $this->parameters['date']->toDateString().' 00:00:00',
                    $this->parameters['date']->toDateString().' 23:59:59',
                ])
                ->where('company_id', $this->parameters['company_id'])
                ->first();

        if (! is_null($moraleCompanyHistory)) {
            return;
        }

        $employeesWithMorale = Employee::whereHas('morales', function ($query) {
            $query->whereBetween(
                'created_at',
                [
                        $this->parameters['date']->toDateString().' 00:00:00',
                        $this->parameters['date']->toDateString().' 23:59:59',
                    ]
            );
        })->select('id')
            ->where('company_id', $this->parameters['company_id'])
            ->get();

        // calculate the average of all the morale
        $sum = 0;
        $average = 0;
        foreach ($employeesWithMorale as $employee) {
            $morale = Morale::where('employee_id', $employee->id)
                ->whereBetween('created_at', [
                    $this->parameters['date']->toDateString().' 00:00:00',
                    $this->parameters['date']->toDateString().' 23:59:59',
                ])
                ->first();

            $sum = $sum + $morale->emotion;
        }

        if ($employeesWithMorale->count() > 0) {
            $average = $sum / $employeesWithMorale->count();
        }

        MoraleCompanyHistory::create([
            'company_id' => $this->parameters['company_id'],
            'average' => $average,
            'number_of_employees' => $employeesWithMorale->count(),
        ]);
    }
}
