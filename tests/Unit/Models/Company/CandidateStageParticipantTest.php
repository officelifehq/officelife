<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CandidateStageParticipant;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CandidateStageParticipantTest extends TestCase
{
    use DatabaseTransactions;

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
