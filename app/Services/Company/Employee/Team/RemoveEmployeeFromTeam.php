<?php

namespace App\Services\Company\Employee\Team;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Team\LogTeamAction;
use App\Services\Company\Employee\LogEmployeeAction;
use App\Services\Company\Adminland\Company\LogAuditAction;

class RemoveEmployeeFromTeam extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'team_id' => 'required|integer|exists:teams,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Remove an employee from a team.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data) : Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        $team->employees()->detach($data['employee_id'], ['company_id' => $data['company_id']]);

        $dataToLog = [
            'author_id' => $author->id,
            'author_name' => $author->name,
            'team_id' => $team->id,
            'team_name' => $team->name,
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
        ];

        (new LogAuditAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'employee_removed_from_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogTeamAction)->execute([
            'company_id' => $data['company_id'],
            'team_id' => $data['team_id'],
            'action' => 'employee_removed_from_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogEmployeeAction)->execute([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => 'employee_removed_from_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        $employee->refresh();

        return $employee;
    }
}
