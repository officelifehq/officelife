<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectIssue;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\DuplicateIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DuplicateIssueTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_duplicates_a_project_issue_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectIssue);
    }

    /** @test */
    public function it_duplicates_a_project_issue_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectIssue);
    }

    /** @test */
    public function it_duplicates_a_project_issue_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectIssue);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DuplicateIssue)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectIssue);
    }

    /** @test */
    public function it_fails_if_the_project_issue_is_not_in_the_company(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectIssue);
    }

    private function executeService(Employee $michael, Project $project, ProjectIssue $projectIssue): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_issue_id' => $projectIssue->id,
        ];

        $issue = (new DuplicateIssue)->execute($request);

        $this->assertDatabaseHas('project_issues', [
            'id' => $issue->id,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            ProjectIssue::class,
            $issue
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $issue) {
            return $job->auditLog['action'] === 'project_issue_duplicated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_issue_id' => $issue->id,
                    'project_issue_title' => $issue->title,
                ]);
        });
    }
}
