<?php

namespace Tests\Unit\Services\Company\Adminland\EmployeeStatus;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\EmployeeStatus\DestroyEmployeeStatus;

class DestroyEmployeeStatusTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_an_employee_status() : void
    {
        $employeeStatus = factory(EmployeeStatus::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $employeeStatus->company_id,
        ]);

        $request = [
            'company_id' => $employeeStatus->company_id,
            'author_id' => $employee->user->id,
            'employee_status_id' => $employeeStatus->id,
        ];

        (new DestroyEmployeeStatus)->execute($request);

        $this->assertDatabaseMissing('employee_statuses', [
            'id' => $employeeStatus->id,
        ]);
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $employeeStatus = factory(EmployeeStatus::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $employeeStatus->company_id,
        ]);

        $request = [
            'company_id' => $employeeStatus->company_id,
            'author_id' => $employee->user->id,
            'employee_status_id' => $employeeStatus->id,
        ];

        (new DestroyEmployeeStatus)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employeeStatus->company_id,
            'action' => 'employee_status_destroyed',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'employee_status_name' => $employeeStatus->name,
            ]),
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyEmployeeStatus)->execute($request);
    }
}
