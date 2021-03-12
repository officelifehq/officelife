<?php

namespace Tests\Unit\ViewHelpers\Dashboard\Manager;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\AvatarHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Dashboard\Manager\DashboardManagerTimesheetViewHelper;

class DashboardManagerTimesheetViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees_who_have_timesheets_to_approve(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        // creating two employees and adding timesheets after this
        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ]);
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $jim->id,
            'manager_id' => $michael->id,
        ]);

        // creating one timesheet ready to submit
        $project = Project::factory()->create();
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheetA = Timesheet::factory()->create([
            'employee_id' => $dwight->id,
            'status' => Timesheet::READY_TO_SUBMIT,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheetA->id,
            'project_task_id' => $task->id,
        ]);

        // creating one timesheet but not ready - it shouldn't appear in the
        // collection
        $timesheetB = Timesheet::factory()->create([
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheetB->id,
            'project_task_id' => $task->id,
        ]);

        $collection = DashboardManagerTimesheetViewHelper::timesheetApprovals($michael, $michael->directReports);

        // make sure there is only one employee
        $this->assertEquals(
            1,
            $collection->count()
        );

        // now analyzing what's returned from the method
        $this->assertEquals(
            $dwight->id,
            $collection->toArray()[0]['id']
        );
        $this->assertEquals(
            'Dwight Schrute',
            $collection->toArray()[0]['name']
        );
        $this->assertEquals(
            AvatarHelper::getImage($dwight),
            $collection->toArray()[0]['avatar']
        );
        $this->assertEquals(
            $dwight->position->title,
            $collection->toArray()[0]['position']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
            $collection->toArray()[0]['url']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
            $collection->toArray()[0]['url']
        );

        // analyzing timesheets - there should be only one timesheet
        $this->assertEquals(
            1,
            $collection->toArray()[0]['timesheets']->count()
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $timesheetA->id,
                    'started_at' => 'Jan 01, 2018',
                    'ended_at' => 'Jan 07, 2018',
                    'duration' => '01 h 40',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/manager/timesheets/'.$timesheetA->id,
                ],
            ],
            $collection->toArray()[0]['timesheets']->toArray()
        );
    }
}
