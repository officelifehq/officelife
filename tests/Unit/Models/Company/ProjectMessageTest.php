<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ProjectDecision;
use Tests\TestCase;

class ProjectMessageTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $decision = ProjectDecision::factory()->create([]);
        $this->assertTrue($decision->project()->exists());
    }

    /** @test */
    public function it_belongs_to_a_employee(): void
    {
        $decision = ProjectDecision::factory()->create([]);
        $this->assertTrue($decision->author()->exists());
    }
}
