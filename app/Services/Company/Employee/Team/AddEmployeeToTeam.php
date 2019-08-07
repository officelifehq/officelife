<?php

namespace App\Services\Company\Employee\Team;

use Carbon\Carbon;
use App\Models\Company\Team;
use App\Services\BaseService;
use App\Jobs\Logs\LogTeamAudit;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use App\Jobs\Logs\LogEmployeeAudit;

class AddEmployeeToTeam extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
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

        $team->employees()->attach(
            $data['employee_id'],
            [
                'company_id' => $data['company_id'],
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $dataToLog = [
            'author_id' => $author->id,
            'author_name' => $author->name,
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'team_id' => $team->id,
            'team_name' => $team->name,
        ];

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_added_to_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        LogTeamAudit::dispatch([
            'company_id' => $data['company_id'],
            'team_id' => $team->id,
            'action' => 'employee_added_to_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        LogEmployeeAudit::dispatch([
            'company_id' => $data['company_id'],
            'employee_id' => $employee->id,
            'action' => 'employee_added_to_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        $employee->refresh();

        return $employee;
    }
}
