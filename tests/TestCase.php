<?php

namespace Tests;

use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
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
    public function accessibleBy($employee, $permissionLevel, $route, $statusCode) : void
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

    /**
     * Grab the content of the given audit log and decode the json.
     *
     * @param Employee $employee
     * @param string $action
     * @return array
     */
    public function extractAuditLogJson(Employee $employee, string $action) : array
    {
        $log = DB::table('audit_logs')
            ->select('objects')
            ->where('company_id', $employee->company_id)
            ->where('action', $action)
            ->first();

        return json_decode($log->objects, true);
    }
}
