<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UserAlreadyInvitedException;
use App\Exceptions\NotEnoughPermissionException;
use App\Mail\Company\InviteEmployeeToBecomeUserMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Employee\InviteEmployeeToBecomeUser;

class InviteEmployeeToBecomeUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_invites_an_employee_to_become_a_user_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_invites_an_employee_to_become_a_user_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_raises_an_exception_if_invitation_link_has_already_been_accepted(): void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'invitation_used_at' => '1999-01-01',
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        $this->expectException(UserAlreadyInvitedException::class);
        (new InviteEmployeeToBecomeUser)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = $this->createAdministrator();
        factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new InviteEmployeeToBecomeUser)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new InviteEmployeeToBecomeUser)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();
        Mail::fake();

        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        $dwight = (new InviteEmployeeToBecomeUser)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'company_id' => $michael->company_id,
            'invitation_link' => $dwight->invitation_link,
        ]);

        Mail::assertQueued(InviteEmployeeToBecomeUserMail::class, function ($mail) use ($dwight) {
            return $mail->employee->id === $dwight->id;
        });

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_invited_to_become_user' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_email' => $dwight->email,
                    'employee_first_name' => $dwight->first_name,
                    'employee_last_name' => $dwight->last_name,
                ]);
        });
    }
}
