<?php

namespace Tests\Unit\ViewHelpers\Company\KB;

use App\Http\ViewHelpers\Company\KB\PageEditViewHelper;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use Carbon\Carbon;
use Tests\TestCase;

class PageEditViewHelperTest extends TestCase
{
    /** @test */
    public function it_gets_the_detail_of_a_page(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);

        $array = PageEditViewHelper::show($page);

        $this->assertEquals(
            [
                'id' => $page->id,
                'title' => $page->title,
                'content' => $page->content,
                'wiki' => [
                    'id' => $page->wiki_id,
                ],
            ],
            $array
        );
    }
}
