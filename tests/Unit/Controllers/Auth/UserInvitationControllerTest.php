<?php

namespace Tests\Unit\Controllers;

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
    public function it_validates_invitation_link(): void
    {
        $employee = factory(Employee::class)->create([
            'invitation_link' => 'link'
        ]);

        $response = $this->get('invite/employee/link');

        $response->assertStatus(200);
        $response->assertSee('Auth\/Invitation\/AcceptInvitationUnlogged');
    }

    /** @test */
    public function it_unvalidates_wrong_invitation_link(): void
    {
        $employee = factory(Employee::class)->create([
            'invitation_link' => 'link'
        ]);

        $response = $this->get('invite/employee/badlink');

        $response->assertStatus(200);
        $response->assertSee('Auth\/Invitation\/InvalidInvitationLink');
    }

    /** @test */
    public function it_unvalidates_already_validated_invitation_link(): void
    {
        $employee = factory(Employee::class)->create([
            'invitation_link' => 'link',
            'invitation_used_at' => now()
        ]);

        $response = $this->get('invite/employee/link');

        $response->assertStatus(200);
        $response->assertSee('Auth\/Invitation\/InvitationLinkAlreadyAccepted');
    }

    /** @test */
    public function it_creates_a_user_invited(): void
    {
        Notification::fake();

        // be sure to have at least 2 users
        factory(User::class)->create([]);

        $employee = factory(Employee::class)->create([
            'invitation_link' => 'link'
        ]);

        $params = [
            'email' => 'jim.halpert@dundermifflin.com',
            'password' => 'pam',
        ];

        $response = $this->post('invite/employee/link/join', $params);

        $response->assertStatus(200);

        $employee->refresh();
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $employee->user_id,
            'email' => 'jim.halpert@dundermifflin.com',
        ]);

        $user = User::find($employee->user_id);

        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }
}
