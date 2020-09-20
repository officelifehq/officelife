<?php

namespace App\Services\Company\Team\Description;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class SetTeamDescription extends BaseService
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
            'description' => 'required|string|max:65535',
        ];
    }

    /**
     * Set a team's description.
     * The description should be saved as unparsed markdown content, and fetched
     * as unparsed markdown content. The UI is responsible for parsing and
     * displaying the proper content.
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

        $team->description = $data['description'];
        $team->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_description_set',
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
            'action' => 'description_set',
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
