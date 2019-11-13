<?php

namespace Tests\Unit\Services\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Mail\ConfirmAccount;
use App\Services\User\CreateAccount;
use Illuminate\Support\Facades\Mail;
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
    public function it_generates_a_confirmation_link(): void
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        $user = (new CreateAccount)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null,
        ]);

        $this->assertNotNull($user->verification_link);
    }

    /** @test */
    public function it_schedules_an_email(): void
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        Mail::fake();

        $user = (new CreateAccount)->execute($request);

        Mail::assertQueued(ConfirmAccount::class, function ($mail) use ($user) {
            return $mail->user->id === $user->id;
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
        ];

        $this->expectException(ValidationException::class);
        (new CreateAccount)->execute($request);
    }
}
