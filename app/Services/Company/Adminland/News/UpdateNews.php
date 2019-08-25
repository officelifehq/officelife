<?php

namespace App\Services\Company\Adminland\Position;

use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;

class UpdateNews extends BaseService
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
            'author_id' => 'required|integer|exists:users,id',
            'news_id' => 'required|integer|exists:company_news,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Update a company news.
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

        $news = CompanyNews::where('company_id', $data['company_id'])
            ->findOrFail($data['news_id']);

        $author = Employee::where('user_id', $data['author_id'])
            ->where('company_id', $data['company_id'])
            ->firstOrFail();

        $oldPositionTitle = $position->title;

        $position->title = $data['title'];
        $position->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'position_updated',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'position_id' => $position->id,
                'position_title' => $position->title,
                'position_old_title' => $oldPositionTitle,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        $position->refresh();

        return $position;
    }
}
