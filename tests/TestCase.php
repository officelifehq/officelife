<?php

namespace Tests;

use App\Models\Company\Employee;
use App\Models\Company\EmployeeStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Company\Employee\Manager\AssignManager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, CreatesApplication;

    /**
     * Check if the given route is accessible by a user with the given
     * permission.
     *
     * @param Employee $employee
     * @param string   $permissionLevel
     * @param string   $route
     * @param int  $statusCode
     */
    public function accessibleBy($employee, $permissionLevel, $route, $statusCode): void
    {
        $this->be($employee->user);
        $employee->permission_level = $permissionLevel;
        $employee->save();

        $response = $this->get($employee->company_id.$route);
        $response->assertStatus($statusCode);
    }

    /**
     * Create an administrator in an account.
     *
     * @return Employee
     */
    public function createAdministrator(): Employee
    {
        return Employee::factory()->asAdministrator()->create();
    }

    /**
     * Create an employee with HR privileges in an account.
     *
     * @return Employee
     */
    public function createHR(): Employee
    {
        return Employee::factory()->asHR()->create();
    }

    /**
     * Create an employee with User privileges in an account.
     *
     * @return Employee
     */
    public function createEmployee(): Employee
    {
        return Employee::factory()->asNormalEmployee()->create();
    }

    /**
     * Create another employee with User privileges in the same account as the
     * given employee.
     *
     * @return Employee
     */
    public function createAnotherEmployee(Employee $employee): Employee
    {
        return Employee::factory()->create([
            'permission_level' => config('officelife.permission_level.user'),
            'company_id' => $employee->company_id,
        ]);
    }

    /**
     * Create another employee who will be a direct report of the given
     * employee, who will become a manager.
     *
     * @return Employee
     */
    public function createDirectReport(Employee $employee): Employee
    {
        $directReport = Employee::factory()->asAdministrator()->create([
            'company_id' => $employee->company_id,
        ]);

        // make the employee temporary an admin, just to run the AssignManager service
        $pastPermission = $employee->permission_level;
        $employee->permission_level = config('officelife.permission_level.administrator');
        $employee->save();

        (new AssignManager)->execute([
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
            'employee_id' => $directReport->id,
            'manager_id' => $employee->id,
        ]);

        // bring back the old permission level
        $employee->permission_level = $pastPermission;
        $employee->save();

        return $directReport;
    }

    /**
     * Set the employee status.
     *
     * @param Employee $employee
     * @param string $type
     * @return Employee
     */
    public function setEmployeeStatus(Employee $employee, string $type): Employee
    {
        $status = EmployeeStatus::create([
            'company_id' => $employee->company_id,
            'name' => 'Creative',
            'type' => $type,
        ]);

        $employee->employee_status_id = $status->id;
        $employee->save();

        return $employee->refresh();
    }
}
