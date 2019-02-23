<?php

namespace Tests\Unit\Services\Company\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Employee\UpdateEmployee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
            'birthdate' => '1978-01-20',
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $updatedEmployee
        );
    }

    // /** @test */
    // public function it_logs_an_action()
    // {
    //     $team = factory(team::class)->create([]);
    //     $employee = factory(employee::class)->create([
    //         'company_id' => $team->company_id,
    //     ]);

    //     $request = [
    //         'company_id' => $team->company_id,
    //         'author_id' => $employee->user->id,
    //         'team_id' => $team->id,
    //         'name' => 'selling team',
    //     ];

    //     (new updateteam)->execute($request);

    //     $this->assertdatabasehas('audit_logs', [
    //         'company_id' => $team->company_id,
    //         'action' => 'team_updated',
    //     ]);
    // }

    // /** @test */
    // public function it_fails_if_wrong_parameters_are_given()
    // {
    //     $request = [
    //         'name' => 'selling team',
    //     ];

    //     $this->expectexception(validationexception::class);
    //     (new updateteam)->execute($request);
    // }
}
