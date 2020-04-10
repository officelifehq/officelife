<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Answer;
use App\Models\Company\Question;
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

    /** @test */
    public function it_returns_an_object(): void
    {
        $michael = $this->createAdministrator();
        $question = factory(Question::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $answer = factory(Answer::class)->create([
            'employee_id' => $michael->id,
            'question_id' => $question->id,
            'body' => 'dunder',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $answer->id,
                'question' => [
                    'id' => $answer->question_id,
                ],
                'employee' => [
                    'id' => $michael->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => $michael->avatar,
                ],
                'body' => 'dunder',
                'created_at' => '2020-01-12 00:00:00',
            ],
            $answer->toObject()
        );
    }
}
