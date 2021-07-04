<?php

namespace App\Http\ViewHelpers\Company\KB;

use App\Models\Company\Page;
use App\Helpers\StringHelper;

class PageShowViewHelper
{
    /**
     * Get the detail of a page.
     *
     * @param Page $page
     * @return array
     */
    public static function show(Page $page): array
    {
        $numberOfRevisions = $page->revisions()->count();
        $mostRecentAuthor = $page->getMostRecentAuthor(25);
        $originalAuthor = $page->getOriginalAuthor(25);

        return [
            'id' => $page->id,
            'pageviews_counter' => $page->pageviews_counter,
            'title' => $page->title,
            'content' => StringHelper::parse($page->content),
            'number_of_revisions' => $numberOfRevisions,
            'original_author' => $originalAuthor,
            'most_recent_author' => $mostRecentAuthor,
            'url_edit' => route('pages.edit', [
                'company' => $page->wiki->company,
                'wiki' => $page->wiki,
                'page' => $page,
            ]),
            'wiki' => [
                'id' => $page->wiki_id,
            ],
        ];
    }
}
