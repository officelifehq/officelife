<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectSprint;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\DestroyProjectIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProjectIssueTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_the_project_issue_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->syncWithoutDetaching([$projectIssue->id => ['position' => 3]]);

        $this->executeService($michael, $project, $sprint, $projectIssue);
    }

    /** @test */
    public function it_destroys_the_project_issue_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->sync([$projectIssue->id => ['position' => 3]]);
        $this->executeService($michael, $project, $sprint, $projectIssue);
    }

    /** @test */
    public function it_destroys_the_project_issue_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->sync([$projectIssue->id => ['position' => 3]]);
        $this->executeService($michael, $project, $sprint, $projectIssue);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();

        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $sprint, $projectIssue);
    }

    /** @test */
    public function it_fails_if_project_issue_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([]);
        $sprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $sprint, $projectIssue);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyProjectIssue)->execute($request);
    }

    private function executeService(Employee $michael, Project $project, ProjectSprint $sprint, ProjectIssue $issue): void
    {
        Queue::fake();

        // this issue, once the given issue will be deleted, should have its position
        // decreased by one
        $olderIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->syncWithoutDetaching([$olderIssue->id => ['position' => 5]]);
        $youngerIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->syncWithoutDetaching([$youngerIssue->id => ['position' => 2]]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_sprint_id' => $sprint->id,
            'project_issue_id' => $issue->id,
            'title' => 'Update issue',
        ];

        (new DestroyProjectIssue)->execute($request);

        $this->assertDatabaseMissing('project_issues', [
            'id' => $issue->id,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertDatabaseHas('project_issue_project_sprint', [
            'project_sprint_id' =>  $sprint->id,
            'project_issue_id' => $olderIssue->id,
            'position' => 4,
        ]);
        $this->assertDatabaseHas('project_issue_project_sprint', [
            'project_sprint_id' =>  $sprint->id,
            'project_issue_id' => $youngerIssue->id,
            'position' => 2,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $issue) {
            return $job->auditLog['action'] === 'project_issue_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_issue_title' => $issue->title,
                ]);
        });
    }
}
