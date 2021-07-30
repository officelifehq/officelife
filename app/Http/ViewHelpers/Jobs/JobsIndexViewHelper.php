<?php

namespace App\Http\ViewHelpers\Jobs;

use App\Models\Company\Page;

class JobsIndexViewHelper
{
    /**
     * Get all the companies in the instance.
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
