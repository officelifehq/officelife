<?php

namespace App\Services\Company\Team;

use App\Models\User\User;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Services\BaseService;
use App\Services\Company\Company\LogAction;

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
     * Add a user to a team.
     *
     * @param array $data
     * @return Team
     */
    public function execute(array $data) : Team
    {
        $this->validate($data);

        $author = $this->validatePermissions($data['author_id'], 'hr');

        $user = User::where('company_id', $data['company_id'])
            ->findOrFail($data['user_id']);

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        $team->users()->attach($data['user_id'], ['company_id' => $data['company_id']]);

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'user_added_to_team',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'team_id' => $team->id,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $team;
    }
}
