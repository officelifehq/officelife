<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectTaskList;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProjectTaskList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectTaskListTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_task_list_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);

        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_adds_a_task_list_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_adds_a_task_list_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
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
        (new CreateProjectTaskList)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([]);
        $project->employees()->attach([$michael->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project);
    }

    private function executeService(Employee $michael, Project $project): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'title' => 'title',
            'description' => 'description',
        ];

        $taskList = (new CreateProjectTaskList)->execute($request);

        $this->assertDatabaseHas('project_task_lists', [
            'id' => $taskList->id,
            'project_id' => $taskList->project_id,
            'author_id' => $taskList->author_id,
            'title' => $taskList->title,
            'description' => $taskList->description,
        ]);

        $this->assertInstanceOf(
            ProjectTaskList::class,
            $taskList
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $taskList) {
            return $job->auditLog['action'] === 'project_task_list_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_task_list_id' => $taskList->id,
                    'project_task_list_title' => $taskList->title,
                ]);
        });
    }
}
