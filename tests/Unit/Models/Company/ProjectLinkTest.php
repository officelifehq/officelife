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
        $projectLink = factory(ProjectLink::class)->create([]);
        $this->assertTrue($projectLink->project()->exists());
    }
}
