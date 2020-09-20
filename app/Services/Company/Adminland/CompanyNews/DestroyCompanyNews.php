<?php

namespace App\Services\Company\Adminland\CompanyNews;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\CompanyNews;

class DestroyCompanyNews extends BaseService
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
        ];
    }

    /**
     * Destroy a company news.
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
            ->asAtLeastHR()
            ->canExecuteService();

        $news = CompanyNews::where('company_id', $data['company_id'])
            ->findOrFail($data['company_news_id']);

        $news->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'company_news_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_news_title' => $news->title,
            ]),
        ])->onQueue('low');

        return true;
    }
}
