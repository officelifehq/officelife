<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\AskMeAnythingQuestion;
use Tests\TestCase;

class AskMeAnythingQuestionTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_session(): void
    {
        $question = AskMeAnythingQuestion::factory()->create([]);
        $this->assertTrue($question->session()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $question = AskMeAnythingQuestion::factory()->create([]);
        $this->assertTrue($question->employee()->exists());
    }
}
