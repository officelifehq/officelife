<?php

namespace App\Http\ViewHelpers\Company\KB;

use Illuminate\Support\Str;
use App\Models\Company\Wiki;
use App\Models\Company\Company;

class KnowledgeBaseShowViewHelper
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
                'id' => $wiki->id,
                'title' => $wiki->title,
                'content' => Str::limit($wiki->content, 20),
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
                'delete' => route('wikis.destroy', [
                    'company' => $company,
                    'wiki' => $wiki,
                ]),
            ],
        ];
    }
}
