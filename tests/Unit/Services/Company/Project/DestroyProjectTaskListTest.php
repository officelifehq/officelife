<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectTaskList;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Project\DestroyProjectTaskList;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProjectTaskListTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_task_list_from_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectTaskList);
    }

    /** @test */
    public function it_destroys_a_task_list_from_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectTaskList);
    }

    /** @test */
    public function it_destroys_a_task_list_from_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectTaskList);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyProjectTaskList)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();
        $projectTaskList = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectTaskList);
    }

    /** @test */
    public function it_fails_if_the_project_task_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskList = ProjectTaskList::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectTaskList);
    }

    private function executeService(Employee $michael, Project $project, ProjectTaskList $taskList): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_list_id' => $taskList->id,
        ];

        (new DestroyProjectTaskList)->execute($request);

        $this->assertDatabaseMissing('project_task_lists', [
            'id' => $taskList->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $taskList) {
            return $job->auditLog['action'] === 'project_task_list_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'title' => $taskList->title,
                ]);
        });
    }
}
