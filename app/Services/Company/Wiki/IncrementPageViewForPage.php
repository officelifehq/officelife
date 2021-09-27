<?php

namespace App\Services\Company\Wiki;

use App\Models\Company\Page;
use App\Services\BaseService;
use App\Models\Company\Pageview;

class IncrementPageViewForPage extends BaseService
{
    protected array $data;
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
            'page_id' => 'required|integer|exists:pages,id',
        ];
    }

    /**
     * Increment the counter showing the number of times the page has been seen.
     * This is an internal service that should only be called inside another
     * service.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->increasePageviewCounter();
        $this->logPageview();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->page = Page::findOrFail($this->data['page_id']);
    }

    private function increasePageviewCounter(): void
    {
        $this->page->increment('pageviews_counter');
    }

    private function logPageview(): void
    {
        Pageview::create([
            'page_id' => $this->page->id,
            'employee_id' => $this->author->id,
            'employee_name' => $this->author->name,
        ]);
    }
}
