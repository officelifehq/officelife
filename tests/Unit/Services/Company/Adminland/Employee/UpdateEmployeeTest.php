<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\UpdateEmployee;

class UpdateEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_an_employee()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'email' => 'dwight@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'birthdate' => '1978-01-20',
        ];

        $updatedEmployee = (new UpdateEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'company_id' => $employee->company_id,
            'email' => 'dwight@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'birthdate' => '1978-01-20 00:00:00',
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $updatedEmployee
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
            'email' => 'dwight@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'birthdate' => '1978-01-20',
        ];

        $updatedEmployee = (new UpdateEmployee)->execute($request);

        $this->assertdatabasehas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'employee_updated',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateEmployee)->execute($request);
    }
}
