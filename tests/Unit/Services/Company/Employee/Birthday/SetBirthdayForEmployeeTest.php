<?php

namespace Tests\Unit\Services\Company\Employee\Birthday;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Birthday\SetBirthdayForEmployee;

class SetBirthdayForEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Set the base for the test.
     *
     * @return Employee
     */
    private function initialize(): Employee
    {
        Carbon::setTestNow(Carbon::create(2017, 1, 1));
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'employee_id' => $employee->id,
            'date' => '1978-10-01',
        ];

        return (new SetBirthdayForEmployee)->execute($request);
    }

    /** @test */
    public function it_sets_the_birthday_of_an_employee()
    {
        $employee = $this->initialize();

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'company_id' => $employee->company_id,
            'birthdate' => '1978-10-01',
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employee
        );
    }

    /** @test */
    public function it_creates_an_event_for_the_birthdate()
    {
        $employee = $this->initialize();

        $this->assertDatabaseHas('employee_events', [
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'label' => 'birthday',
            'date' => '2017-10-01',
        ]);
    }

    /** @test */
    public function it_logs_an_action()
    {
        $employee = $this->initialize();

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'employee_birthday_set',
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'birthday_set',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'employee_id' => $employee->id,
        ];

        $this->expectException(ValidationException::class);
        (new SetBirthdayForEmployee)->execute($request);
    }
}
