<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_validates_permission_level(): void
    {
        // administrator has all rights
        $stub = $this->getMockForAbstractClass(Controller::class);
        $employee = Employee::factory()->asAdministrator()->create();

        $this->assertInstanceOf(
            User::class,
            $stub->asUser($employee->user)
                ->forEmployee($employee)
                ->forCompanyId($employee->company_id)
                ->asPermissionLevel(config('officelife.permission_level.administrator'))
                ->canAccessCurrentPage()
        );

        // now testing the HR access level
        $employee = Employee::factory()->asHR()->create();
        $this->assertInstanceOf(
            User::class,
            $stub->asUser($employee->user)
                ->forEmployee($employee)
                ->forCompanyId($employee->company_id)
                ->asPermissionLevel(config('officelife.permission_level.hr'))
                ->canAccessCurrentPage()
        );

        // now testing the normal access level
        $employee = Employee::factory()->asNormalEmployee()->create();
        $this->assertInstanceOf(
            User::class,
            $stub->asUser($employee->user)
                ->forEmployee($employee)
                ->forCompanyId($employee->company_id)
                ->asPermissionLevel(config('officelife.permission_level.user'))
                ->canAccessCurrentPage()
        );

        // test that a normal user can't see another employee's forbidden content
        $employee = Employee::factory()->asNormalEmployee()->create();
        $employeeB = Employee::factory()->asNormalEmployee()->create([
            'company_id' => $employee->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->asUser($employee->user)
            ->forEmployee($employeeB)
            ->forCompanyId($employee->company_id)
            ->asPermissionLevel(config('officelife.permission_level.hr'))
            ->canAccessCurrentPage();

        // // same, but with different companies
        $employee = Employee::factory()->asNormalEmployee()->create();
        $employeeB = Employee::factory()->asNormalEmployee()->create();

        $this->expectException(NotEnoughPermissionException::class);
        $stub->asUser($employee->user)
            ->forEmployee($employeeB)
            ->forCompanyId($employeeB->company_id)
            ->asPermissionLevel(config('officelife.permission_level.hr'))
            ->canAccessCurrentPage();
    }
}
