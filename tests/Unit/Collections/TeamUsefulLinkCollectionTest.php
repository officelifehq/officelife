<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamUsefulLink;
use App\Http\Collections\TeamUsefulLinkCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamUsefulLinkCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $sales = Team::factory()->create([]);
        TeamUsefulLink::factory()->count(2)->create([
            'team_id' => $sales->id,
        ]);

        $links = $sales->links()->orderBy('created_at', 'desc')->get();
        $collection = TeamUsefulLinkCollection::prepare($links);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
