<?php

namespace Tests\Unit\Services\Company\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\Adminland\Employee\DestroyEmployee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_an_employee()
    {
        $administrator = $this->createAdministrator();
        $employee = factory(Employee::class)->create([
            'company_id' => $administrator->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
        ];

        (new DestroyEmployee)->execute($request);

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    /** @test */
    public function it_logs_an_action()
    {
        $administrator = $this->createAdministrator();
        $employee = factory(Employee::class)->create([
            'company_id' => $administrator->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
        ];

        (new DestroyEmployee)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $administrator->company_id,
            'action' => 'employee_destroyed',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyEmployee)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_does_not_match_the_company()
    {
        $administrator = $this->createAdministrator();
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $administrator->user->id,
            'employee_id' => $employee->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new DestroyEmployee)->execute($request);
    }
}
