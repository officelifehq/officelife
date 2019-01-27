<?php

namespace Tests\Api\User;

use Tests\ApiTestCase;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiUserControllerTest extends ApiTestCase
{
    use DatabaseTransactions;

    protected $jsonUser = [
        'id',
        'object',
        'email',
        'permission_level',
        'account' => [
            'id',
        ],
        'created_at',
        'updated_at',
    ];

    /** @test */
    public function it_gets_a_list_of_users()
    {
        $user = $this->signin();
        $userA = factory(User::class)->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->json('GET', '/api/users');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => $this->jsonUser,
            ],
        ]);

        $response->assertJsonFragment([
            'object' => 'user',
            'id' => $user->id,
            'email' => $userA->email,
        ]);
    }

    /** @test */
    public function it_gets_a_specific_user()
    {
        $user = $this->signin();

        $response = $this->json('GET', '/api/users/'.$user->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => $this->jsonUser,
        ]);

        $response->assertJsonFragment([
            'object' => 'user',
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function it_cant_get_a_specific_user_if_user_not_found()
    {
        $user = $this->signin();

        $response = $this->json('GET', '/api/users/2939209');

        $this->expectNotFound($response);
    }
}
