<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectSprint;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProjectSprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectSprintTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_sprint_in_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectBoard = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectBoard);
    }

    /** @test */
    public function it_creates_a_sprint_in_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectBoard = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectBoard);
    }

    /** @test */
    public function it_creates_a_sprint_in_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectBoard = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectBoard);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateProjectSprint)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();
        $projectBoard = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectBoard);
    }

    /** @test */
    public function it_fails_if_the_projectboard_is_not_in_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectBoard = ProjectBoard::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectBoard);
    }

    private function executeService(Employee $michael, Project $project, ProjectBoard $board): void
    {
        Queue::fake();

        // create another sprint to make sure that when the sprint is created
        // in the board, the newly added sprint takes the position 0
        $existingSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
            'project_board_id' => $board->id,
            'position' => 0,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_board_id' => $board->id,
            'name' => 'board name',
        ];

        $projectSprint = (new CreateProjectSprint)->execute($request);

        $this->assertDatabaseHas('project_sprints', [
            'id' => $projectSprint->id,
            'project_id' => $project->id,
            'project_board_id' => $board->id,
            'name' => 'board name',
            'position' => 0,
        ]);

        $this->assertDatabaseHas('project_sprints', [
            'id' => $existingSprint->id,
            'project_id' => $project->id,
            'project_board_id' => $board->id,
            'position' => 1,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            ProjectSprint::class,
            $projectSprint
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $projectSprint) {
            return $job->auditLog['action'] === 'project_sprint_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'name' => $projectSprint->name,
                ]);
        });
    }
}
