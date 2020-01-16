<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Position;
use App\Http\Collections\PositionCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PositionCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $positionA = factory(Position::class)->create([]);
        factory(Position::class)->create([
            'company_id' => $positionA->company_id,
        ]);

        $positions = $positionA->company->positions()->orderBy('title', 'asc')->get();
        $collection = PositionCollection::prepare($positions);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
