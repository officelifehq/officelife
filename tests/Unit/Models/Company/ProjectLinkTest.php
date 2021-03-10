<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectLinkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $projectLink = ProjectLink::factory()->create([]);
        $this->assertTrue($projectLink->project()->exists());
    }
}
