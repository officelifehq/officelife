<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PermissionHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_lets_the_employee_see_the_full_birthdate(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_full_birthdate']);
    }

    /** @test */
    public function it_lets_the_employee_see_the_expenses(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $accountant = Employee::factory()->create([
            'can_manage_expenses' => true,
        ]);
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertFalse($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertFalse($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertFalse($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($accountant, $administrator);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($accountant, $hr);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($accountant, $employee);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_see_expenses']);
    }
}
