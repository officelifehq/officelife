<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\RemoveEmployeeFromProject;

class RemoveEmployeeFromProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_employee_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$dwight->id]);

        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_removes_an_employee_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$dwight->id]);

        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_removes_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);

        $this->executeService($michael, $michael, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new RemoveEmployeeFromProject)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project);
    }

    private function executeService(Employee $michael, Employee $dwight, Project $project): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'project_id' => $project->id,
        ];

        (new RemoveEmployeeFromProject)->execute($request);

        $this->assertDatabaseMissing('employee_project', [
            'project_id' => $project->id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $dwight) {
            return $job->auditLog['action'] === 'employee_removed_from_project' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'employee_removed_from_project' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
