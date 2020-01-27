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
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Set the employee as the team leader.
     *
     * @param array $data
     * @return Employee
     * @throws NotEnoughPermissionException
     */
    public function execute(array $data): Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('officelife.authorizations.hr')
        );

        $employee = $this->validateEmployeeBelongsToCompany($data);
        $team = $this->validateTeamBelongsToCompany($data);

        $team = $this->save($data, $team);

        $this->addEmployeeToTeam($data, $employee, $team);

        $this->addNotification($employee, $team);

        $this->log($data, $author, $employee, $team);

        return $employee;
    }

    /**
     * Save the team with the new information.
     *
     * @param array $data
     * @param Team $team
     * @return Team
     */
    private function save(array $data, Team $team): Team
    {
        $team->team_leader_id = $data['employee_id'];
        $team->save();

        return $team;
    }

    /**
     * Add the employee to the team - if he’s not in the team already.
     *
     * @param array $data
     * @param Employee $employee
     * @param Team $team
     * @return void|null
     */
    private function addEmployeeToTeam(array $data, Employee $employee, Team $team)
    {
        if ($employee->isInTeam($team->id)) {
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
     *
     * @param Employee $employee
     * @param Team $team
     * @return void
     */
    private function addNotification(Employee $employee, Team $team): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $employee->id,
            'action' => 'team_lead_set',
            'objects' => json_encode([
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');
    }

    /**
     * Log the information in the audit logs.
     *
     * @param array $data
     * @param Employee $author
     * @param Employee $employee
     * @param Team $team
     * @return void
     */
    private function log(array $data, Employee $author, Employee $employee, Team $team): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_leader_assigned',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_id' => $employee->id,
                'team_leader_name' => $employee->name,
                'team_name' => $team->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'team_leader_assigned',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_id' => $employee->id,
                'team_leader_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
