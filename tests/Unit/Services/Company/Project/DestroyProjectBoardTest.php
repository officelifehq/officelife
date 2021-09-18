<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectBoard;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\DestroyProjectBoard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProjectBoardTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_board_from_a_project_as_administrator(): void
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
    public function it_destroys_a_board_from_a_project_as_hr(): void
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
    public function it_destroys_a_board_from_a_project_as_normal_user(): void
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
        (new DestroyProjectBoard)->execute($request);
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
    public function it_fails_if_the_project_board_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectBoard = ProjectBoard::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectBoard);
    }

    private function executeService(Employee $michael, Project $project, ProjectBoard $board): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_board_id' => $board->id,
        ];

        (new DestroyProjectBoard)->execute($request);

        $this->assertDatabaseMissing('project_boards', [
            'id' => $board->id,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $board) {
            return $job->auditLog['action'] === 'project_board_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'name' => $board->name,
                ]);
        });
    }
}
