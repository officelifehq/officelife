<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\PageRevision;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageRevisionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_page(): void
    {
        $pageRevision = PageRevision::factory()->create([]);
        $this->assertTrue($pageRevision->page()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $pageRevision = PageRevision::factory()->create([]);
        $this->assertTrue($pageRevision->employee()->exists());
    }
}
