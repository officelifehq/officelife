<?php

namespace App\Http\ViewHelpers\Company\KB;

use App\Models\Company\Wiki;
use App\Models\Company\Company;

class WikiShowViewHelper
{
    /**
     * Get the detail of a wiki.
     *
     * @param Wiki $wiki
     * @param Company $company
     * @return array
     */
    public static function show(Wiki $wiki, Company $company): array
    {
        $pages = $wiki->pages()
            ->latest('updated_at')
            ->get();

        $pagesCollection = collect([]);
        foreach ($pages as $page) {
            $pagesCollection->push([
                'id' => $page->id,
                'title' => $page->title,
                'first_revision' => $page->getOriginalAuthor(),
                'last_revision' => $page->getMostRecentAuthor(),
                'url' => route('pages.show', [
                    'company' => $company,
                    'wiki' => $wiki,
                    'page' => $page,
                ]),
            ]);
        }

        return [
            'id' => $wiki->id,
            'title' => $wiki->title,
            'pages' => $pagesCollection,
            'urls' => [
                'create' => route('pages.new', [
                    'company' => $company,
                    'wiki' => $wiki,
                ]),
                'edit' => route('wikis.edit', [
                    'company' => $company,
                    'wiki' => $wiki,
                ]),
            ],
        ];
    }
}
