<?php

namespace Tests\Unit\Services\Company\Employee\Position;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\EmployeeStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\EmployeeStatus\AssignEmployeeStatusToEmployee;

class AssignEmployeeStatusToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_an_employee_status() :void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $employeeStatus = factory(EmployeeStatus::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
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
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'employee_status_id' => $employeeStatus->id,
                    'employee_status_name' => $employeeStatus->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $employeeStatus) {
            return $job->auditLog['action'] === 'employee_status_assigned' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_status_id' => $employeeStatus->id,
                    'employee_status_name' => $employeeStatus->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() :void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignEmployeeStatusToEmployee)->execute($request);
    }
}
