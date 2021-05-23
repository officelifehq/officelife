<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Models\Company\File;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\AddFileToProject;
use App\Services\Company\Project\AddEmployeeToProject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddFileToProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_file_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $file, $project);
    }

    /** @test */
    public function it_adds_a_file_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $file, $project);
    }

    /** @test */
    public function it_adds_a_file_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $file, $project);
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
    public function it_fails_if_the_file_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $file, $project);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $file, $project);
    }

    private function executeService(Employee $michael, File $file, Project $project): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'file_id' => $file->id,
        ];

        $file = (new AddFileToProject)->execute($request);

        $this->assertDatabaseHas('file_project', [
            'project_id' => $project->id,
            'file_id' => $file->id,
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            File::class,
            $file
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $file) {
            return $job->auditLog['action'] === 'file_added_to_project' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'name' => $file->name,
                ]);
        });
    }
}
