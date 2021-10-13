<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectLabel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectLabelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $label = ProjectLabel::factory()->create();
        $this->assertTrue($label->project()->exists());
    }

    /** @test */
    public function it_has_many_issues(): void
    {
        $issue = ProjectIssue::factory()->create();
        $label = ProjectLabel::factory()->create();
        $label->issues()->sync([$issue->id]);

        $this->assertTrue($label->issues()->exists());
    }
}
