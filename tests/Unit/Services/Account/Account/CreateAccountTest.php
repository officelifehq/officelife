<?php

namespace Tests\Unit\Services\Account\Account;

use Tests\TestCase;
use App\Models\Account\Account;
use Illuminate\Validation\ValidationException;
use App\Services\Account\Account\CreateAccount;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_account()
    {
        $request = [
            'subdomain' => 'dundermifflin',
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        $account = (new CreateAccount)->execute($request);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'subdomain' => 'dundermifflin',
        ]);

        $this->assertDatabaseHas('users', [
            'account_id' => $account->id,
            'email' => 'dwight@dundermifflin.com',
            'is_administrator' => true,
        ]);

        $this->assertInstanceOf(
            Account::class,
            $account
        );
    }

    public function test_it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'subdomain' => 'dundermifflin',
            'email' => 'dwight@dundermifflin.com',
        ];

        $this->expectException(ValidationException::class);
        $account = (new CreateAccount)->execute($request);
    }
}
