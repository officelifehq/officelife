<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectLabel;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\AssignLabelToIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssignLabelToIssueTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_the_project_label_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectLabel, $projectIssue);
    }

    /** @test */
    public function it_assigns_the_project_label_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectLabel, $projectIssue);
    }

    /** @test */
    public function it_assigns_the_project_label_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectLabel, $projectIssue);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();

        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectLabel, $projectIssue);
    }

    /** @test */
    public function it_fails_if_project_label_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectLabel, $projectIssue);
    }

    /** @test */
    public function it_fails_if_project_issue_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectLabel, $projectIssue);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new AssignLabelToIssue)->execute($request);
    }

    private function executeService(Employee $michael, Project $project, ProjectLabel $label, ProjectIssue $issue): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_label_id' => $label->id,
            'project_issue_id' => $issue->id,
        ];

        (new AssignLabelToIssue)->execute($request);

        $this->assertDatabaseHas('project_issue_project_label', [
            'project_label_id' => $label->id,
            'project_issue_id' => $issue->id,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $label, $issue) {
            return $job->auditLog['action'] === 'project_label_assigned_to_issue' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'issue_title' => $issue->title,
                    'label_name' => $label->name,
                ]);
        });
    }
}
