<?php

namespace Tests\Unit\Services\Logs;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use App\Services\Logs\LogEmployeeAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogEmployeeActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action() : void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'employee_id' => $employee->id,
            'action' => 'account_created',
            'objects' => '{"user": 1}',
        ];

        $employeeLog = (new LogEmployeeAction)->execute($request);

        $this->assertDatabaseHas('employee_logs', [
            'id' => $employeeLog->id,
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
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogEmployeeAction)->execute($request);
    }
}
