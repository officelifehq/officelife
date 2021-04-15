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
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Gets all the employees of the team, and check the morale of each employee
     * for the given day.
     * This will let us pre-calculate team morale so data will load faster.
     */
    public function handle()
    {
        // do we already have a record for today? if so, skip it.
        $moraleTeamHistory = MoraleTeamHistory::whereBetween('created_at', [
            $this->parameters['date']->toDateString().' 00:00:00',
            $this->parameters['date']->toDateString().' 23:59:59',
        ])
            ->where('team_id', $this->parameters['team_id'])
            ->first();

        if (! is_null($moraleTeamHistory)) {
            return;
        }

        $employeeIds = Team::find($this->parameters['team_id'])
            ->employees()
            ->pluck('id')
            ->toArray();

        $averageMorale = Morale::whereIn('employee_id', $employeeIds)
            ->whereBetween('created_at', [
                $this->parameters['date']->toDateString().' 00:00:00',
                $this->parameters['date']->toDateString().' 23:59:59',
            ])
            ->avg('emotion');

        MoraleTeamHistory::create([
            'team_id' => $this->parameters['team_id'],
            'average' => $averageMorale ? $averageMorale : 0,
            'number_of_team_members' => count($employeeIds),
            'created_at' => $this->parameters['date'],
        ]);
    }
}
