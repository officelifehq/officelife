<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectMessage;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProjectStatus;
use App\Services\Company\Project\CreateProjectMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectMessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_message_to_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_adds_a_message_to_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $project);
    }

    /** @test */
    public function it_adds_a_message_to_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
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
        (new CreateProjectStatus)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project);
    }

    private function executeService(Employee $michael, Project $project): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'title' => 'email',
            'content' => 'content',
        ];

        $projectMessage = (new CreateProjectMessage)->execute($request);

        $this->assertDatabaseHas('project_messages', [
            'id' => $projectMessage->id,
            'project_id' => $project->id,
            'title' => 'email',
            'content' => 'content',
        ]);

        $this->assertDatabaseHas('project_message_read_status', [
            'project_message_id' => $projectMessage->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            ProjectMessage::class,
            $projectMessage
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $projectMessage) {
            return $job->auditLog['action'] === 'project_message_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'title' => $projectMessage->title,
                ]);
        });
    }
}
