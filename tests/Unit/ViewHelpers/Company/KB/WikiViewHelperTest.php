<?php

namespace Tests\Unit\ViewHelpers\Company\KB;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use Illuminate\Support\Facades\DB;
use App\Http\ViewHelpers\Company\KB\WikiViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WikiViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_list_of_wikis(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);

        $array = WikiViewHelper::index($michael->company);
        $this->assertEquals(1, count($array['data']));

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/kb/create',
            $array['url_create']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $wiki->id,
                    'title' => $wiki->title,
                    'count' => 1,
                    'most_recent_page' => [
                        'id' => $page->id,
                        'title' => $page->title,
                        'url' => env('APP_URL').'/'.$michael->company_id. '/company/kb/'.$wiki->id.'/pages/'.$page->id,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id. '/company/kb/'.$wiki->id,
                ],
            ],
            $array['data']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_list_of_most_recent_pages(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);

        $pages = DB::table('pages')
            ->join('wikis', 'pages.wiki_id', '=', 'wikis.id')
            ->select('pages.id', 'pages.title', 'pages.content', 'wikis.title as wiki_title', 'wikis.id as wiki_id')
            ->where('wikis.company_id', $michael->company->id)
            ->orderBy('pages.updated_at', 'desc')
            ->paginate(10);

        $array = WikiViewHelper::pages($pages, $michael->company);

        $this->assertEquals(1, count($array['data']));

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/kb/create',
            $array['url_create']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'content' => Str::limit($page->content, 20),
                    'wiki' => [
                        'id' => $wiki->id,
                        'title' => $wiki->title,
                        'url' => env('APP_URL').'/'.$michael->company_id.'/company/kb/'.$wiki->id,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/kb/'.$wiki->id.'/pages/'.$page->id,
                ],
            ],
            $array['data']->toArray()
        );
    }
}
