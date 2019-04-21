<?php

namespace App\Services\Company\Employee\Team;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Employee\LogEmployeeAction;
use App\Services\Company\Adminland\Company\LogAuditAction;

class AddEmployeeToTeam extends BaseService
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
     * Add an employee to a team.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data): Employee
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

        $team->employees()->attach($data['employee_id'], ['company_id' => $data['company_id']]);

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
            'action' => 'employee_added_to_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogEmployeeAction)->execute([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => 'employee_added_to_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        $employee->refresh();

        return $employee;
    }
}
