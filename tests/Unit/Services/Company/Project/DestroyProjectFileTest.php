<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Models\Company\File;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\DestroyProjectFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProjectFileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_a_file_from_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->files()->syncWithoutDetaching([
            $file->id,
        ]);
        $this->executeService($michael, $file, $project);
    }

    /** @test */
    public function it_removes_a_file_from_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->files()->syncWithoutDetaching([
            $file->id,
        ]);
        $this->executeService($michael, $file, $project);
    }

    /** @test */
    public function it_removes_a_file_from_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->files()->syncWithoutDetaching([
            $file->id,
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
        (new DestroyProjectFile)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project = Project::factory()->create();
        $project->files()->syncWithoutDetaching([
            $file->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $file, $project);
    }

    private function executeService(Employee $michael, File $file, Project $project): void
    {
        Queue::fake();
        Event::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'file_id' => $file->id,
        ];

        (new DestroyProjectFile)->execute($request);

        $this->assertDatabaseMissing('file_project', [
            'project_id' => $project->id,
            'file_id' => $file->id,
        ]);

        $this->assertDatabaseMissing('files', [
            'id' => $file->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $file) {
            return $job->auditLog['action'] === 'project_file_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'name' => $file->name,
                ]);
        });
    }
}
