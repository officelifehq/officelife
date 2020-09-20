<?php

namespace App\Services\Company\Team\Links;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use Illuminate\Validation\Rule;
use App\Models\Company\TeamUsefulLink;

class UpdateTeamUsefulLink extends BaseService
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
            'team_useful_link_id' => 'required|integer|exists:team_useful_links,id',
            'type' => [
                'required',
                Rule::in([
                    'slack',
                    'email',
                ]),
            ],
            'label' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
        ];
    }

    /**
     * Update a team useful link.
     * A useful link is a link that provides information to someone visiting
     * the team page.
     *
     * @param array $data
     *
     * @return TeamUsefulLink
     */
    public function execute(array $data): TeamUsefulLink
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $link = TeamUsefulLink::findOrFail($data['team_useful_link_id']);

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($link->team_id);

        $link->update([
            'type' => $data['type'],
            'label' => $this->valueOrNull($data, 'label'),
            'url' => $data['url'],
        ]);

        $this->log($data, $link, $team);

        return $link;
    }

    /**
     * Add logs.
     *
     * @param array          $data
     * @param TeamUsefulLink $link
     * @param Team           $team
     */
    private function log(array $data, TeamUsefulLink $link, Team $team): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_useful_link_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'link_id' => $link->id,
                'link_name' => $link->label,
                'team_id' => $link->team->id,
                'team_name' => $link->team->name,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'useful_link_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'link_id' => $link->id,
                'link_name' => $link->label,
            ]),
        ])->onQueue('low');
    }
}
