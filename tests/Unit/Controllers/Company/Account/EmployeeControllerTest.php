<?php

namespace Tests\Unit\Controllers\Company\Account;

use Tests\TestCase;
use App\Models\Company\Employee;

class EmployeeControllerTest extends TestCase
{
    /** @test */
    public function it_lets_you_see_the_list_of_employees_only_with_the_right_permissions()
    {
        $route = '/account/employees';
        $employee = factory(Employee::class)->create([]);

        $this->accessibleBy($employee, config('homas.authorizations.administrator'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.hr'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.user'), $route, 401);
    }

    /** @test */
    public function it_lets_you_see_the_add_employee_screen_with_the_right_permissions()
    {
        $route = '/account/employees/create';
        $employee = factory(Employee::class)->create([]);

        $this->accessibleBy($employee, config('homas.authorizations.administrator'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.hr'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.user'), $route, 401);
    }
}
