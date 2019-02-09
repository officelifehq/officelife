<?php

namespace Tests\Unit\Controllers\Account;

use Tests\TestCase;
use App\Models\Company\Employee;

class PermissionControllerTest extends TestCase
{
    /** @test */
    public function it_lets_you_see_the_change_permission_page_with_the_right_permissions()
    {
        $employee = factory(Employee::class)->create([]);
        $employeeB = factory(Employee::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $route = '/account/employees/'.$employeeB->id.'/permissions';

        $this->accessibleBy($employee, config('homas.authorizations.administrator'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.hr'), $route, 200);
        $this->accessibleBy($employee, config('homas.authorizations.user'), $route, 401);
    }
}
