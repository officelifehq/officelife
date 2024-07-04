<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ProjectDecision;
use Tests\TestCase;

class ProjectDecisionTest extends TestCase
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

    /** @test */
    public function it_has_many_deciders(): void
    {
        $decision = ProjectDecision::factory()->create([]);
        $michael = $this->createAdministrator();

        $decision->deciders()->attach([$michael->id]);
        $this->assertTrue($decision->deciders()->exists());
    }
}
