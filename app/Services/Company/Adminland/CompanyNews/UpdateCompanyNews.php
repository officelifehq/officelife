<?php

namespace App\Services\Company\Adminland\CompanyNews;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\CompanyNews;

class UpdateCompanyNews extends BaseService
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
            'company_news_id' => 'required|integer|exists:company_news,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
        ];
    }

    /**
     * Update a company news.
     *
     * @param array $data
     *
     * @return CompanyNews
     */
    public function execute(array $data): CompanyNews
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $news = CompanyNews::where('company_id', $data['company_id'])
            ->findOrFail($data['company_news_id']);

        $oldNewsTitle = $news->title;

        CompanyNews::where('id', $news->id)
            ->update([
                'title' => $data['title'],
                'content' => $data['content'],
            ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'company_news_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_news_id' => $news->id,
                'company_news_title' => $data['title'],
                'company_news_old_title' => $oldNewsTitle,
            ]),
        ])->onQueue('low');

        $news->refresh();

        return $news;
    }
}
