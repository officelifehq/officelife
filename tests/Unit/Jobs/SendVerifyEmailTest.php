<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SendVerifyEmail;
use App\Models\User\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendVerifyEmailTest extends TestCase
{
    /** @test */
    public function it_send_a_verification_mail(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        dispatch(new SendVerifyEmail($user));

        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }
}
