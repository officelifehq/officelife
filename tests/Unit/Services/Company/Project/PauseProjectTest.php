<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Project\PauseProject;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PauseProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_stops_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_stops_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_stops_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $project = factory(Project::class)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new PauseProject)->execute($request);
    }

    private function executeService(Employee $michael, Project $project = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
        ];

        (new PauseProject)->execute($request);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => Project::PAUSED,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'project_paused' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
