<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectIssue;
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

    /** @test */
    public function it_belongs_to_a_project_board(): void
    {
        $sprint = ProjectSprint::factory()->create();
        $this->assertTrue($sprint->board()->exists());
    }

    /** @test */
    public function it_has_many_issues(): void
    {
        $sprint = ProjectSprint::factory()->create();
        $issue = ProjectIssue::factory()->create();
        $sprint->issues()->sync([$issue->id]);

        $this->assertTrue($sprint->issues()->exists());
    }
}
