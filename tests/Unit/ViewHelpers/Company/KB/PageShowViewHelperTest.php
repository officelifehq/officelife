<?php

namespace Tests\Unit\ViewHelpers\Company\KB;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use App\Helpers\StringHelper;
use App\Models\Company\PageRevision;
use App\Http\ViewHelpers\Company\KB\PageShowViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageShowViewHelperTest extends TestCase
{
    use DatabaseTransactions;

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
        $revisionA = PageRevision::factory()->create([
            'page_id' => $page->id,
            'created_at' => Carbon::now()->subMonths(2),
        ]);
        PageRevision::factory()->create([
            'page_id' => $page->id,
            'created_at' => Carbon::now()->subMonths(1),
        ]);
        $revisionC = PageRevision::factory()->create([
            'page_id' => $page->id,
            'created_at' => Carbon::now(),
        ]);

        $array = PageShowViewHelper::show($page);

        $this->assertEquals(
            [
                'id' => $page->id,
                'pageviews_counter' => $page->pageviews_counter,
                'title' => $page->title,
                'content' => StringHelper::parse($page->content),
                'number_of_revisions' => 3,
                'original_author' => [
                    'id' => $revisionA->employee->id,
                    'avatar' => ImageHelper::getAvatar($revisionA->employee, 25),
                    'name' => $revisionA->employee->name,
                    'url' => env('APP_URL').'/'.$revisionA->employee->company_id.'/employees/'.$revisionA->employee->id,
                    'created_at' => 'Nov 01, 2017',
                ],
                'most_recent_author' => [
                    'id' => $revisionC->employee->id,
                    'avatar' => ImageHelper::getAvatar($revisionC->employee, 25),
                    'name' => $revisionC->employee->name,
                    'url' => env('APP_URL').'/'.$revisionC->employee->company_id.'/employees/'.$revisionC->employee->id,
                    'created_at' => 'Jan 01, 2018',
                ],
                'url_edit' => env('APP_URL').'/'.$wiki->company_id.'/company/kb/'.$wiki->id.'/pages/'.$page->id.'/edit',
                'wiki' => [
                    'id' => $page->wiki_id,
                ],
            ],
            $array
        );
    }
}
