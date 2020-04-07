<?php

namespace App\Jobs;

use App\Models\Company\Team;
use Illuminate\Bus\Queueable;
use App\Models\Company\Morale;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Company\MoraleTeamHistory;
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
    public array $parameters;

    /**
     * Create a new job instance.
     *
     * @param array $parameters
     *
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $moraleTeamHistory = MoraleTeamHistory::whereBetween('created_at', [
            $this->parameters['date']->toDateString().' 00:00:00',
            $this->parameters['date']->toDateString().' 23:59:59',
        ])
            ->where('team_id', $this->parameters['team_id'])
            ->first();

        if (! is_null($moraleTeamHistory)) {
            return;
        }

        $employees = Team::find($this->parameters['team_id'])
            ->employees()
            ->select('id')
            ->get();

        // calculate the average of all the morale
        $sum = 0;
        $numberOfEmployees = 0;
        foreach ($employees as $employee) {
            $morale = Morale::where('employee_id', $employee->id)
                ->whereBetween('created_at', [
                    $this->parameters['date']->toDateString().' 00:00:00',
                    $this->parameters['date']->toDateString().' 23:59:59',
                ])
                ->first();

            if (! is_null($morale)) {
                $sum = $sum + $morale->emotion;
                $numberOfEmployees = $numberOfEmployees + 1;
            }
        }

        $average = $sum / $numberOfEmployees;

        MoraleTeamHistory::create([
            'team_id' => $this->parameters['team_id'],
            'average' => $average,
            'number_of_team_members' => $numberOfEmployees,
        ]);
    }
}
