<?php

namespace Tests\Unit\ViewHelpers\Company\KB;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\KB\KnowledgeBaseShowViewHelper;

class KnowledgeBaseShowViewHelperTest extends TestCase
{
    use DatabaseTransactions;

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

        $array = KnowledgeBaseShowViewHelper::show($wiki, $michael->company);

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
                    'id' => $wiki->id,
                    'title' => $wiki->title,
                    'content' => Str::limit($wiki->content, 20),
                    'url' => env('APP_URL').'/'.$michael->company_id. '/company/kb/'.$wiki->id.'/pages/'.$page->id,
                ],
            ],
            $array['pages']->toArray()
        );

        $this->assertEquals(
            [
                'create' => env('APP_URL').'/'.$michael->company_id. '/company/kb/'.$wiki->id.'/pages/create',
                'delete' => env('APP_URL').'/'.$michael->company_id. '/company/kb/'.$wiki->id,
            ],
            $array['urls']
        );
    }
}
