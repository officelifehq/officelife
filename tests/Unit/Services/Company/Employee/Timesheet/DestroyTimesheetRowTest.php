<?php

namespace Tests\Unit\Services\Company\Employee\Timesheet;

use Tests\TestCase;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Timesheet\DestroyTimesheetRow;

class DestroyTimesheetRowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_timesheet_row_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $dwight, $project, $task, $timesheet);
    }

    /** @test */
    public function it_destroys_a_timesheet_row_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $dwight, $project, $task, $timesheet);
    }

    /** @test */
    public function it_destroys_a_timesheet_row_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $michael->id,
        ]);
        $this->executeService($michael, $michael, $project, $task, $timesheet);
    }

    /** @test */
    public function normal_user_cant_destroy_a_timesheet_on_behalf_of_another_employee(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $dwight->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight, $project, $task, $timesheet);
    }

    /** @test */
    public function it_fails_if_employee_is_not_part_of_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $jim = factory(Employee::class)->create([]);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'company_id' => $michael->company_id,
            'employee_id' => $jim->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $jim->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $jim, $project, $task, $timesheet);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create();
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $dwight->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $task, $timesheet);
    }

    /** @test */
    public function it_fails_if_project_task_is_not_part_of_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $dwight->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $task, $timesheet);
    }

    /** @test */
    public function it_fails_if_timesheet_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'started_at' => '2020-11-17 00:00:00',
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'timesheet_id' => $timesheet->id,
            'happened_at' => '2020-11-17',
            'employee_id' => $dwight->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $task, $timesheet);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyTimesheetRow)->execute($request);
    }

    private function executeService(Employee $author, Employee $employee, Project $project = null, ProjectTask $task, Timesheet $timesheet): void
    {
        Queue::fake();

        $request = [
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'timesheet_id' => $timesheet->id,
            'employee_id' => $employee->id,
            'project_id' => $project->id,
            'project_task_id' => $task->id,
        ];

        (new DestroyTimesheetRow)->execute($request);

        $this->assertDatabaseMissing('time_tracking_entries', [
            'project_id' => $project->id,
            'project_task_id' => $task->id,
        ]);
    }
}
