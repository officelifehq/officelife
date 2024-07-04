<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Answer;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_question(): void
    {
        $answer = Answer::factory()->create([]);
        $this->assertTrue($answer->question()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $answer = Answer::factory()->create([]);
        $this->assertTrue($answer->employee()->exists());
    }
}
