<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\User\Pronoun;
use App\Http\Collections\PronounCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PronounCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $pronouns = Pronoun::all();
        $collection = PronounCollection::prepare($pronouns);

        $this->assertEquals(
            7,
            $collection->count()
        );
    }
}
