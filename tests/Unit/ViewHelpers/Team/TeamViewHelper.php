<?php

namespace Tests\Unit\ViewHelpers\Team;

use Tests\TestCase;
use App\Http\ViewHelpers\Team\TeamViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_detail_of_a_team(): void
    {
        $dunder = factory(Company::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $dunder->id,
            'name' => 'sales',
            'description' => 'this is a description',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $sales->id,
                'name' => 'sales',
                'raw_description' => 'this is a description',
                'parsed_description' => '<p>this is a description</p>',
                'team_leader' => [
                    'id' => $sales->leader->id,
                ],
            ],
            TeamViewHelper::team($sales)
        );
    }
}
