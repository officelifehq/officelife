<?php

namespace App\Services\Company\Wiki;

use App\Models\Company\Page;
use App\Models\Company\Wiki;
use App\Services\BaseService;
use App\Models\Company\PageRevision;

class CreatePageRevision extends BaseService
{
    protected array $data;
    protected Wiki $wiki;
    protected Page $page;
    protected PageRevision $pageRevision;

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
        ];
    }

    /**
     * Create a page revision for the given page.
     *
     * @param array $data
     * @return PageRevision
     */
    public function execute(array $data): PageRevision
    {
        $this->data = $data;
        $this->validate();
        $this->createPageRevision();

        return $this->pageRevision;
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

    private function createPageRevision(): void
    {
        $this->pageRevision = PageRevision::create([
            'page_id' => $this->data['page_id'],
            'employee_id' => $this->author->id,
            'employee_name' => $this->author->name,
            'title' => $this->page->title,
            'content' => $this->page->content,
        ]);
    }
}
