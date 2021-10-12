<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectIssueTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $issue = ProjectIssue::factory()->create();
        $this->assertTrue($issue->project()->exists());
    }

    /** @test */
    public function it_belongs_to_a_reporter(): void
    {
        $issue = ProjectIssue::factory()->create();
        $this->assertTrue($issue->reporter()->exists());
    }

    /** @test */
    public function it_belongs_to_a_issue_type(): void
    {
        $issue = ProjectIssue::factory()->create();
        $this->assertTrue($issue->type()->exists());
    }
}
