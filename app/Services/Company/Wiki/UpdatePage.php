<?php

namespace App\Services\Company\Wiki;

use Carbon\Carbon;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class UpdatePage extends BaseService
{
    protected array $data;
    protected Wiki $wiki;
    protected Page $page;

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
            'page_id' => 'required|integer|exists:pages,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
        ];
    }

    /**
     * Edit a page in a wiki.
     *
     * @param array $data
     * @return Page
     */
    public function execute(array $data): Page
    {
        $this->data = $data;
        $this->validate();
        $this->updatePage();
        $this->createPageRevision();
        $this->log();

        return $this->page;
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

        $this->page = Page::where('wiki_id', $this->data['wiki_id'])
            ->findOrFail($this->data['page_id']);
    }

    private function updatePage(): void
    {
        $this->page->title = $this->data['title'];
        $this->page->content = $this->data['content'];
        $this->page->save();
        $this->page->refresh();
    }

    private function createPageRevision(): void
    {
        (new CreatePageRevision)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'wiki_id' => $this->data['wiki_id'],
            'page_id' => $this->page->id,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'page_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'wiki_id' => $this->wiki->id,
                'wiki_title' => $this->wiki->title,
                'page_id' => $this->page->id,
                'page_title' => $this->page->title,
            ]),
        ])->onQueue('low');
    }
}
