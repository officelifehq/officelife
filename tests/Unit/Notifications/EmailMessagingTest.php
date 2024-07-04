<?php

namespace Tests\Unit\Notifications;

use App\Models\User\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailMessagingTest extends TestCase
{
    /** @test */
    public function it_sends_a_confirmation_email()
    {
        config(['mail.verify' => true]);

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
