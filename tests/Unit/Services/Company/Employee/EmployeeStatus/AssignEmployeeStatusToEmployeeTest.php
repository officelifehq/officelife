<?php

namespace Tests\Unit\Services\Company\Employee\EmployeeStatus;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\EmployeeStatus;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\EmployeeStatus\AssignEmployeeStatusToEmployee;

class AssignEmployeeStatusToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_an_employee_status_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_assigns_an_employee_status_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignEmployeeStatusToEmployee)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_status_is_not_linked_to_company(): void
    {
        $michael = $this->createAdministrator();
        $employeeStatus = factory(EmployeeStatus::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'employee_status_id' => $employeeStatus->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new AssignEmployeeStatusToEmployee)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $employeeStatus = factory(EmployeeStatus::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'employee_status_id' => $employeeStatus->id,
        ];

        $michael = (new AssignEmployeeStatusToEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $michael->company_id,
            'id' => $michael->id,
            'employee_status_id' => $employeeStatus->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $employeeStatus) {
            return $job->auditLog['action'] === 'employee_status_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'employee_status_id' => $employeeStatus->id,
                    'employee_status_name' => $employeeStatus->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $employeeStatus) {
            return $job->auditLog['action'] === 'employee_status_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_status_id' => $employeeStatus->id,
                    'employee_status_name' => $employeeStatus->name,
                ]);
        });
    }
}
