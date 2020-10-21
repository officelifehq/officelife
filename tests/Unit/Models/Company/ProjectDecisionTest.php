<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectDecisionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $decision = factory(ProjectDecision::class)->create([]);
        $this->assertTrue($decision->project()->exists());
    }

    /** @test */
    public function it_belongs_to_a_employee(): void
    {
        $decision = factory(ProjectDecision::class)->create([]);
        $this->assertTrue($decision->author()->exists());
    }

    /** @test */
    public function it_has_many_deciders(): void
    {
        $decision = factory(ProjectDecision::class)->create([]);
        $michael = $this->createAdministrator();

        $decision->deciders()->attach([$michael->id]);
        $this->assertTrue($decision->deciders()->exists());
    }
}
