<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WikiTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $wiki = Wiki::factory()->create([]);
        $this->assertTrue($wiki->company()->exists());
    }

    /** @test */
    public function it_has_many_pages(): void
    {
        $wiki = Wiki::factory()->create();
        Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);

        $this->assertTrue($wiki->pages()->exists());
    }
}
