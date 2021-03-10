<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use App\Http\Collections\TeamLogCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamLogCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $sales = Team::factory()->create([]);
        TeamLog::factory()->count(2)->create([
            'team_id' => $sales->id,
        ]);

        $logs = $sales->logs()->with('author')->get();
        $collection = TeamLogCollection::prepare($logs);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
