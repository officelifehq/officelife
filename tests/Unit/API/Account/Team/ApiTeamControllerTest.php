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

    /** @test */
    public function it_stores_a_team()
    {
        $user = $this->signin();

        $response = $this->json('POST', '/api/teams/', [
            'name' => 'sales team',
            'description' => 'managed by dwight',
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => $this->jsonTeam,
        ]);

        $response->assertJsonFragment([
            'object' => 'team',
            'id' => $response->json('data.id'),
            'name' => 'sales team',
        ]);
    }

    /** @test */
    public function it_doesnt_store_a_team_if_validation_fails()
    {
        $user = $this->signin();

        $response = $this->json('POST', '/api/teams/', [
            'description' => 'managed by dwight',
        ]);

        $errors = [
            'The name field is required.',
        ];

        $this->expectDataError($response, $errors);
    }

    /** @test */
    public function it_doesnt_store_a_team_if_permission_fails()
    {
        $user = $this->signin();
        $user->permission_level = 3;
        $user->save();

        $response = $this->json('POST', '/api/teams/', [
            'name' => 'sales team',
            'description' => 'managed by dwight',
        ]);

        $this->expectNotAuthorized($response);
    }
}
