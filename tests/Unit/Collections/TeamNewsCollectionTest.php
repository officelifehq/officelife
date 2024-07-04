<?php

namespace Tests\Unit\Collections;

use App\Http\Collections\TeamNewsCollection;
use App\Models\Company\Team;
use App\Models\Company\TeamNews;
use Tests\TestCase;

class TeamNewsCollectionTest extends TestCase
{
    /** @test */
    public function it_returns_a_collection(): void
    {
        $sales = Team::factory()->create([]);
        TeamNews::factory()->count(2)->create([
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
