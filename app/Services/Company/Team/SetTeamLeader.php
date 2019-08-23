<?php

namespace App\Services\Company\Team;

use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;

class SetTeamLeader extends BaseService
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
     * Set the employee as the team leader.
     *
     * @param array $data
     * @return Team
     */
    public function execute(array $data) : Team
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

        $team->team_leader_id = $data['employee_id'];
        $team->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_leader_assigned',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'team_leader_id' => $employee->id,
                'team_leader_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'team_leader_assigned',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'team_leader_id' => $employee->id,
                'team_leader_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $team;
    }
}
