<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\AskMeAnythingQuestion;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AskMeAnythingQuestionTest extends TestCase
{
    use DatabaseTransactions;

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
