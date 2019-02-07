<?php

namespace Tests\Unit\Services\Company\Team;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Employee\ChangePermission;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChangePermissionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_changes_permission()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'employee_id' => $employee->id,
            'permission_level' => config('homas.authorizations.hr'),
        ];

        $employee = (new ChangePermission)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'permission_level' => config('homas.authorizations.hr'),
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
            'author_id' => $employee->user_id,
            'employee_id' => $employee->id,
            'permission_level' => config('homas.authorizations.hr'),
        ];

        $employee = (new ChangePermission)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'permission_changed',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
        ];

        $this->expectException(ValidationException::class);
        (new ChangePermission)->execute($request);
    }
}
