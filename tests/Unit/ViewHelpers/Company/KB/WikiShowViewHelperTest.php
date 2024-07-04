<?php

namespace Tests\Unit\ViewHelpers\Company\KB;

use App\Http\ViewHelpers\Company\KB\WikiShowViewHelper;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use Tests\TestCase;

class WikiShowViewHelperTest extends TestCase
{
    /** @test */
    public function it_gets_the_detail_of_a_wiki(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);

        $array = WikiShowViewHelper::show($wiki, $michael->company);

        $this->assertEquals(
            $wiki->id,
            $array['id']
        );

        $this->assertEquals(
            $wiki->title,
            $array['title']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'first_revision' => null,
                    'last_revision' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/kb/'.$wiki->id.'/pages/'.$page->id,
                ],
            ],
            $array['pages']->toArray()
        );

        $this->assertEquals(
            [
                'create' => env('APP_URL').'/'.$michael->company_id.'/company/kb/'.$wiki->id.'/pages/create',
                'edit' => env('APP_URL').'/'.$michael->company_id.'/company/kb/'.$wiki->id.'/edit',
            ],
            $array['urls']
        );
    }
}
