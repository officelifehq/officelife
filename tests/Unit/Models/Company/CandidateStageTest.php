<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CandidateStage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CandidateStageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_candidate(): void
    {
        $stage = CandidateStage::factory()->create();
        $this->assertTrue($stage->candidate()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $stage = CandidateStage::factory()->create();
        $this->assertTrue($stage->decider()->exists());
    }
}
