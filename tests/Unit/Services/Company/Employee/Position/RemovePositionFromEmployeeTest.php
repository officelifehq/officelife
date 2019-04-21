<?php

namespace Tests\Unit\Services\Company\Employee\Position;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Position\RemovePositionFromEmployee;

class RemovePositionFromEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_resets_an_employees_position()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
        ];

        $employee = (new RemovePositionFromEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $employee->company_id,
            'id' => $employee->id,
            'position_id' => null,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employee
        );
    }

    /** @test */
    public function it_logs_an_action()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
        ];

        (new RemovePositionFromEmployee)->execute($request);

        $this->assertdatabasehas('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'position_removed',
        ]);

        $this->assertdatabasehas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'position_removed',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new RemovePositionFromEmployee)->execute($request);
    }
}
