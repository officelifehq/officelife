<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectMessage;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Project\DestroyProjectDecision;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\MarkProjectMessageasRead;

class MarkProjectMessageasReadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_reads_a_message_from_a_project_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = ProjectMessage::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_reads_a_message_from_a_project_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = ProjectMessage::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_reads_a_message_from_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = ProjectMessage::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($michael, $project, $projectMessage);
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
        $projectMessage = ProjectMessage::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectMessage);
    }

    /** @test */
    public function it_fails_if_the_project_message_is_not_part_of_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = ProjectMessage::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectMessage);
    }

    private function executeService(Employee $michael, Project $project, ProjectMessage $message): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_message_id' => $message->id,
        ];

        (new MarkProjectMessageasRead)->execute($request);

        $this->assertDatabaseHas('project_message_read_status', [
            'project_message_id' => $message->id,
            'employee_id' => $michael->id,
        ]);
    }
}
