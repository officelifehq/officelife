<?php

namespace Tests\Unit\Controllers\Company\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;

class EmployeeControllerTest extends TestCase
{
    /** @test */
    public function it_lets_you_see_an_employee_with_the_right_permissions()
    {
        $employee = factory(Employee::class)->create([]);
        $route = '/employees/'.$employee->id;

        $this->accessibleBy($employee, config('homas.authorizations.administrator'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.hr'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.user'), $route, 200);
    }
}
