<?php

namespace App\Services\Company\Adminland\Team;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Exceptions\TeamNameNotUniqueException;

class CreateTeam extends BaseService
{
    private Team $team;

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
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Create a team.
     *
     * @param array $data
     *
     * @return Team
     */
    public function execute(array $data): Team
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->verifyTeamNameUniqueness($data);

        $this->create($data);

        $this->log($data);

        return $this->team;
    }

    /**
     * Make sure the team's name is unique in the company.
     *
     * @param array $data
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
     * Create the team.
     *
     * @param array $data
     */
    private function create(array $data): void
    {
        $this->team = Team::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);
    }

    /**
     * Add audit logs.
     *
     * @param array $data
     */
    private function log(array $data): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $this->team->id,
                'team_name' => $this->team->name,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $this->team->id,
            'action' => 'team_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $this->team->id,
                'team_name' => $this->team->name,
            ]),
        ])->onQueue('low');
    }
}
