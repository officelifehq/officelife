<?php

namespace Tests\Unit\Jobs\Dummy;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\CompanyPTOPolicy;
use App\Jobs\Dummy\AddDummyEmployeeToCompany;
use App\Services\Company\Adminland\Team\CreateTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Position\CreatePosition;

class AddDummyEmployeeToCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        factory(CompanyPTOPolicy::class)->create([
            'company_id' => $michael->company_id,
            'year' => Carbon::now()->format('Y'),
        ]);

        (new CreatePosition)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'title' => 'Assistant to the regional manager',
        ]);

        $team = (new CreateTeam)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Management',
            'is_dummy' => true,
        ]);

        AddDummyEmployeeToCompany::dispatch([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'email' => 'dwight@dunder.com',
            'first_name' => 'Dwight',
            'last_name' => 'Henrito',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
            'position_name' => 'Assistant to the regional manager',
            'team_name' => 'Management',
            'birthdate' => '1970-01-01',
            'manager_name' => $michael->last_name,
            'leader_of_team_name' => 'Management',
        ]);

        $employee = Employee::where('last_name', 'Henrito')->first();

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'birthdate' => '1970-01-01 00:00:00',
            'is_dummy' => true,
        ]);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'company_id' => $michael->company_id,
            'name' => 'Management',
            'team_leader_id' => $employee->id,
            'is_dummy' => true,
        ]);

        $this->assertDatabaseHas('employee_team', [
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ]);

        $this->assertDatabaseHas('direct_reports', [
            'manager_id' => $michael->id,
            'employee_id' => $employee->id,
        ]);
    }
}
