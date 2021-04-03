<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectDecision;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProjectStatus;
use App\Services\Company\Project\CreateProjectDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectDecisionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_decision_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_adds_a_decision_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_adds_a_decision_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateProjectStatus)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create();
        $project->employees()->attach([$michael->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_fails_if_the_decider_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
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
            'project_id' => $project->id,
            'title' => 'email',
            'deciders' => [$dwight->id],
        ];

        $projectDecision = (new CreateProjectDecision)->execute($request);

        $this->assertDatabaseHas('project_decisions', [
            'id' => $projectDecision->id,
            'project_id' => $project->id,
            'title' => 'email',
        ]);

        $this->assertDatabaseHas('project_decision_deciders', [
            'project_decision_id' => $projectDecision->id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertInstanceOf(
            ProjectDecision::class,
            $projectDecision
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $projectDecision) {
            return $job->auditLog['action'] === 'project_decision_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'title' => $projectDecision->title,
                ]);
        });
    }
}
