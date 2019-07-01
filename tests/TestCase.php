<?php

namespace Tests;

use App\Models\User\User;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Check if the given route is accessible by a user with the given
     * permission.
     *
     * @param Employee $employee
     * @param string $permissionLevel
     * @param string $route
     * @param int $statusCode
     */
    public function accessibleBy($employee, $permissionLevel, $route, $statusCode)
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
    public function createAdministrator() : Employee
    {
        return factory(Employee::class)->create([
            'permission_level' => config('homas.authorizations.administrator'),
        ]);
    }
}
