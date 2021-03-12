<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\User\User;
use App\Jobs\SendVerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendVerifyEmailTest extends TestCase
{
    use DatabaseTransactions;

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
