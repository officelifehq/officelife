<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ProjectMemberActivity;
use Tests\TestCase;

class ProjectMemberActivityTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $activity = ProjectMemberActivity::factory()->create();
        $this->assertTrue($activity->project()->exists());
    }

    /** @test */
    public function it_has_one_employee(): void
    {
        $activity = ProjectMemberActivity::factory()->create();
        $this->assertTrue($activity->employee()->exists());
    }
}
