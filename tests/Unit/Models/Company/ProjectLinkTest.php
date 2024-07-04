<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ProjectLink;
use Tests\TestCase;

class ProjectLinkTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $projectLink = ProjectLink::factory()->create([]);
        $this->assertTrue($projectLink->project()->exists());
    }
}
