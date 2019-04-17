<?php

namespace App\Services\Adminland\Team;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Adminland\Company\LogAction;

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
        ];
    }

    /**
     * Remove an employee from a team.
     *
     * @param array $data
     * @return Team
     */
    public function execute(array $data): Team
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

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'user_removed_from_team',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_email' => $employee->user->email,
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
        ]);

        return $team;
    }
}
