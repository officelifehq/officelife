<?php

namespace App\Services\Company\Adminland\CompanyNews;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;

class CreateCompanyNews extends BaseService
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
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a company news.
     *
     * @param array $data
     * @return CompanyNews
     */
    public function execute(array $data) : CompanyNews
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $author = Employee::where('id', $data['author_id'])
            ->where('company_id', $data['company_id'])
            ->firstOrFail();

        $news = CompanyNews::create([
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'author_name' => $author->name,
            'title' => $data['title'],
            'content' => $data['content'],
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'company_news_created',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'news_id' => $news->id,
                'news_title' => $news->title,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $news;
    }
}
