<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\IssueType;
use App\Models\Company\ProjectIssue;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProjectIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectIssueTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_issue_in_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'short_code' => 'off',
            'company_id' => $michael->company_id,
        ]);
        $issueType = IssueType::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project, $issueType);
    }

    /** @test */
    public function it_creates_an_issue_in_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'short_code' => 'off',
            'company_id' => $michael->company_id,
        ]);
        $issueType = IssueType::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project, $issueType);
    }

    /** @test */
    public function it_creates_an_issue_in_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'short_code' => 'off',
            'company_id' => $michael->company_id,
        ]);
        $issueType = IssueType::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project, $issueType);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateProjectIssue)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();
        $issueType = IssueType::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $issueType);
    }

    /** @test */
    public function it_fails_if_the_issue_type_is_not_in_the_company(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'short_code' => 'off',
            'company_id' => $michael->company_id,
        ]);
        $issueType = IssueType::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $issueType);
    }

    private function executeService(Employee $michael, Project $project, IssueType $issueType): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'issue_type_id' => $issueType->id,
            'title' => 'issue name',
            'description' => 'this is a description',
        ];

        $projectIssue = (new CreateProjectIssue)->execute($request);

        $this->assertDatabaseHas('project_issues', [
            'id' => $projectIssue->id,
            'project_id' => $project->id,
            'reporter_id' => $michael->id,
            'issue_type_id' => $issueType->id,
            'id_in_project' => 1,
            'key' => 'off-1',
            'slug' => 'issue-name',
            'title' => 'issue name',
            'description' => 'this is a description',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            ProjectIssue::class,
            $projectIssue
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $projectIssue) {
            return $job->auditLog['action'] === 'project_issue_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'title' => $projectIssue->title,
                ]);
        });
    }
}
