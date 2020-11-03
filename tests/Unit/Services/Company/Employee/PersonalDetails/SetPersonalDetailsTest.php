<?php

namespace Tests\Unit\Services\Company\Employee\PersonalDetails;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\PersonalDetails\SetPersonalDetails;

class SetPersonalDetailsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_the_name_and_email_of_the_employee_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_sets_the_name_and_email_of_the_employee_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_sets_the_name_and_email_of_the_employee_as_normal_user_if_hes_the_employee(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_against_another_normal_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new SetPersonalDetails)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'first_name' => 'michael',
            'last_name' => 'scott',
            'email' => 'michael@dundermifflin.com',
            'phone' => '123456789',
        ];

        $dwight = (new SetPersonalDetails)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'first_name' => 'michael',
            'last_name' => 'scott',
            'email' => 'michael@dundermifflin.com',
            'phone_number' => '123456789',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_personal_details_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'employee_email' => 'michael@dundermifflin.com',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'personal_details_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'name' => $dwight->name,
                    'email' => 'michael@dundermifflin.com',
                ]);
        });
    }
}
