<?php

namespace App\Services\Company\Adminland\Team;

use App\Exceptions\TeamNameNotUniqueException;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Services\BaseService;
use Carbon\Carbon;

class UpdateTeam extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'team_id' => 'required|integer|exists:teams,id',
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Update a team.
     */
    public function execute(array $data): Team
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $team = $this->validateTeamBelongsToCompany($data);

        $this->verifyTeamNameUniqueness($data);

        $oldName = $team->name;

        $team->update([
            'name' => $data['name'],
        ]);

        $this->log($data, $oldName);

        return $team;
    }

    /**
     * Make sure the team's name is unique in the company.
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
     */
    private function log(array $data, string $oldName): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $data['team_id'],
                'team_old_name' => $oldName,
                'team_new_name' => $data['name'],
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $data['team_id'],
            'action' => 'team_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_old_name' => $oldName,
                'team_new_name' => $data['name'],
            ]),
        ])->onQueue('low');
    }
}
