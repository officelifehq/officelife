<?php

namespace App\Services\Adminland\Team;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Adminland\Company\LogAction;

class SetTeamLeader extends BaseService
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
            'employee_id' => 'nullable|integer|exists:employees,id',
            'team_id' => 'required|integer|exists:teams,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Set the employee as the team leader.
     * If employee id is null, the team will not have a leader anymore.
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

        if (! is_null($data['employee_id'])) {
            $employee = Employee::where('company_id', $data['company_id'])
                ->findOrFail($data['employee_id']);
        }

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        $team->team_leader_id = $data['employee_id'];
        $team->save();

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'team_leader_assigned',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'leader_id' => $data['employee_id'],
                'team_id' => $team->id,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $team;
    }
}
