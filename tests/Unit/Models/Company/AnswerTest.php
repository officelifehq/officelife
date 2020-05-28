<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Answer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnswerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_question(): void
    {
        $answer = factory(Answer::class)->create([]);
        $this->assertTrue($answer->question()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $answer = factory(Answer::class)->create([]);
        $this->assertTrue($answer->employee()->exists());
    }
}
