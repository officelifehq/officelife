<?php

namespace Tests\Unit\Controllers\Auth;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserInvitationControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_user_invited(): void
    {
        Notification::fake();

        // be sure to have at least 2 users
        User::factory()->create([]);

        $employee = Employee::factory()->create([
            'invitation_link' => 'link',
        ]);

        $params = [
            'email' => 'jim.halpert@dundermifflin.com',
            'password' => 'pam',
        ];

        $response = $this->post('invite/employee/link/join', $params);

        $response->assertStatus(204);

        $employee->refresh();

        $this->assertNotEmpty($employee->invitation_used_at);

        $this->assertDatabaseHas('users', [
            'id' => $employee->user_id,
            'email' => 'jim.halpert@dundermifflin.com',
        ]);

        $user = User::find($employee->user_id);
    }

    /** @test */
    public function it_link_an_existing_user_invited(): void
    {
        Notification::fake();

        // be sure to have at least 2 users
        $user = User::factory()->create([
            'email' => 'jim.halpert@dundermifflin.com',
        ]);

        $employee = Employee::factory()->create([
            'invitation_link' => 'link',
        ]);

        $params = [
            'email' => 'jim.halpert@dundermifflin.com',
            'password' => 'pam',
        ];

        $response = $this->post('invite/employee/link/join', $params);

        $response->assertStatus(204);

        $employee->refresh();
        $this->assertNotEmpty($employee->invitation_used_at);
        $this->assertEquals($user->id, $employee->user_id);

        Notification::assertNotSentTo(
            [$user],
            VerifyEmail::class
        );
    }
}
