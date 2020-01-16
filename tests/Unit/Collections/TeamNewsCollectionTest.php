<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamNews;
use App\Http\Collections\TeamNewsCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamNewsCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $sales = factory(Team::class)->create([]);
        factory(TeamNews::class, 2)->create([
            'team_id' => $sales->id,
        ]);

        $news = $sales->news()->orderBy('created_at', 'desc')->get();
        $collection = TeamNewsCollection::prepare($news);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
