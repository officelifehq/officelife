<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Mail;
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
        $adminEmployee = $this->createAdministrator();
        $employee = factory(Employee::class)->create([
            'company_id' => $adminEmployee->company_id,
        ]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
            'employee_id' => $employee->id,
        ];

        Mail::fake();
        $employee = (new InviteUser)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'company_id' => $adminEmployee->company_id,
            'invitation_link' => $employee->invitation_link,
        ]);

        Mail::assertQueued(InviteEmployeeToBecomeUser::class, function ($mail) use ($employee) {
            return $mail->employee->id === $employee->id;
        });
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $adminEmployee = $this->createAdministrator();
        $employee = factory(Employee::class)->create([
            'company_id' => $adminEmployee->company_id,
        ]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
            'employee_id' => $employee->id,
        ];

        Mail::fake();
        $employee = (new InviteUser)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'employee_invited_to_become_user',
            'objects' => json_encode([
                'author_id' => $adminEmployee->user->id,
                'author_name' => $adminEmployee->user->name,
                'employee_id' => $employee->id,
                'employee_email' => $employee->email,
                'employee_first_name' => $employee->first_name,
                'employee_last_name' => $employee->last_name,
            ]),
        ]);
    }

    /** @test */
    public function it_raises_an_exception_if_invitation_link_has_already_been_accepted() : void
    {
        $adminEmployee = $this->createAdministrator();
        $employee = factory(Employee::class)->create([
            'company_id' => $adminEmployee->company_id,
            'invitation_used_at' => '1999-01-01',
        ]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
            'employee_id' => $employee->id,
        ];

        $this->expectException(InvitationAlreadyUsedException::class);
        (new InviteUser)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $adminEmployee = $this->createAdministrator();
        factory(Employee::class)->create([
            'company_id' => $adminEmployee->company_id,
        ]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
        ];

        $this->expectException(ValidationException::class);
        (new InviteUser)->execute($request);
    }
}
