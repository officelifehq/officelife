<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Pageview;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageviewTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_page(): void
    {
        $pageview = Pageview::factory()->create([]);
        $this->assertTrue($pageview->page()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $pageview = Pageview::factory()->create([]);
        $this->assertTrue($pageview->employee()->exists());
    }
}
