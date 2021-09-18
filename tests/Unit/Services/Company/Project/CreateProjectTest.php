<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProject;
use App\Exceptions\ProjectCodeAlreadyExistException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_project_and_assigns_a_project_lead(): void
    {
        $michael = $this->createEmployee();
        $jim = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $jim);
    }

    /** @test */
    public function it_fails_if_project_lead_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create([]);
        $jim = Employee::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $jim);
    }

    /** @test */
    public function it_fails_if_project_code_already_exists(): void
    {
        $michael = Employee::factory()->create([]);
        $jim = $this->createAnotherEmployee($michael);
        Project::factory()->create([
            'company_id' => $michael->company_id,
            'status' => Project::CREATED,
            'code' => '123',
        ]);

        $this->expectException(ProjectCodeAlreadyExistException::class);
        $this->executeService($michael, $jim);
    }

    /** @test */
    public function it_fails_if_project_short_code_already_exists(): void
    {
        $michael = Employee::factory()->create([]);
        $jim = $this->createAnotherEmployee($michael);
        Project::factory()->create([
            'company_id' => $michael->company_id,
            'status' => Project::CREATED,
            'short_code' => '123',
        ]);

        $this->expectException(ProjectCodeAlreadyExistException::class);
        $this->executeService($michael, $jim);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateProject)->execute($request);
    }

    private function executeService(Employee $michael, Employee $lead = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_lead_id' => $lead ? $lead->id : null,
            'name' => 'Livraison API v3',
            'code' => '123',
            'short_code' => '123',
            'emoji' => null,
            'description' => null,
        ];

        $project = (new CreateProject)->execute($request);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'project_lead_id' => $lead ? $lead->id : null,
            'name' => 'Livraison API v3',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        if ($lead) {
            $this->assertDatabaseHas('employee_project', [
                'employee_id' => $lead->id,
                'project_id' => $project->id,
            ]);
        }

        $this->assertInstanceOf(
            Project::class,
            $project
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'project_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
