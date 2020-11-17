<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectTask;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\AssignProjecTaskToEmployee;

class AssignProjecTaskToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_task_to_an_employee_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $project->employees()->attach([$dwight->id]);
        $this->executeService($michael, $dwight, $project, $projectTask);
    }

    /** @test */
    public function it_assigns_a_task_to_an_employee_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $project->employees()->attach([$dwight->id]);
        $this->executeService($michael, $dwight, $project, $projectTask);
    }

    /** @test */
    public function it_assigns_a_task_to_an_employee_as_normal_user(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $project->employees()->attach([$dwight->id]);
        $this->executeService($michael, $dwight, $project, $projectTask);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignProjecTaskToEmployee)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create();
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $project->employees()->attach([$dwight->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $projectTask);
    }

    /** @test */
    public function it_fails_if_project_task_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create();
        $project->employees()->attach([$dwight->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $projectTask);
    }

    /** @test */
    public function it_fails_if_assignee_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $projectTask);
    }

    private function executeService(Employee $michael, Employee $assignee, Project $project, ProjectTask $projectTask): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $projectTask->id,
            'assignee_id' => $assignee->id,
        ];

        $task = (new AssignProjecTaskToEmployee)->execute($request);

        $this->assertInstanceOf(
            ProjectTask::class,
            $task
        );

        $this->assertDatabaseHas('project_tasks', [
            'id' => $task->id,
            'assignee_id' => $assignee->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $task, $assignee) {
            return $job->auditLog['action'] === 'project_task_assigned_to_assignee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_task_id' => $task->id,
                    'project_task_title' => $task->title,
                    'assignee_id' => $assignee->id,
                    'assignee_name' => $assignee->name,
                ]);
        });
    }
}
