<?php

namespace Tests\Unit\Controllers\Company\Dashboard;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardTeamControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_lets_you_see_the_audit_list_only_with_the_right_permissions() : void
    {
        $employee = factory(Employee::class)->create([]);
        $this->be($employee->user);

        $route = '/'.$employee->company_id.'/dashboard/team';

        $response = $this->get($route);
        $response->assertSee(trans('dashboard.team_no_team_yet'));

        $response = $this->get($route.'/900000');
        $response->assertSee(trans('dashboard.team_dont_exist'));
    }
}
