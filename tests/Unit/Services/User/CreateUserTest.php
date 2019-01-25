<?php

namespace Tests\Unit\Services\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Account;
use App\Services\User\CreateUser;
use App\Exceptions\EmailAlreadyUsedException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_user()
    {
        $account = factory(Account::class)->create([]);

        $request = [
            'account_id' => $account->id,
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
            'is_administrator' => true,
        ];

        $user = (new CreateUser)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'account_id' => $account->id,
            'email' => 'dwight@dundermifflin.com',
            'is_administrator' => true,
        ]);

        $this->assertInstanceOf(
            User::class,
            $user
        );
    }

    /** @test */
    public function it_doesnt_create_a_user_if_email_is_not_unique_in_account()
    {
        $account = factory(Account::class)->create([]);
        $user = factory(User::class)->create([
            'account_id' => $account->id,
        ]);

        $request = [
            'account_id' => $account->id,
            'email' => $user->email,
            'password' => 'password',
            'is_administrator' => true,
        ];

        $this->expectException(EmailAlreadyUsedException::class);
        (new CreateUser)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $account = factory(Account::class)->create([]);

        $request = [
            'email' => 'dwight@dundermifflin.com',
        ];

        $this->expectException(ValidationException::class);
        (new CreateUser)->execute($request);
    }
}
