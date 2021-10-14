<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectIssue;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\RemoveParentIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RemoveParentIssueTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_a_parent_issue_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $parentIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $childIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $parentIssue, $childIssue);
    }

    /** @test */
    public function it_removes_a_parent_issue_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $parentIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $childIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $parentIssue, $childIssue);
    }

    /** @test */
    public function it_removes_a_parent_issue_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $parentIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $childIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $parentIssue, $childIssue);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();
        $parentIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $childIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $parentIssue, $childIssue);
    }

    /** @test */
    public function it_fails_if_parent_project_issue_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $parentIssue = ProjectIssue::factory()->create();
        $childIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $parentIssue, $childIssue);
    }

    /** @test */
    public function it_fails_if_child_project_issue_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $parentIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $childIssue = ProjectIssue::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $parentIssue, $childIssue);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveParentIssue)->execute($request);
    }

    private function executeService(Employee $michael, Project $project, ProjectIssue $parentIssue, ProjectIssue $childIssue): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'parent_project_issue_id' => $parentIssue->id,
            'project_issue_id' => $childIssue->id,
        ];

        (new RemoveParentIssue)->execute($request);

        $this->assertDatabaseMissing('project_issue_parents', [
            'parent_project_issue_id' => $parentIssue->id,
            'child_project_issue_id' => $childIssue->id,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $parentIssue, $childIssue) {
            return $job->auditLog['action'] === 'project_issue_parent_removed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'issue_title' => $childIssue->title,
                    'parent_issue_title' => $parentIssue->title,
                ]);
        });
    }
}
