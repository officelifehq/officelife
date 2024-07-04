<?php

namespace Tests\Unit\Collections;

use App\Http\Collections\QuestionCollection;
use App\Models\Company\Question;
use Tests\TestCase;

class QuestionCollectionTest extends TestCase
{
    /** @test */
    public function it_returns_a_collection(): void
    {
        Question::factory()->count(2)->create([]);
        $questions = Question::all();
        $collection = QuestionCollection::prepare($questions);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
