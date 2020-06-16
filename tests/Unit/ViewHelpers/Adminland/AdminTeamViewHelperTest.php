<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\ApiTestCase;
use App\Models\Company\Team;
use App\Http\ViewHelpers\Adminland\AdminTeamViewHelper;

class AdminTeamViewHelperTest extends ApiTestCase
{
    /** @test */
    public function it_gets_a_collection_of_teams(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $collection = AdminTeamViewHelper::teams($michael->company->teams);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/teams/'.$team->id,
                ],
            ],
            $collection->toArray()
        );
    }
}
