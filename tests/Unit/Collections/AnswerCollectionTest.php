<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Answer;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use App\Http\Collections\AnswerCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnswerCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = factory(Employee::class)->create([]);
        $question = factory(Question::class)->create([
            'company_id' => $michael->company_id,
        ]);
        factory(Answer::class, 2)->create([
            'question_id' => $question->id,
        ]);

        $answers = $question->answers()->get();
        $collection = AnswerCollection::prepare($answers);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
