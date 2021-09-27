<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CandidateStageNote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CandidateStageNoteTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_candidate_stage(): void
    {
        $note = CandidateStageNote::factory()->create();
        $this->assertTrue($note->candidateStage()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $note = CandidateStageNote::factory()->create();
        $this->assertTrue($note->author()->exists());
    }
}
