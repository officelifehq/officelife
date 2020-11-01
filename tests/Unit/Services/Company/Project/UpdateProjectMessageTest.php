<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectMessage;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\UpdateProjectMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateProjectMessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_project_message_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = factory(ProjectMessage::class)->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_updates_the_project_message_as_hr(): void
    {
        $michael = $this->createHR();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = factory(ProjectMessage::class)->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_updates_the_project_message_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = factory(ProjectMessage::class)->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $project = factory(Project::class)->create();

        $projectMessage = factory(ProjectMessage::class)->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_fails_if_project_message_is_not_part_of_the_project(): void
    {
        $michael = factory(Employee::class)->create([]);
        $project = factory(Project::class)->create();
        $projectMessage = factory(ProjectMessage::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new UpdateProjectMessage)->execute($request);
    }

    private function executeService(Employee $michael, Project $project, ProjectMessage $message): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_message_id' => $message->id,
            'title' => 'Update',
            'content' => 'Content',
        ];

        $message = (new UpdateProjectMessage)->execute($request);

        $this->assertDatabaseHas('project_messages', [
            'id' => $message->id,
            'title' => 'Update',
            'content' => 'Content',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $message) {
            return $job->auditLog['action'] === 'project_message_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_message_id' => $message->id,
                    'project_message_title' => $message->title,
                ]);
        });
    }
}
