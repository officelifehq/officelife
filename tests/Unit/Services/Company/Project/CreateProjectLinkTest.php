<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectLink;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProjectLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectLinkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_link_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_adds_a_link_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_adds_a_link_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
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
        (new CreateProjectLink)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
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
            'employee_id' => $dwight->id,
            'type' => 'email',
            'url' => 'https',
        ];

        $projectLink = (new CreateProjectLink)->execute($request);

        $this->assertDatabaseHas('project_links', [
            'id' => $projectLink->id,
            'project_id' => $project->id,
            'type' => 'email',
            'url' => 'https',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            ProjectLink::class,
            $projectLink
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $projectLink) {
            return $job->auditLog['action'] === 'project_link_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_link_id' => $projectLink->id,
                    'project_link_name' => $projectLink->label,
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
