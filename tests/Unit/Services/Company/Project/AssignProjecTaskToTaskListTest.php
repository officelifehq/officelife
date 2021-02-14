<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectTask;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectTaskList;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\AssignProjecTaskToTaskList;

class AssignProjecTaskToTaskListTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_project_task_to_a_task_list_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectTask, $projectTaskList);
    }

    /** @test */
    public function it_adds_a_project_task_to_a_task_list_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectTask, $projectTaskList);
    }

    /** @test */
    public function it_adds_a_project_task_to_a_task_list_as_normal_user(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectTask, $projectTaskList);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignProjecTaskToTaskList)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectTask, $projectTaskList);
    }

    /** @test */
    public function it_fails_if_project_task_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create();
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectTask, $projectTaskList);
    }

    /** @test */
    public function it_fails_if_project_task_list_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectTask, $projectTaskList);
    }

    private function executeService(Employee $michael, Project $project, ProjectTask $projectTask, ProjectTaskList $projectTaskList): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $projectTask->id,
            'project_task_list_id' => $projectTaskList->id,
        ];

        $task = (new AssignProjecTaskToTaskList)->execute($request);

        $this->assertInstanceOf(
            ProjectTask::class,
            $task
        );

        $this->assertDatabaseHas('project_tasks', [
            'id' => $task->id,
            'project_task_list_id' => $projectTaskList->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $task, $projectTaskList) {
            return $job->auditLog['action'] === 'project_task_assigned_to_task_list' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_task_id' => $task->id,
                    'project_task_title' => $task->title,
                    'project_task_list_id' => $projectTaskList->id,
                    'project_task_list_title' => $projectTaskList->title,
                ]);
        });
    }
}
