<?php

namespace App\Services\Company\Team\TeamNews;

use Exception;
use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\TeamNews;

class DestroyTeamNews extends BaseService
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
            'team_news_id' => 'required|integer|exists:team_news,id',
        ];
    }

    /**
     * Destroy a team news.
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

        $news = TeamNews::findOrFail($data['team_news_id']);
        $team = $news->team;

        if ($team->company_id != $data['company_id']) {
            throw new Exception();
        }

        $news->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_news_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $team->id,
                'team_name' => $team->name,
                'team_news_title' => $news->title,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'team_news_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_news_title' => $news->title,
            ]),
        ])->onQueue('low');

        return true;
    }
}
