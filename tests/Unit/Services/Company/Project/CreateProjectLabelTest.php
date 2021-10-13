<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectLabel;
use Illuminate\Support\Facades\Queue;
use App\Exceptions\LabelAlreadyExistException;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProjectLabel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectLabelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_label_in_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'short_code' => 'off',
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_creates_a_label_in_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'short_code' => 'off',
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_creates_a_label_in_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'short_code' => 'off',
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateProjectLabel)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_fails_if_the_label_already_exists(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        ProjectLabel::factory()->create([
            'project_id' => $project->id,
            'name' => 'label name',
        ]);

        $this->expectException(LabelAlreadyExistException::class);
        $this->executeService($michael, $project);
    }

    private function executeService(Employee $michael, Project $project): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'name' => 'label name',
        ];

        $projectLabel = (new CreateProjectLabel)->execute($request);

        $this->assertDatabaseHas('project_labels', [
            'id' => $projectLabel->id,
            'project_id' => $project->id,
            'name' => 'label name',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            ProjectLabel::class,
            $projectLabel
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $projectLabel) {
            return $job->auditLog['action'] === 'project_label_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'name' => $projectLabel->name,
                ]);
        });
    }
}
