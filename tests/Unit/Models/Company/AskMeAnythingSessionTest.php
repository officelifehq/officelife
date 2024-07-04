<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\AskMeAnythingQuestion;
use App\Models\Company\AskMeAnythingSession;
use Tests\TestCase;

class AskMeAnythingSessionTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $ama = AskMeAnythingSession::factory()->create([]);
        $this->assertTrue($ama->company()->exists());
    }

    /** @test */
    public function it_has_many_questions(): void
    {
        $ama = AskMeAnythingSession::factory()->create([]);
        AskMeAnythingQuestion::factory()->create([
            'ask_me_anything_session_id' => $ama->id,
        ]);
        $this->assertTrue($ama->questions()->exists());
    }
}
