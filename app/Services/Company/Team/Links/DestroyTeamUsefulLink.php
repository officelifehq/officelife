<?php

namespace App\Services\Company\Team\Links;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\TeamUsefulLink;

class DestroyTeamUsefulLink extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'company_id' => 'required|integer|exists:companies,id',
            'team_useful_link_id' => 'required|integer|exists:team_useful_links,id',
        ];
    }

    /**
     * Destroy a team useful link.
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
            ->asNormalUser()
            ->canExecuteService();

        $link = TeamUsefulLink::findOrFail($data['team_useful_link_id']);

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($link->team_id);

        $link->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_useful_link_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'link_name' => $link->label,
                'team_id' => $link->team->id,
                'team_name' => $link->team->name,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'useful_link_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'link_name' => $link->label,
            ]),
        ])->onQueue('low');

        return true;
    }
}
