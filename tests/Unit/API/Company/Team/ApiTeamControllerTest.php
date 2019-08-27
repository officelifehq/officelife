<?php

namespace Tests\Api\Company\Team;

use Tests\ApiTestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTeamControllerTest extends ApiTestCase
{
    use DatabaseTransactions;

    /**
     * @var array
     */
    protected $jsonTeam = [
        'id',
        'object',
        'name',
        'company' => [
            'id',
        ],
        'created_at',
        'updated_at',
    ];

    public function it_gets_a_list_of_teams()
    {
        $user = $this->signin();
        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $response = $this->json('GET', '/api/'.$team->company_id.'/teams');

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

    public function it_gets_a_specific_team()
    {
        $user = $this->signin();
        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $response = $this->json('GET', '/api/'.$team->company_id.'/teams/'.$team->id);

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

    public function it_cant_get_a_specific_team_if_team_not_found()
    {
        $user = $this->signin();
        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $response = $this->json('GET', '/api/'.$team->company_id.'/teams/2939209');

        $this->expectNotFound($response);
    }

    public function it_stores_a_team()
    {
        $user = $this->signin();
        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('POST', '/api/'.$employee->company_id.'/teams/', [
            'name' => 'sales team',
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

    public function it_doesnt_store_a_team_if_validation_fails()
    {
        $user = $this->signin();
        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('POST', '/api/'.$employee->company_id.'/teams/', [
            'name' => null,
        ]);

        $errors = [
            'The name field is required.',
        ];

        $this->expectDataError($response, $errors);
    }

    public function it_doesnt_store_a_team_if_permission_fails()
    {
        $user = $this->signin();
        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);
        $employee->permission_level = config('homas.authorizations.user');
        $employee->save();

        $response = $this->json('POST', '/api/'.$employee->company_id.'/teams/', [
            'name' => 'sales team',
        ]);

        $this->expectNotAuthorized($response);
    }
}
