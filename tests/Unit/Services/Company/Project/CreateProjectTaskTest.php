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
use App\Services\Company\Project\CreateProjectTask;
use App\Services\Company\Project\CreateProjectStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_task_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);

        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_adds_a_task_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_adds_a_task_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateProjectStatus)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([]);
        $project->employees()->attach([$michael->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_the_project_task_is_not_in_the_project(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([]);
        $project->employees()->attach([$michael->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectTaskList);
    }

    private function executeService(Employee $michael, Project $project, ProjectTaskList $taskList = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_list_id' => $taskList ? $taskList->id : null,
            'title' => 'title',
            'description' => 'description',
        ];

        $task = (new CreateProjectTask)->execute($request);

        $this->assertDatabaseHas('project_tasks', [
            'id' => $task->id,
            'project_id' => $task->project_id,
            'project_task_list_id' => $task->project_task_list_id,
            'author_id' => $task->author_id,
            'assignee_id' => $task->assignee_id,
            'title' => $task->title,
            'description' => $task->description,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            ProjectTask::class,
            $task
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $task) {
            return $job->auditLog['action'] === 'project_task_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_task_id' => $task->id,
                    'project_task_title' => $task->title,
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
