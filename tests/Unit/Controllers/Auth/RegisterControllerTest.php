<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_user(): void
    {
        Notification::fake();

        // be sure to have at least 2 users
        factory(User::class)->create([]);

        $params = [
            'email' => 'jim.halpert@dundermifflin.com',
            'password' => 'pam',
        ];

        $response = $this->post('signup', $params);

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
