<?php

namespace App\Services\Company\Adminland\Team;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Exceptions\TeamNameNotUniqueException;

class UpdateTeam extends BaseService
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
            'team_id' => 'required|integer|exists:teams,id',
            'name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Update a team.
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
            config('officelife.authorizations.hr')
        );

        $team = $this->validateTeamBelongsToCompany($data);

        $this->verifyTeamNameUniqueness($data);

        $oldName = $team->name;

        $team->update([
            'name' => $data['name'],
        ]);

        $this->log($data, $author, $oldName);

        return $team;
    }

    /**
     * Make sure the team's name is unique in the company.
     *
     * @param array $data
     * @return void
     */
    private function verifyTeamNameUniqueness(array $data): void
    {
        $teams = Team::select('name')
            ->where('company_id', $data['company_id'])
            ->get();

        $teams = $teams->filter(function ($team) use ($data) {
            return strtolower(trim($team->name)) === strtolower(trim($data['name']));
        });

        if ($teams->count() > 0) {
            throw new TeamNameNotUniqueException(trans('app.error_team_name_not_unique'));
        }
    }

    /**
     * Add audit logs.
     *
     * @param array $data
     * @param Employee $author
     * @param string $oldName
     * @return void
     */
    private function log(array $data, Employee $author, string $oldName): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_updated',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $data['team_id'],
                'team_old_name' => $oldName,
                'team_new_name' => $data['name'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $data['team_id'],
            'action' => 'team_updated',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_old_name' => $oldName,
                'team_new_name' => $data['name'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
