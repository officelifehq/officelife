<?php

namespace App\Services\Company\Employee\Team;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class AddEmployeeToTeam extends BaseService
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
     * Add an employee to a team.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('officelife.permission_level.hr')
        );

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $team = $this->validateTeamBelongsToCompany($data);

        $team->employees()->attach(
            $data['employee_id'],
            [
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $this->addNotification($employee, $team);

        $this->log($data, $employee, $team, $author);

        $employee->refresh();

        return $employee;
    }

    /**
     * Add a notification in the UI for the employee that is added to the team.
     *
     * @param Employee $employee
     * @param Team $team
     * @return void
     */
    private function addNotification(Employee $employee, Team $team): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $employee->id,
            'action' => 'employee_added_to_team',
            'objects' => json_encode([
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');
    }

    /**
     * Add the logs in the different audit logs.
     *
     * @param array $data
     * @param Employee $employee
     * @param Team $team
     * @param Employee $author
     * @return void
     */
    private function log(array $data, Employee $employee, Team $team, Employee $author): void
    {
        $dataToLog = [
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'team_id' => $team->id,
            'team_name' => $team->name,
        ];

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_added_to_team',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'employee_added_to_team',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'employee_added_to_team',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
