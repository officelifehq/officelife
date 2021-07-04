<?php

namespace App\Http\ViewHelpers\Company\KB;

use App\Models\Company\Page;

class PageEditViewHelper
{
    /**
     * Get the information of the page being edited.
     *
     * @param Page $page
     * @return array
     */
    public static function show(Page $page): array
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'content' => $page->content,
            'wiki' => [
                'id' => $page->wiki_id,
            ],
        ];
    }
}
