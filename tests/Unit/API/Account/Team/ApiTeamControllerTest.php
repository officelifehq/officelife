<?php

namespace Tests\Api\Account\Team;

use Tests\ApiTestCase;
use App\Models\Account\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTeamControllerTest extends ApiTestCase
{
    use DatabaseTransactions;

    protected $jsonTeam = [
        'id',
        'object',
        'name',
        'description',
        'account' => [
            'id',
        ],
        'created_at',
        'updated_at',
    ];

    /** @test */
    public function it_gets_a_list_of_teams()
    {
        $user = $this->signin();
        $team = factory(Team::class)->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->json('GET', '/api/teams');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => $this->jsonTeam,
            ],
        ]);

        $response->assertJsonFragment([
            'object' => 'team',
            'id' => $team->id,
            'name' => $team->name,
        ]);
    }

    /** @test */
    public function it_gets_a_specific_team()
    {
        $user = $this->signin();
        $team = factory(Team::class)->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->json('GET', '/api/teams/'.$team->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => $this->jsonTeam,
        ]);

        $response->assertJsonFragment([
            'object' => 'team',
            'id' => $team->id,
            'name' => $team->name,
        ]);
    }

    /** @test */
    public function it_cant_get_a_specific_team_if_team_not_found()
    {
        $user = $this->signin();

        $response = $this->json('GET', '/api/teams/2939209');

        $this->expectNotFound($response);
    }
}
