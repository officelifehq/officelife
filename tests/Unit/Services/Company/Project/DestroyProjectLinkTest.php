<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectLink;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\DestroyProjectLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProjectLinkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_link_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLink = ProjectLink::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $dwight, $project, $projectLink);
    }

    /** @test */
    public function it_destroys_a_link_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLink = ProjectLink::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $dwight, $project, $projectLink);
    }

    /** @test */
    public function it_destroys_a_link_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLink = ProjectLink::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $michael, $project, $projectLink);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyProjectLink)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $project = Project::factory()->create();
        $projectLink = ProjectLink::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $projectLink);
    }

    /** @test */
    public function it_fails_if_the_project_link_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLink = ProjectLink::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project, $projectLink);
    }

    private function executeService(Employee $michael, Employee $dwight, Project $project, ProjectLink $link): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_link_id' => $link->id,
        ];

        (new DestroyProjectLink)->execute($request);

        $this->assertDatabaseMissing('project_links', [
            'id' => $link->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $link) {
            return $job->auditLog['action'] === 'project_link_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_link_name' => $link->label,
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
