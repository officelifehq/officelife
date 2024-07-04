<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\CandidateStageParticipant;
use Tests\TestCase;

class CandidateStageParticipantTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_candidate_stage(): void
    {
        $participant = CandidateStageParticipant::factory()->create();
        $this->assertTrue($participant->candidateStage()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $participant = CandidateStageParticipant::factory()->create();
        $this->assertTrue($participant->participant()->exists());
    }
}
