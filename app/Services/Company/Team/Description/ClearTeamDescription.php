<?php

namespace App\Services\Company\Team\Description;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class ClearTeamDescription extends BaseService
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
        ];
    }

    /**
     * Clear a team's description.
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
            ->asNormalUser()
            ->canExecuteService();

        $team = $this->validateTeamBelongsToCompany($data);

        $team->description = null;
        $team->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_description_cleared',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'description_cleared',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');

        return $team;
    }
}
