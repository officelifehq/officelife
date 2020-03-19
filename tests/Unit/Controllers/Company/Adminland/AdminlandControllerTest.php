<?php

namespace Tests\Unit\Controllers\Company\Adminland;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminlandControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_lets_you_see_the_account_dashboard_with_the_right_permissions(): void
    {
        $route = '/account';
        $employee = factory(Employee::class)->create([]);

        $this->accessibleBy($employee, config('officelife.permission_level.administrator'), $route, 200);
        $this->accessibleBy($employee, config('officelife.permission_level.hr'), $route, 200);
        $this->accessibleBy($employee, config('officelife.permission_level.user'), $route, 401);
    }
}
