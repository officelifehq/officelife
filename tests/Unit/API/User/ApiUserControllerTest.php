<?php

namespace Tests\Api\User;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiUserControllerTest extends ApiTestCase
{
    use DatabaseTransactions;

    /**
     * @var array
     */
    protected $jsonUser = [
        'id',
        'object',
        'email',
        'first_name',
        'last_name',
        'middle_name',
        'nickname',
        'avatar',
        'uuid',
        'created_at',
        'updated_at',
    ];

    /** @test */
    public function it_gets_me_as_a_user()
    {
        $user = $this->signin();

        $response = $this->json('GET', '/api/me');

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
}
