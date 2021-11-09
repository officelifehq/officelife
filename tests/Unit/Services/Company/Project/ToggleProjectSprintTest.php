<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectSprint;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\ToggleProjectSprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ToggleProjectSprintTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_toggles_the_project_sprint_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectSprint);
    }

    /** @test */
    public function it_toggles_the_project_sprint_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectSprint);
    }

    /** @test */
    public function it_toggles_the_project_sprint_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectSprint);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();

        $projectSprint = ProjectSprint::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectSprint);
    }

    /** @test */
    public function it_fails_if_project_sprint_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();
        $projectSprint = ProjectSprint::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectSprint);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new ToggleProjectSprint)->execute($request);
    }

    private function executeService(Employee $michael, Project $project, ProjectSprint $sprint): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_sprint_id' => $sprint->id,
        ];

        (new ToggleProjectSprint)->execute($request);

        $this->assertDatabaseHas('project_sprint_employee_settings', [
            'project_sprint_id' => $sprint->id,
            'employee_id' => $michael->id,
            'collapsed' => true,
        ]);

        (new ToggleProjectSprint)->execute($request);

        $this->assertDatabaseHas('project_sprint_employee_settings', [
            'project_sprint_id' => $sprint->id,
            'employee_id' => $michael->id,
            'collapsed' => false,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);
    }
}
