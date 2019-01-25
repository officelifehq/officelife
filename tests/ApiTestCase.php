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
     * @return mixed
     */
    public function signIn()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        return $user;
    }
}
