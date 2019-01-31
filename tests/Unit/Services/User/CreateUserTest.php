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
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => $user->id,
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
            'is_administrator' => true,
        ];

        $createdUser = (new CreateUser)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $createdUser->id,
            'account_id' => $createdUser->account_id,
            'email' => 'dwight@dundermifflin.com',
            'permission_level' => config('homas.authorizations.administrator'),
        ]);

        $this->assertInstanceOf(
            User::class,
            $createdUser
        );
    }

    /** @test */
    public function it_has_an_avatar()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => $user->id,
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
            'is_administrator' => true,
        ];

        $createdUser = (new CreateUser)->execute($request);

        $this->assertNotNull($createdUser->avatar);
    }

    /** @test */
    public function it_logs_an_action()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => $user->id,
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
            'is_administrator' => true,
        ];

        $team = (new CreateUser)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $user->account_id,
            'action' => 'user_created',
        ]);
    }

    /** @test */
    public function it_doesnt_create_a_user_if_email_is_not_unique_in_account()
    {
        $user = factory(User::class)->create([]);
        $existingUser = factory(User::class)->create([
            'account_id' => $user->account_id,
        ]);

        $request = [
            'account_id' => $existingUser->account_id,
            'author_id' => $user->id,
            'email' => $existingUser->email,
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
