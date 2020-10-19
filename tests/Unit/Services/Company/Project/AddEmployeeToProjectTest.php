<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\AddEmployeeToProject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddEmployeeToProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_an_employee_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_adds_an_employee_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_adds_an_employee_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $michael, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AddEmployeeToProject)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $project = factory(Project::class)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project);
    }

    private function executeService(Employee $michael, Employee $dwight, Project $project): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'employee_id' => $dwight->id,
        ];

        $employee = (new AddEmployeeToProject)->execute($request);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'company_id' => $dwight->company_id,
        ]);

        $this->assertDatabaseHas('employee_project', [
            'project_id' => $project->id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employee
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $dwight) {
            return $job->auditLog['action'] === 'employee_added_to_project' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'employee_added_to_project' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
