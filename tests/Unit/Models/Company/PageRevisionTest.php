<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\PageRevision;
use Tests\TestCase;

class PageRevisionTest extends TestCase
{
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
