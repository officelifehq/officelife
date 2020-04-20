<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Question;
use App\Http\Collections\QuestionCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        factory(Question::class, 2)->create([]);
        $questions = Question::all();
        $collection = QuestionCollection::prepare($questions);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
