<?php

namespace Tests\Unit\Services\Company\Employee\Description;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Description\SetPersonalDescription;

class SetPersonalDescriptionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_a_personal_description_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_sets_a_personal_description_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function user_can_execute_the_service_in_his_own_profile(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new SetPersonalDescription)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    private function executeService(Employee $michael, Employee $employeeToUpdate): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $employeeToUpdate->id,
            'description' => 'This is just great',
        ];

        $employeeToUpdate = (new SetPersonalDescription)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $employeeToUpdate->company_id,
            'id' => $employeeToUpdate->id,
            'description' => 'This is just great',
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employeeToUpdate
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $employeeToUpdate) {
            return $job->auditLog['action'] === 'employee_description_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $employeeToUpdate->id,
                    'employee_name' => $employeeToUpdate->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'description_set' &&
                $job->auditLog['author_id'] === $michael->id;
        });
    }
}
