<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CandidateStage;
use App\Models\Company\CandidateStageNote;
use App\Models\Company\CandidateStageParticipant;
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

    /** @test */
    public function it_has_many_candidate_stage_notes(): void
    {
        $stage = CandidateStage::factory()->create();
        CandidateStageNote::factory()->count(2)->create([
            'candidate_stage_id' => $stage->id,
        ]);

        $this->assertTrue($stage->notes()->exists());
    }

    /** @test */
    public function it_has_many_candidate_stage_participants(): void
    {
        $stage = CandidateStage::factory()->create();
        CandidateStageParticipant::factory()->count(2)->create([
            'candidate_stage_id' => $stage->id,
        ]);

        $this->assertTrue($stage->participants()->exists());
    }
}
