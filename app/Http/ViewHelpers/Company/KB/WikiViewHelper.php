<?php

namespace App\Http\ViewHelpers\Company\KB;

use Illuminate\Support\Str;
use App\Models\Company\Company;

class WikiViewHelper
{
    /**
     * Get all the wikis in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function index(Company $company): array
    {
        $wikis = $company->wikis()
            ->with('pages')
            ->latest('id')
            ->get();

        $wikisCollection = collect([]);
        foreach ($wikis as $wiki) {
            $mostRecentPage = $wiki->pages()
                ->latest()
                ->first();

            $count = $wiki->pages->count();

            $wikisCollection->push([
                'id' => $wiki->id,
                'title' => $wiki->title,
                'count' => $count,
                'most_recent_page' => $mostRecentPage ? [
                    'id' => $mostRecentPage->id,
                    'title' => $mostRecentPage->title,
                    'url' => route('pages.show', [
                        'company' => $company,
                        'wiki' => $wiki,
                        'page' => $mostRecentPage,
                    ]),
                ] : null,
                'url' => route('wikis.show', [
                    'company' => $company,
                    'wiki' => $wiki,
                ]),
            ]);
        }

        return [
            'data' => $wikisCollection,
            'url_create' => route('wikis.new', [
                'company' => $company,
            ]),
        ];
    }

    /**
     * Get all the pages in the company sorted by latest edited at.
     *
     * @param mixed $pages
     * @param Company $company
     * @return array
     */
    public static function pages($pages, Company $company): array
    {
        $pagesCollection = collect([]);
        foreach ($pages as $page) {
            $pagesCollection->push([
                'id' => $page->id,
                'title' => $page->title,
                'content' => Str::limit($page->content, 20),
                'wiki' => [
                    'id' => $page->wiki_id,
                    'title' => $page->wiki_title,
                    'url' => route('wikis.show', [
                        'company' => $company,
                        'wiki' => $page->wiki_id,
                    ]),
                ],
                'url' => route('pages.show', [
                    'company' => $company,
                    'wiki' => $page->wiki_id,
                    'page' => $page->id,
                ]),
            ]);
        }

        return [
            'data' => $pagesCollection,
            'url_create' => route('wikis.new', [
                'company' => $company,
            ]),
        ];
    }
}
