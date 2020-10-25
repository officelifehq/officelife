<?php

namespace App\Services\Company\Team;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;

class SetTeamLead extends BaseService
{
    private Employee $employee;
    private Team $team;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
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
     * Set the employee as the team leader.
     *
     * @param array $data
     *
     * @throws NotEnoughPermissionException
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);
        $this->team = $this->validateTeamBelongsToCompany($data);

        $this->save($data);

        $this->addEmployeeToTeam($data);

        $this->addNotification();

        $this->log($data);

        return $this->employee;
    }

    /**
     * Save the team with the new information.
     *
     * @param array $data
     */
    private function save(array $data): void
    {
        $this->team->team_leader_id = $data['employee_id'];
        $this->team->save();
    }

    /**
     * Add the employee to the team - if he’s not in the team already.
     *
     * @param array $data
     */
    private function addEmployeeToTeam(array $data): void
    {
        if ($this->employee->isInTeam($this->team->id)) {
            return;
        }

        (new AddEmployeeToTeam)->execute([
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'employee_id' => $data['employee_id'],
            'team_id' => $data['team_id'],
        ]);
    }

    /**
     * Add a notification in the UI for the employee who is the new leader.
     */
    private function addNotification(): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'team_lead_set',
            'objects' => json_encode([
                'team_name' => $this->team->name,
            ]),
        ])->onQueue('low');
    }

    /**
     * Log the information in the audit logs.
     *
     * @param array $data
     */
    private function log(array $data): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_leader_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_id' => $this->employee->id,
                'team_leader_name' => $this->employee->name,
                'team_name' => $this->team->name,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $this->team->id,
            'action' => 'team_leader_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_id' => $this->employee->id,
                'team_leader_name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }
}
