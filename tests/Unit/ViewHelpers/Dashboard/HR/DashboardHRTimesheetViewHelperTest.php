<?php

namespace Tests\Unit\ViewHelpers\Dashboard\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRTimesheetViewHelper;

class DashboardHRTimesheetViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees_who_have_timesheets_to_approve(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        // creating two employees and adding timesheets after this
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ]);

        // creating one timesheet ready to submit for dwight
        $project = Project::factory()->create();
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheetA = Timesheet::factory()->create([
            'company_id' => $dwight->company_id,
            'employee_id' => $dwight->id,
            'status' => Timesheet::READY_TO_SUBMIT,
            'started_at' => '2017-12-25 00:00:00',
        ]);

        // creating one timesheet for jim
        $timesheetB = Timesheet::factory()->create([
            'company_id' => $dwight->company_id,
            'employee_id' => $jim->id,
            'status' => Timesheet::READY_TO_SUBMIT,
            'started_at' => '2017-12-25 00:00:00',
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheetB->id,
            'project_task_id' => $task->id,
        ]);

        $collection = DashboardHRTimesheetViewHelper::timesheetApprovalsForEmployeesWithoutManagers($michael->company);

        // make sure there is only one employee
        $this->assertEquals(
            1,
            $collection->count()
        );

        // now analyzing what's returned from the method
        $this->assertEquals(
            $jim->id,
            $collection->toArray()[0]['id']
        );
        $this->assertEquals(
            'Dwight Schrute',
            $collection->toArray()[0]['name']
        );
        $this->assertEquals(
            $jim->avatar,
            $collection->toArray()[0]['avatar']
        );
        $this->assertEquals(
            $jim->position->title,
            $collection->toArray()[0]['position']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
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
                    'id' => $timesheetB->id,
                    'started_at' => 'Dec 25, 2017',
                    'ended_at' => 'Jan 07, 2018',
                    'duration' => '01 h 40',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/timesheets/'.$timesheetB->id,
                ],
            ],
            $collection->toArray()[0]['timesheets']->toArray()
        );
    }
}
