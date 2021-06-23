<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Page;
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
}
