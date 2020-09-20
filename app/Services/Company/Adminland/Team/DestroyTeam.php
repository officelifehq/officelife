<?php

namespace App\Services\Company\Adminland\Team;

use Carbon\Carbon;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class DestroyTeam extends BaseService
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
     * Destroy a team.
     *
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $team = $this->validateTeamBelongsToCompany($data);

        $team->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');

        return true;
    }
}
