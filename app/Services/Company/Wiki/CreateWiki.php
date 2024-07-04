<?php

namespace App\Services\Company\Wiki;

use App\Jobs\LogAccountAudit;
use App\Models\Company\Wiki;
use App\Services\BaseService;
use Carbon\Carbon;

class CreateWiki extends BaseService
{
    protected array $data;

    protected Wiki $wiki;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'title' => 'required|string|max:255',
        ];
    }

    /**
     * Create a wiki.
     */
    public function execute(array $data): Wiki
    {
        $this->data = $data;
        $this->validate();
        $this->createWiki();
        $this->log();

        return $this->wiki;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();
    }

    private function createWiki(): void
    {
        $this->wiki = Wiki::create([
            'company_id' => $this->data['company_id'],
            'title' => $this->data['title'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'wiki_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'wiki_id' => $this->wiki->id,
                'wiki_title' => $this->wiki->title,
            ]),
        ])->onQueue('low');
    }
}
