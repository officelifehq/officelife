<?php

namespace Tests\Unit\ViewHelpers\Team;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Http\ViewHelpers\Team\TeamIndexViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamIndexViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_teams(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $team->employees()->attach([$michael->id]);
        $team->employees()->attach([$dwight->id]);

        $collection = TeamIndexViewHelper::index($michael->company);

        $this->assertEquals(1, $collection->count());

        $this->assertEquals(
            $team->id,
            $collection->toArray()[0]['id']
        );

        $this->assertEquals(
            $team->name,
            $collection->toArray()[0]['name']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                1 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'avatar' => ImageHelper::getAvatar($dwight),
                    'url' => env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
                ],
            ],
            $collection->toArray()[0]['employees']->toArray()
        );
    }
}
