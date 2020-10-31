<?php

namespace Tests\Unit\ViewHelpers\Team;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Http\ViewHelpers\Team\TeamRecentShipViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamRecentShipViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_recent_ships(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $featureA = factory(Ship::class)->create([
            'team_id' => $team->id,
        ]);
        $featureB = factory(Ship::class)->create([
            'team_id' => $team->id,
        ]);
        $featureA->employees()->attach([$michael->id]);

        $collection = TeamRecentShipViewHelper::recentShips($team);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $featureA->id,
                    'title' => $featureA->title,
                    'description' => $featureA->description,
                    'employees' => [
                        0 => [
                            'id' => $michael->id,
                            'name' => $michael->name,
                            'avatar' => $michael->avatar,
                            'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                        ],
                    ],
                    'url' => route('teams.ships.show', [
                        'company' => $featureA->team->company,
                        'team' => $featureA->team,
                        'ship' => $featureA->id,
                    ]),
                ],
                1 => [
                    'id' => $featureB->id,
                    'title' => $featureB->title,
                    'description' => $featureB->description,
                    'employees' => null,
                    'url' => route('teams.ships.show', [
                        'company' => $featureB->team->company,
                        'team' => $featureB->team,
                        'ship' => $featureB->id,
                    ]),
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_details_of_a_recent_ship(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $featureA = factory(Ship::class)->create([
            'team_id' => $team->id,
            'description' => '**cool**',
        ]);
        $featureA->employees()->attach([$michael->id]);

        $array = TeamRecentShipViewHelper::ship($featureA);

        $this->assertEquals(6, count($array));

        $this->assertEquals(
            [
                'id' => $featureA->id,
                'title' => $featureA->title,
                'description' => '<p><strong>cool</strong></p>',
                'created_at' => 'Monday, Jan 1st 2018',
                'employees' => [
                    0 => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => $michael->avatar,
                        'position' => [
                            'title' => $michael->position->title,
                        ],
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                ],
                'url' => env('APP_URL').'/'.$michael->company_id.'/teams/'.$team->id.'/ships/'.$featureA->id,
            ],
            $array
        );
    }
}
