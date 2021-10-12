<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectSprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectSprintTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $sprint = ProjectSprint::factory()->create();
        $this->assertTrue($sprint->project()->exists());
    }
}
