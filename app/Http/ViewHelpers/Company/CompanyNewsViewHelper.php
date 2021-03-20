<?php

namespace App\Http\ViewHelpers\Company;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class CompanyNewsViewHelper
{
    /**
     * Get all the company news in the company.
     *
     * @param Company $company
     *
     * @return Collection|null
     */
    public static function index(Company $company): ?Collection
    {
        $allNews = $company->news()->with('author')->orderBy('id', 'desc')->get();

        $newsCollection = collect([]);
        foreach ($allNews as $news) {
            $author = $news->author;

            $newsCollection->push([
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
                'author' => $author ? [
                    'id' => $author->id,
                    'name' => $author->name,
                    'avatar' => ImageHelper::getAvatar($author, 22),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $author,
                    ]),
                ] : $news->author_name,
                'written_at' => DateHelper::formatDate($news->created_at),
            ]);
        }

        return $newsCollection;
    }
}
