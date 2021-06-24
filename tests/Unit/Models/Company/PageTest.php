<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Page;
use App\Models\Company\Pageview;
use App\Models\Company\PageRevision;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_wiki(): void
    {
        $page = Page::factory()->create([]);
        $this->assertTrue($page->wiki()->exists());
    }

    /** @test */
    public function it_has_many_revisions(): void
    {
        $page = Page::factory()->create([]);
        PageRevision::factory()->create([
            'page_id' => $page->id,
        ]);
        $this->assertTrue($page->revisions()->exists());
    }

    /** @test */
    public function it_has_many_pageviews(): void
    {
        $page = Page::factory()->create([]);
        Pageview::factory()->create([
            'page_id' => $page->id,
        ]);
        $this->assertTrue($page->pageviews()->exists());
    }

    /** @test */
    public function it_gets_the_original_author(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $page = Page::factory()->create([]);
        $revisionA = PageRevision::factory()->create([
            'page_id' => $page->id,
            'created_at' => Carbon::now()->subMonths(2),
        ]);
        PageRevision::factory()->create([
            'page_id' => $page->id,
            'created_at' => Carbon::now()->subMonths(1),
        ]);
        PageRevision::factory()->create([
            'page_id' => $page->id,
            'created_at' => Carbon::now(),
        ]);

        $this->assertEquals(
            [
                'id' => $revisionA->employee->id,
                'avatar' => ImageHelper::getAvatar($revisionA->employee, 10),
                'name' => $revisionA->employee->name,
                'url' => env('APP_URL') . '/' . $revisionA->employee->company_id . '/employees/' . $revisionA->employee->id,
                'created_at' => 'Nov 01, 2017',
            ],
            $page->getOriginalAuthor(10)
        );
    }

    /** @test */
    public function it_gets_the_most_recent_author(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $page = Page::factory()->create([]);
        PageRevision::factory()->create([
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

        $this->assertEquals(
            [
                'id' => $revisionC->employee->id,
                'avatar' => ImageHelper::getAvatar($revisionC->employee, 10),
                'name' => $revisionC->employee->name,
                'url' => env('APP_URL') . '/' . $revisionC->employee->company_id . '/employees/' . $revisionC->employee->id,
                'created_at' => 'Jan 01, 2018',
            ],
            $page->getMostRecentAuthor(10)
        );
    }
}
