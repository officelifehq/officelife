<?php

namespace Tests\Unit\Controllers\Auth;

use App\Models\User\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    /** @test */
    public function it_creates_a_user(): void
    {
        Notification::fake();

        // be sure to have at least 2 users
        User::factory()->create([]);

        $params = [
            'email' => 'jim.halpert@dundermifflin.com',
            'password' => 'pam',
        ];

        $response = $this->post('register', $params);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => 'jim.halpert@dundermifflin.com',
        ]);

        $user = User::where('email', 'jim.halpert@dundermifflin.com')->first();

        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }
}
