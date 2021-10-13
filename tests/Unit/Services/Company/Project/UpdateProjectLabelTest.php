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
use App\Services\Company\Project\UpdateProjectLabel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateProjectLabelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_project_label_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectLabel);
    }

    /** @test */
    public function it_updates_the_project_label_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectLabel);
    }

    /** @test */
    public function it_updates_the_project_label_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectLabel);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create();

        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectLabel);
    }

    /** @test */
    public function it_fails_if_project_label_is_not_part_of_the_project(): void
    {
        $michael = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectLabel);
    }

    /** @test */
    public function it_fails_if_the_label_already_exists(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectLabel = ProjectLabel::factory()->create([
            'project_id' => $project->id,
        ]);
        ProjectLabel::factory()->create([
            'project_id' => $project->id,
            'name' => 'label name',
        ]);

        $this->expectException(LabelAlreadyExistException::class);
        $this->executeService($michael, $project, $projectLabel);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new UpdateProjectLabel)->execute($request);
    }

    private function executeService(Employee $michael, Project $project, ProjectLabel $label): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_label_id' => $label->id,
            'name' => 'label name',
        ];

        $label = (new UpdateProjectLabel)->execute($request);

        $this->assertDatabaseHas('project_labels', [
            'id' => $label->id,
            'project_id' => $project->id,
            'name' => 'label name',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $label) {
            return $job->auditLog['action'] === 'project_label_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'name' => $label->name,
                ]);
        });
    }
}
