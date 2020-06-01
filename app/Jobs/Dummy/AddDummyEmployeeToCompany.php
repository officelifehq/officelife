<?php

namespace App\Jobs\Dummy;

use Carbon\Carbon;
use App\Models\Company\Team;
use Illuminate\Bus\Queueable;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Company\Team\SetTeamLead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Services\Company\Employee\Birthdate\SetBirthdate;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;

class AddDummyEmployeeToCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The array instance.
     *
     * @var array
     */
    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $employee = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'email' => $this->data['email'],
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'permission_level' => $this->data['permission_level'],
            'send_invitation' => $this->data['send_invitation'],
            'is_dummy' => true,
        ]);

        $team = Team::where('name', $this->data['team_name'])->first();

        (new AddEmployeeToTeam)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'employee_id' => $employee->id,
            'team_id' => $team->id,
            'is_dummy' => true,
        ]);

        $position = Position::where('title', $this->data['position_name'])->first();

        (new AssignPositionToEmployee)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'employee_id' => $employee->id,
            'position_id' => $position->id,
            'is_dummy' => true,
        ]);

        if ($this->data['birthdate']) {
            $date = Carbon::parse($this->data['birthdate']);
            (new SetBirthdate)->execute([
                'company_id' => $this->data['company_id'],
                'author_id' => $this->data['author_id'],
                'employee_id' => $employee->id,
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
                'is_dummy' => true,
            ]);
        }

        if ($this->data['manager_name']) {
            $manager = Employee::where('last_name', $this->data['manager_name'])->firstOrFail();

            (new AssignManager)->execute([
                'company_id' => $this->data['company_id'],
                'author_id' => $this->data['author_id'],
                'employee_id' => $employee->id,
                'manager_id' => $manager->id,
                'is_dummy' => true,
            ]);
        }

        if ($this->data['leader_of_team_name']) {
            (new SetTeamLead)->execute([
                'company_id' => $this->data['company_id'],
                'author_id' => $this->data['author_id'],
                'employee_id' => $employee->id,
                'team_id' => $team->id,
                'is_dummy' => true,
            ]);
        }
    }
}
