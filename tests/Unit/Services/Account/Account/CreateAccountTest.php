<?php

namespace Tests\Unit\Services\Account\Account;

use Tests\TestCase;
use App\Mail\ConfirmAccount;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Mail;
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
            'permission_level' => config('homas.authorizations.administrator'),
        ]);

        $this->assertInstanceOf(
            Account::class,
            $account
        );
    }

    /** @test */
    public function it_generates_a_confirmation_link()
    {
        $request = [
            'subdomain' => 'dundermifflin',
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        $account = (new CreateAccount)->execute($request);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'confirmed' => false,
        ]);

        $this->assertNotNull($account->confirmation_link);
    }

    /** @test */
    public function it_logs_an_action()
    {
        $request = [
            'subdomain' => 'dundermifflin',
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        $account = (new CreateAccount)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $account->id,
            'action' => 'account_created',
        ]);
    }

    /** @test */
    public function it_schedules_an_email()
    {
        $request = [
            'subdomain' => 'dundermifflin',
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        Mail::fake();

        $account = (new CreateAccount)->execute($request);

        Mail::assertQueued(ConfirmAccount::class, function ($mail) use ($account) {
            return $mail->account->id === $account->id;
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'subdomain' => 'dundermifflin',
            'email' => 'dwight@dundermifflin.com',
        ];

        $this->expectException(ValidationException::class);
        (new CreateAccount)->execute($request);
    }
}
