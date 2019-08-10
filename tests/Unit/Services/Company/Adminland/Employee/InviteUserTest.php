<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Mail\Company\InviteEmployeeToBecomeUser;
use App\Exceptions\InvitationAlreadyUsedException;
use App\Services\Company\Adminland\Employee\InviteUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InviteUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_invites_an_employee_to_become_a_user() : void
    {
        Queue::fake();

        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
            'employee_id' => $dwight->id,
        ];

        Mail::fake();
        $dwight = (new InviteUser)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'company_id' => $michael->company_id,
            'invitation_link' => $dwight->invitation_link,
        ]);

        Mail::assertQueued(InviteEmployeeToBecomeUser::class, function ($mail) use ($dwight) {
            return $mail->employee->id === $dwight->id;
        });

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_invited_to_become_user' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $dwight->id,
                    'employee_email' => $dwight->email,
                    'employee_first_name' => $dwight->first_name,
                    'employee_last_name' => $dwight->last_name,
                ]);
        });
    }

    /** @test */
    public function it_raises_an_exception_if_invitation_link_has_already_been_accepted() : void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'invitation_used_at' => '1999-01-01',
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
            'employee_id' => $dwight->id,
        ];

        $this->expectException(InvitationAlreadyUsedException::class);
        (new InviteUser)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = $this->createAdministrator();
        factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
        ];

        $this->expectException(ValidationException::class);
        (new InviteUser)->execute($request);
    }
}
