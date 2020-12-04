<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

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

        $collection = DashboardTimesheetViewHelper::tasks($project, $timesheet);

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
