<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectMessageTest extends TestCase
{
    use DatabaseTransactions;

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
