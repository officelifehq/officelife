<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Mail\Company\InviteEmployeeToBecomeUserMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;

class AddEmployeeToCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_an_employee_to_a_company_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, false);
        $this->executeService($michael, true);
    }

    /** @test */
    public function it_adds_an_employee_to_a_company_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael, false);
        $this->executeService($michael, true);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, true);
        $this->executeService($michael, false);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'last_name' => 'Schrute',
            'permission_level' => config('officelife.permission_level.user'),
        ];

        $this->expectException(ValidationException::class);
        (new AddEmployeeToCompany)->execute($request);
    }

    private function executeService(Employee $michael, bool $sendEmail): void
    {
        Queue::fake();
        Mail::fake();
        Carbon::setTestNow(Carbon::create(2020, 1, 1));

        // used to populate the holidays
        CompanyPTOPolicy::factory()->create([
            'company_id' => $michael->company_id,
            'year' => 2020,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'email' => 'dwight@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => $sendEmail,
        ];

        $dwight = (new AddEmployeeToCompany)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'user_id' => null,
            'company_id' => $michael->company_id,
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'amount_of_allowed_holidays' => 30,
        ]);

        $this->assertNotNull(ImageHelper::getAvatar($dwight));

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_added_to_company' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_email' => 'dwight@dundermifflin.com',
                    'employee_first_name' => 'Dwight',
                    'employee_last_name' => 'Schrute',
                    'employee_name' => 'Dwight Schrute',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => 'Dwight Schrute',
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($dwight) {
            return $job->notification['action'] === 'employee_added_to_company' &&
                $job->notification['objects'] === json_encode([
                    'company_name' => $dwight->company->name,
                ]);
        });

        if ($sendEmail) {
            Mail::assertQueued(InviteEmployeeToBecomeUserMail::class, function ($mail) use ($dwight) {
                return $mail->employee->id === $dwight->id;
            });
        }
    }
}
