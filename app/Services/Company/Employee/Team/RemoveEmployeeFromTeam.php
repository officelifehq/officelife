<?php

namespace App\Services\Company\Employee\Team;

use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Employee;
use App\Models\Company\Team;
use App\Services\BaseService;
use Carbon\Carbon;

class RemoveEmployeeFromTeam extends BaseService
{
    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'team_id' => 'required|integer|exists:teams,id',
        ];
    }

    /**
     * Remove an employee from a team.
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        $team = $this->validateTeamBelongsToCompany($data);

        $team->employees()->detach($data['employee_id']);

        $this->addNotification($team);

        $this->log($data, $team);

        $this->employee->refresh();

        return $this->employee;
    }

    /**
     * Add a notification in the UI for the employee that is added to the team.
     */
    private function addNotification(Team $team): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_removed_from_team',
            'objects' => json_encode([
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');
    }

    /**
     * Add the logs in the different audit logs.
     */
    private function log(array $data, Team $team): void
    {
        $dataToLog = [
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->name,
            'team_id' => $team->id,
            'team_name' => $team->name,
        ];

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_removed_from_team',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'employee_removed_from_team',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_removed_from_team',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
        ])->onQueue('low');
    }
}
