<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectSprint;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\UpdateProjectIssuePosition;

class UpdateProjectIssuePositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_project_issue_position_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectSprint, $projectIssue, 4);
    }

    /** @test */
    public function it_updates_the_project_issue_position_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectSprint, $projectIssue, 4);
    }

    /** @test */
    public function it_updates_the_project_issue_position_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectSprint, $projectIssue, 4);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();

        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectSprint, $projectIssue, 4);
    }

    /** @test */
    public function it_fails_if_project_issue_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectIssue = ProjectIssue::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectSprint, $projectIssue, 4);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new UpdateProjectIssuePosition)->execute($request);
    }

    private function executeService(Employee $michael, Project $project, ProjectSprint $sprint, ProjectIssue $issue, int $newposition): void
    {
        Queue::fake();

        $projectIssue1 = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->syncWithoutDetaching([$projectIssue1->id => ['position' => 1]]);
        $sprint->issues()->syncWithoutDetaching([$issue->id => ['position' => 2]]);
        $projectIssue3 = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->syncWithoutDetaching([$projectIssue3->id => ['position' => 3]]);
        $projectIssue4 = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->syncWithoutDetaching([$projectIssue4->id => ['position' => 4]]);
        $projectIssue5 = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $sprint->issues()->syncWithoutDetaching([$projectIssue5->id => ['position' => 5]]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_sprint_id' => $sprint->id,
            'project_issue_id' => $issue->id,
            'new_position' => $newposition,
        ];

        $issue = (new UpdateProjectIssuePosition)->execute($request);

        $this->assertDatabaseHas('project_issue_project_sprint', [
            'project_issue_id' => $issue->id,
            'project_sprint_id' => $sprint->id,
            'position' => 4,
        ]);
        $this->assertDatabaseHas('project_issue_project_sprint', [
            'project_issue_id' => $projectIssue1->id,
            'project_sprint_id' => $sprint->id,
            'position' => 1,
        ]);
        $this->assertDatabaseHas('project_issue_project_sprint', [
            'project_issue_id' => $projectIssue3->id,
            'project_sprint_id' => $sprint->id,
            'position' => 2,
        ]);
        $this->assertDatabaseHas('project_issue_project_sprint', [
            'project_issue_id' => $projectIssue4->id,
            'project_sprint_id' => $sprint->id,
            'position' => 3,
        ]);
        $this->assertDatabaseHas('project_issue_project_sprint', [
            'project_issue_id' => $projectIssue5->id,
            'project_sprint_id' => $sprint->id,
            'position' => 5,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);
    }
}
