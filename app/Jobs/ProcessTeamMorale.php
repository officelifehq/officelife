<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Company\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessTeamMorale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The team instance.
     *
     * @var array
     */
    public $team;

    /**
     * The date instance.
     *
     * @var Carbon
     */
    public $date;

    /**
     * Create a new job instance.
     *
     * @param Team $team
     * @param Carbon $date
     * @return void
     */
    public function __construct(Team $team, Carbon $date)
    {
        $this->team = $team;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get all employees within the team who have logged a morale
        $employeesWithMorale = Employee::whereHas('morale', function ($query) {
            $query->whereDate('created_at', $this->date);
        })->where('team_id')->get();

        // calculate the average of all the morale

        // save the result
    }
}
