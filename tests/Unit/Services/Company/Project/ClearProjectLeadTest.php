<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\ClearProjectLead;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClearProjectLeadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_clears_the_project_lead_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
            'project_lead_id' => $michael->id,
        ]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_clears_the_project_lead_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
            'project_lead_id' => $michael->id,
        ]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_clears_the_project_lead_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
            'project_lead_id' => $michael->id,
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
        (new ClearProjectLead)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $project = factory(Project::class)->create([
            'project_lead_id' => $michael->id,
        ]);

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

        $project = (new ClearProjectLead)->execute($request);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'company_id' => $dwight->company_id,
            'project_lead_id' => null,
        ]);

        $this->assertInstanceOf(
            Project::class,
            $project
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'project_team_lead_cleared' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'project_team_lead_cleared' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
