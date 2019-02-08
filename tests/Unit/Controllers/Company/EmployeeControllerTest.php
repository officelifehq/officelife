<?php

namespace Tests\Unit\Controllers\Company;

use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    /** @test */
    public function it_lets_you_see_the_list_of_employees_only_with_the_right_permissions()
    {
        $route = '/account/employees';
        $this->accessibleBy(config('homas.authorizations.administrator'), $route, 200);
        $this->accessibleBy(config('homas.authorizations.hr'), $route, 200);
        $this->accessibleBy(config('homas.authorizations.user'), $route, 401);
    }

    /** @test */
    public function it_lets_you_see_the_add_employee_screen_with_the_right_permissions()
    {
        $route = '/account/employees/create';
        $this->accessibleBy(config('homas.authorizations.administrator'), $route, 200);
        $this->accessibleBy(config('homas.authorizations.hr'), $route, 200);
        $this->accessibleBy(config('homas.authorizations.user'), $route, 401);
    }
}
