<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\UpdateProjectDescription;

class UpdateProjectDescriptionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_project_description_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_updates_the_project_description_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_updates_the_project_description_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new UpdateProjectDescription)->execute($request);
    }

    private function executeService(Employee $michael, Project $project = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'description' => 'Project description',
        ];

        (new UpdateProjectDescription)->execute($request);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'description' => 'Project description',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'project_description_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
