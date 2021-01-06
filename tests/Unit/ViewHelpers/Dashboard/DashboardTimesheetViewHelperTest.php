<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Project;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\DashboardTimesheetViewHelper;

class DashboardTimesheetViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_details_of_a_timesheet(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
        ]);
        $timeTrackingEntry = TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheet->id,
            'employee_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'happened_at' => Carbon::now()->startOfWeek(),
            'duration' => 100,
        ]);

        $array = DashboardTimesheetViewHelper::show($timesheet);

        $this->assertEquals(
            $timesheet->id,
            $array['id']
        );

        $this->assertEquals(
            $timesheet->status,
            $array['status']
        );

        $this->assertEquals(
            'Jan 01, 2018',
            $array['start_date']
        );

        $this->assertEquals(
            'Jan 07, 2018',
            $array['end_date']
        );

        // Content of the Entries collection
        $this->assertEquals(
            '123456',
            $array['entries']->toArray()[0]['project_code']
        );

        $this->assertEquals(
            $project->id,
            $array['entries']->toArray()[0]['project_id']
        );

        $this->assertEquals(
            'API v3',
            $array['entries']->toArray()[0]['project_name']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id,
            $array['entries']->toArray()[0]['project_url']
        );

        $this->assertEquals(
            $task->id,
            $array['entries']->toArray()[0]['task_id']
        );

        $this->assertEquals(
            $task->title,
            $array['entries']->toArray()[0]['task_title']
        );

        $this->assertEquals(
            100,
            $array['entries']->toArray()[0]['total_this_week']
        );

        $this->assertEquals(
            [
                0 => [
                    'day_of_week' => 1,
                    'hours' => 1,
                    'minutes' => 40,
                    'total_of_minutes' => 100,
                ],
                1 => [
                    'day_of_week' => 2,
                    'hours' => 0,
                    'minutes' => 0,
                    'total_of_minutes' => 0,
                ],
                2 => [
                    'day_of_week' => 3,
                    'hours' => 0,
                    'minutes' => 0,
                    'total_of_minutes' => 0,
                ],
                3 => [
                    'day_of_week' => 4,
                    'hours' => 0,
                    'minutes' => 0,
                    'total_of_minutes' => 0,
                ],
                4 => [
                    'day_of_week' => 5,
                    'hours' => 0,
                    'minutes' => 0,
                    'total_of_minutes' => 0,
                ],
                5 => [
                    'day_of_week' => 6,
                    'hours' => 0,
                    'minutes' => 0,
                    'total_of_minutes' => 0,
                ],
                6 => [
                    'day_of_week' => 0,
                    'hours' => 0,
                    'minutes' => 0,
                    'total_of_minutes' => 0,
                ],
            ],
            $array['entries']->toArray()[0]['days']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_approver_if_it_exists(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $date = Carbon::now();

        $michael = $this->createAdministrator();
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
            'status' => Timesheet::OPEN,
        ]);

        $array = DashboardTimesheetViewHelper::approverInformation($timesheet);
        $this->assertEmpty($array);

        // change the timesheet to approved BUT without an existing approver
        $timesheet->status = Timesheet::APPROVED;
        $timesheet->approver_id = null;
        $timesheet->approved_at = $date;
        $timesheet->approver_name = 'Henri Troyat';
        $timesheet->save();
        $timesheet->refresh();

        $array = DashboardTimesheetViewHelper::approverInformation($timesheet);

        $this->assertEquals(
            [
                'name' => 'Henri Troyat',
                'approved_at' => 'Jan 01, 2018',
            ],
            $array
        );

        // change the timesheet to approved BUT with an existing approver
        $timesheet->status = Timesheet::APPROVED;
        $timesheet->approver_id = $michael->id;
        $timesheet->approved_at = $date;
        $timesheet->save();
        $timesheet->refresh();

        $array = DashboardTimesheetViewHelper::approverInformation($timesheet);
        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'approved_at' => 'Jan 01, 2018',
                'avatar' => $michael->avatar,
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_an_array_of_the_days_that_should_be_displayed_in_the_header(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
        ]);

        $array = DashboardTimesheetViewHelper::daysHeader($timesheet);

        $this->assertEquals(
            [
                'monday' => [
                    'full' => 'Jan 01',
                    'short' => 'Mon',
                ],
                'tuesday' => [
                    'full' => 'Jan 02',
                    'short' => 'Tue',
                ],
                'wednesday' => [
                    'full' => 'Jan 03',
                    'short' => 'Wed',
                ],
                'thursday' => [
                    'full' => 'Jan 04',
                    'short' => 'Thu',
                ],
                'friday' => [
                    'full' => 'Jan 05',
                    'short' => 'Fri',
                ],
                'saturday' => [
                    'full' => 'Jan 06',
                    'short' => 'Sat',
                ],
                'sunday' => [
                    'full' => 'Jan 07',
                    'short' => 'Sun',
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_previous_timesheet(): void
    {
        $michael = $this->createAdministrator();
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $array = DashboardTimesheetViewHelper::previousTimesheet($timesheet, $michael);

        // a timesheet should have been created in the db for the previous timesheet
        $previousTimesheet = Timesheet::orderBy('id', 'desc')->first();

        $this->assertEquals(
            [
                'id' => $previousTimesheet->id,
                'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/timesheet/'.$previousTimesheet->id,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_next_timesheet(): void
    {
        $michael = $this->createAdministrator();
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $array = DashboardTimesheetViewHelper::nextTimesheet($timesheet, $michael);

        // a timesheet should have been created in the db for the previous timesheet
        $nextTimesheet = Timesheet::orderBy('id', 'desc')->first();

        $this->assertEquals(
            [
                'id' => $nextTimesheet->id,
                'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/timesheet/'.$nextTimesheet->id,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_current_timesheet(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'started_at' => '2017-01-01 00:00:00',
            'ended_at' => '2017-01-07 00:00:00',
        ]);

        $array = DashboardTimesheetViewHelper::currentTimesheet($michael);

        $currentTimesheet = Timesheet::orderBy('id', 'desc')->first();

        $this->assertEquals(
            [
                'id' => $currentTimesheet->id,
                'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/timesheet/'.$currentTimesheet->id,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_a_list_of_projects_for_the_given_employee(): void
    {
        $dwight = $this->createAdministrator();
        $project = Project::factory()->create();
        $project->employees()->syncWithoutDetaching([$dwight->id]);

        $collection = DashboardTimesheetViewHelper::projects($dwight);

        $this->assertEquals(
            [
                0 => [
                    'value' => $project->id,
                    'label' => $project->name,
                    'code' => $project->code,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_list_of_tasks_for_a_given_project_and_not_already_present_in_a_timesheet(): void
    {
        $project = Project::factory()->create();
        $taskA = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $taskB = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);

        $timesheet = Timesheet::factory()->create();
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheet->id,
            'project_task_id' => $taskA->id,
        ]);

        $collection = DashboardTimesheetViewHelper::availableTasks($project, $timesheet);

        $this->assertEquals(
            [
                0 => [
                    'value' => $taskB->id,
                    'label' => $taskB->title,
                ],
            ],
            $collection->toArray()
        );
    }
}
