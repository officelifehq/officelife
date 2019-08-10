<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;

class AddEmployeeToCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_an_employee_to_a_company() : void
    {
        Queue::fake();

        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
            'email' => 'dwight@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'permission_level' => config('homas.authorizations.user'),
            'send_invitation' => false,
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
        ]);

        $this->assertNotNull($dwight->avatar);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_added_to_company' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $dwight->id,
                    'employee_email' => 'dwight@dundermifflin.com',
                    'employee_first_name' => 'Dwight',
                    'employee_last_name' => 'Schrute',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_created' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => 'Dwight Schrute',
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'last_name' => 'Schrute',
            'permission_level' => config('homas.authorizations.user'),
        ];

        $this->expectException(ValidationException::class);
        (new AddEmployeeToCompany)->execute($request);
    }
}
