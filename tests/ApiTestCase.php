<?php

namespace Tests;

use App\Models\User\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\TestResponse;

class ApiTestCase extends TestCase
{
    /**
     * Create a user and sign in as that user.
     *
     * @param null $user
     *
     * @return mixed
     */
    public function signIn()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        return $user;
    }

    /**
     * Test that the response contains a not found notification.
     *
     * @param TestResponse $response
     */
    public function expectNotFound(TestResponse $response): void
    {
        $response->assertStatus(404);

        $response->assertJson([
            'error' => [
                'message' => null,
                'error_message' => 'Resource not found.',
            ],
        ]);
    }

    /**
     * Test that the response contains a data error notification.
     *
     * @param TestResponse $response
     * @param array|string $message
     */
    public function expectDataError(TestResponse $response, $message = ''): void
    {
        $response->assertStatus(422);

        $response->assertJson([
            'error' => [
                'message' => $message,
                'error_message' => 'Validator failed.',
            ],
        ]);
    }

    /**
     * Test that the response contains a non authorized response.
     *
     * @param TestResponse $response
     * @param array|string $message
     */
    public function expectNotAuthorized(TestResponse $response, $message = ''): void
    {
        $response->assertStatus(401);

        $response->assertJson([
            'error' => [
                'error_message' => 'Action requires user authentication.',
            ],
        ]);
    }
}
