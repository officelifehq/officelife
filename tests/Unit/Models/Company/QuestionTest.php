<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Answer;
use App\Models\Company\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $question = factory(Question::class)->create([]);
        $this->assertTrue($question->company()->exists());
    }

    /** @test */
    public function it_has_many_answers(): void
    {
        $question = factory(Question::class)->create();
        factory(Answer::class, 2)->create([
            'question_id' => $question->id,
        ]);

        $this->assertTrue($question->answers()->exists());
    }
}
