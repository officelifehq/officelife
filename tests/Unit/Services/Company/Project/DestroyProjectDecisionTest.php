<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectDecision;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Project\DestroyProjectDecision;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProjectDecisionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_decision_from_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $projectDecision = ProjectDecision::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectDecision);
    }

    /** @test */
    public function it_destroys_a_decision_from_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $projectDecision = ProjectDecision::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectDecision);
    }

    /** @test */
    public function it_destroys_a_decision_from_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $projectDecision = ProjectDecision::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectDecision);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyProjectDecision)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();
        $project->employees()->attach([$michael->id]);
        $projectDecision = ProjectDecision::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectDecision);
    }

    /** @test */
    public function it_fails_if_the_project_decision_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $projectDecision = ProjectDecision::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectDecision);
    }

    private function executeService(Employee $michael, Project $project, ProjectDecision $decision): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_decision_id' => $decision->id,
        ];

        (new DestroyProjectDecision)->execute($request);

        $this->assertDatabaseMissing('project_decisions', [
            'id' => $decision->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $decision) {
            return $job->auditLog['action'] === 'project_decision_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'title' => $decision->title,
                ]);
        });
    }
}
