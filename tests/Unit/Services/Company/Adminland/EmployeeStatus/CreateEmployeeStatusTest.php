<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;

class CreateEmployeeStatusTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_employee_status() : void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'name' => 'Permanent',
        ];

        $employeeStatus = (new CreateEmployeeStatus)->execute($request);

        $this->assertDatabaseHas('employee_statuses', [
            'id' => $employeeStatus->id,
            'company_id' => $employee->company_id,
            'name' => 'Permanent',
        ]);

        $this->assertInstanceOf(
            EmployeeStatus::class,
            $employeeStatus
        );
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'name' => 'Permanent',
            'description' => 'Permanent',
        ];

        $employeeStatus = (new CreateEmployeeStatus)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'employee_status_created',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'employee_status_id' => $employeeStatus->id,
                'employee_status_name' => 'Permanent',
            ]),
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Permanent',
        ];

        $this->expectException(ValidationException::class);
        (new CreateEmployeeStatus)->execute($request);
    }
}
