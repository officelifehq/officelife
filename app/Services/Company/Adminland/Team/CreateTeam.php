<?php

namespace App\Services\Company\Adminland\Team;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Exceptions\TeamNameNotUniqueException;

class CreateTeam extends BaseService
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
            'name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a team.
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

        $this->verifyTeamNameUniqueness($data);

        $team = $this->save($data);

        $this->log($data, $author, $team);

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
     * Create the team.
     *
     * @param array $data
     * @return Team
     */
    private function save(array $data): Team
    {
        $team = Team::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);

        return $team;
    }

    /**
     * Add audit logs.
     *
     * @param array $data
     * @param Employee $author
     * @param Team $team
     * @return void
     */
    private function log(array $data, Employee $author, Team $team): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_created',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'team_created',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
