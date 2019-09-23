<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Team;
use App\Helpers\WorklogHelper;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorklogHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_list_of_worklogs_for_a_given_team_and_a_given_day() : void
    {
        $date = Carbon::now();
        $team = factory(Team::class)->create([]);

        // making employees
        $dwight = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);
        $michael = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $team->employees()->syncWithoutDetaching([$dwight->id => ['company_id' => $team->company_id]]);
        $team->employees()->syncWithoutDetaching([$michael->id => ['company_id' => $team->company_id]]);

        // logging worklogs
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);

        $response = WorklogHelper::getInformationAboutTeam($team, $date);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('day', $response);
        $this->assertArrayHasKey('date', $response);
        $this->assertArrayHasKey('friendlyDate', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('completionRate', $response);
        $this->assertArrayHasKey('numberOfEmployeesInTeam', $response);
        $this->assertArrayHasKey('numberOfEmployeesWhoHaveLoggedWorklogs', $response);

        $this->assertEquals(7, count($response));
    }

    /** @test */
    public function it_gets_the_content_of_the_worklog_for_a_given_employee_and_a_given_day() : void
    {
        $date = Carbon::now();

        $dwight = factory(Employee::class)->create([]);

        // logging worklogs
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);

        // logging morale
        factory(Morale::class)->create([
            'employee_id' => $dwight->id,
            'emotion' => 1,
        ]);

        $response = WorklogHelper::getInformation($dwight, $date);
        $this->assertIsArray($response);

        $this->assertArrayHasKey('date', $response);
        $this->assertArrayHasKey('friendly_date', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('worklog_parsed_content', $response);
        $this->assertArrayHasKey('morale', $response);

        $this->assertEquals(5, count($response));
    }

    /** @test */
    public function it_gets_the_content_of_the_worklog_for_a_given_employee_and_a_given_day_without_morale() : void
    {
        $date = Carbon::now();

        $dwight = factory(Employee::class)->create([]);

        // logging worklogs
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);

        $response = WorklogHelper::getInformation($dwight, $date);
        $this->assertIsArray($response);

        $this->assertArrayHasKey('date', $response);
        $this->assertArrayHasKey('friendly_date', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('worklog_parsed_content', $response);
        $this->assertArrayHasKey('morale', $response);

        $this->assertEquals(5, count($response));
    }
}
