<?php

namespace Tests\Unit\Notifications;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmailMessagingTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sends_a_confirmation_email()
    {
        Notification::fake();

        // be sure to have at least 2 users
        User::factory()->create([]);
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class);

        $notifications = Notification::sent($user, VerifyEmail::class);
        $message = $notifications[0]->toMail($user);

        $this->assertStringContainsString('Thanks for signing up.', implode('', $message->introLines));
    }
}
