<?php

namespace App\Services\Company\Wiki;

use Carbon\Carbon;
use App\Models\Company\Wiki;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class DestroyWiki extends BaseService
{
    protected array $data;
    protected Wiki $wiki;

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
            'wiki_id' => 'required|integer|exists:wikis,id',
        ];
    }

    /**
     * Delete a wiki.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->wiki = Wiki::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['wiki_id']);
    }

    private function destroy(): void
    {
        $this->wiki->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'wiki_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'wiki_title' => $this->wiki->title,
            ]),
        ])->onQueue('low');
    }
}
