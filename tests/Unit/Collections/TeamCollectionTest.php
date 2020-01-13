<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Http\Collections\TeamCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $teamA = factory(Team::class)->create([]);
        factory(Team::class)->create([
            'company_id' => $teamA->company_id,
        ]);

        $teams = $teamA->company->teams()->orderBy('name', 'asc')->get();
        $collection = TeamCollection::prepare($teams);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
