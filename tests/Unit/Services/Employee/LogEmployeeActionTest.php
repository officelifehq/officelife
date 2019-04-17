<?php

namespace Tests\Unit\Services\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use App\Services\Employee\LogEmployeeAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogEmployeeActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'account_created',
            'objects' => '{"user": 1}',
        ];

        $employeeLog = (new LogEmployeeAction)->execute($request);

        $this->assertDatabaseHas('employee_logs', [
            'id' => $employeeLog->id,
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'account_created',
            'objects' => '{"user": 1}',
        ]);

        $this->assertInstanceOf(
            EmployeeLog::class,
            $employeeLog
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogEmployeeAction)->execute($request);
    }
}
