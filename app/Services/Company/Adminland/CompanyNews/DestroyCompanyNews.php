<?php

namespace App\Services\Company\Adminland\CompanyNews;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;

class DestroyCompanyNews extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'company_news_id' => 'required|integer|exists:company_news,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Destroy a company news.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data) : bool
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $news = CompanyNews::where('company_id', $data['company_id'])
            ->findOrFail($data['company_news_id']);

        $author = Employee::where('id', $data['author_id'])
            ->where('company_id', $data['company_id'])
            ->firstOrFail();

        $news->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'company_news_destroyed',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_news_title' => $news->title,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return true;
    }
}
