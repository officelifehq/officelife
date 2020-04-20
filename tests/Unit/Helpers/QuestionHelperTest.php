<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Models\Company\Answer;
use App\Helpers\QuestionHelper;
use App\Models\Company\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_answer_of_the_question_made_by_the_employee(): void
    {
        $michael = $this->createAdministrator();
        $answer = factory(Answer::class)->create([
            'employee_id' => $michael->id,
        ]);

        $response = QuestionHelper::getAnswer($answer->question, $michael);

        $this->assertInstanceOf(
            Answer::class,
            $response
        );

        $this->assertEquals(
            $response->id,
            $answer->id
        );
    }

    /** @test */
    public function it_gets_an_empty_array_if_the_employee_hasnt_answered_the_question(): void
    {
        $michael = $this->createAdministrator();
        $question = factory(Question::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->assertNull(
            QuestionHelper::getAnswer($question, $michael)
        );
    }
}
