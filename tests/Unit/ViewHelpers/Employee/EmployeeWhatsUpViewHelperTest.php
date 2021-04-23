<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Models\Company\Project;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\WorkFromHome;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\TimeTrackingEntry;
use App\Models\Company\ProjectMemberActivity;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeWhatsUpViewHelper;

class EmployeeWhatsUpViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_information_about_the_one_on_ones(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        // one on ones
        OneOnOneEntry::factory()->count(2)->create([
            'employee_id' => $dwight->id,
            'happened_at' => Carbon::now(),
        ]);
        OneOnOneEntry::factory()->create([
            'employee_id' => $dwight->id,
            'happened_at' => Carbon::now()->subMonths(4),
        ]);

        $result = EmployeeWhatsUpViewHelper::oneOnOnes($dwight, $startDate, $endDate, $michael->company);

        $this->assertEquals(
            2,
            $result
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_recent_ships(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        // two teams
        $sales = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sales->employees()->attach([$dwight->id]);

        // recent ships (Accomplishments)
        $ship = Ship::factory()->create([
            'team_id' => $sales->id,
            'created_at' => Carbon::now(),
        ]);
        $shipB = Ship::factory()->create([
            'team_id' => $sales->id,
            'created_at' => Carbon::now()->subMonths(4),
        ]);
        $ship->employees()->attach([$dwight->id]);
        $shipB->employees()->attach([$dwight->id]);

        $collection = EmployeeWhatsUpViewHelper::accomplishments($dwight, $startDate, $endDate, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $ship->id,
                    'title' => $ship->title,
                    'description' => $ship->description,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/teams/'.$sales->id.'/ships/'.$ship->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_projects(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        // projects
        $projectA = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectB = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectA->employees()->attach([$dwight->id]);
        $projectB->employees()->attach([$dwight->id]);

        // project activity
        ProjectMemberActivity::factory()->create([
            'project_id' => $projectA->id,
            'employee_id' => $dwight->id,
            'created_at' => Carbon::now(),
        ]);
        ProjectMemberActivity::factory()->create([
            'project_id' => $projectB->id,
            'employee_id' => $dwight->id,
            'created_at' => Carbon::now()->subMonths(4),
        ]);

        $collection = EmployeeWhatsUpViewHelper::projects($dwight, $startDate, $endDate, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectA->id,
                    'name' => $projectA->name,
                    'code' => $projectA->code,
                    'summary' => $projectA->summary,
                    'status' => $projectA->status,
                    'status_i18n' => trans('project.summary_status_'.$projectA->status),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$projectA->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_timesheets(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        // timesheets
        $timesheetA = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'started_at' => Carbon::now(),
        ]);
        $timesheetB = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'started_at' => Carbon::now()->addWeeks(2),
        ]);

        TimeTrackingEntry::factory()->count(3)->create([
            'timesheet_id' => $timesheetA->id,
            'employee_id' => $dwight->id,
            'duration' => 100,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheetB->id,
            'employee_id' => $dwight->id,
            'duration' => 50,
        ]);

        $array = EmployeeWhatsUpViewHelper::timesheets($dwight, $startDate, $endDate, $michael->company);

        $this->assertEquals(
            3,
            $array['average_hours_worked']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $timesheetA->id,
                    'started_at' => '2018-01-01',
                    'ended_at' => '2018-01-07',
                    'minutes_worked' => 300,
                ],
                1 => [
                    'id' => $timesheetB->id,
                    'started_at' => '2018-01-15',
                    'ended_at' => '2018-01-07',
                    'minutes_worked' => 50,
                ],
            ],
            $array['data']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_external_status(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = Employee::factory()->create([
            'contract_renewed_at' => null,
        ]);

        $this->assertNull(
            EmployeeWhatsUpViewHelper::external($michael, $startDate, $endDate)
        );

        $michael = Employee::factory()->create([]);

        $this->assertNull(
            EmployeeWhatsUpViewHelper::external($michael, $startDate, $endDate)
        );

        $michael = Employee::factory()->asExternal()->create([
            'contract_renewed_at' => Carbon::now(),
        ]);

        $this->assertEquals(
            [
                'contract_renews_in_timeframe' => true,
                'hired_date' => 'Jan 01, 2018',
            ],
            EmployeeWhatsUpViewHelper::external($michael, $startDate, $endDate)
        );
    }

    /** @test */
    public function it_gets_the_information_about_working_from_homes(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = Employee::factory()->create();

        WorkFromHome::factory()->count(2)->create([
            'employee_id' => $michael->id,
            'date' => Carbon::now(),
        ]);

        $this->assertEquals(
            [
                'number_times_work_from_home' => 2,
                'percent_work_from_home' => 2,
            ],
            EmployeeWhatsUpViewHelper::workFromHome($michael, $startDate, $endDate)
        );
    }

    /** @test */
    public function it_gets_the_number_of_worklogs(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = Employee::factory()->create();

        Worklog::factory()->count(4)->create([
            'employee_id' => $michael->id,
            'created_at' => Carbon::now(),
        ]);

        $this->assertEquals(
            [
                'number_worklogs' => 4,
                'percent_completion' => 3,
            ],
            EmployeeWhatsUpViewHelper::worklogs($michael, $startDate, $endDate)
        );
    }
}
