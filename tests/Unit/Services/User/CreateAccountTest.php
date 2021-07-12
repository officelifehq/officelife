<?php

namespace Tests\Unit\Services\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Services\User\CreateAccount;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_user(): void
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        $createdUser = (new CreateAccount)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $createdUser->id,
            'email' => 'dwight@dundermifflin.com',
        ]);

        $this->assertInstanceOf(
            User::class,
            $createdUser
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'email' => 'dwight.dundermifflin.com',
        ];

        $this->expectException(ValidationException::class);
        (new CreateAccount)->execute($request);
    }
}
