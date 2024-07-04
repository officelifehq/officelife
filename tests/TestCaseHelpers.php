<?php

namespace Tests;

use App\Models\Company\Employee;

trait TestCaseHelpers
{
    /**
     * Create an administrator in an account.
     */
    public function createAdministrator(): Employee
    {
        return Employee::factory()->asAdministrator()->create();
    }

    /**
     * Create an employee with HR privileges in an account.
     */
    public function createHR(): Employee
    {
        return Employee::factory()->asHR()->create();
    }

    /**
     * Create an employee with User privileges in an account.
     */
    public function createEmployee(): Employee
    {
        return Employee::factory()->asNormalEmployee()->create();
    }
}