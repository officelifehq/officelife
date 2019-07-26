<?php

namespace Tests\Unit\Services\Company\Employee\Position;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;

class AssignPositionToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_position() : void
    {
        $employee = factory(Employee::class)->create([]);
        $position = factory(Position::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'position_id' => $position->id,
        ];

        $employee = (new AssignPositionToEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $employee->company_id,
            'id' => $employee->id,
            'position_id' => $position->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employee
        );
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $employee = factory(Employee::class)->create([]);
        $position = factory(Position::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'position_id' => $position->id,
        ];

        (new AssignPositionToEmployee)->execute($request);

        $this->assertdatabasehas('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'position_assigned',
        ]);

        $this->assertdatabasehas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'position_assigned',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignPositionToEmployee)->execute($request);
    }
}
