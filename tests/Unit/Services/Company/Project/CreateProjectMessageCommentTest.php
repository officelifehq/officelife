<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Comment;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ProjectMessage;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\CreateProjectMessageComment;

class CreateProjectMessageCommentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_comment_to_a_project_as_administrator(): void
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
    public function it_adds_a_comment_to_a_project_as_hr(): void
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
    public function it_adds_a_comment_to_a_project_as_normal_user(): void
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
        (new CreateProjectMessageComment)->execute($request);
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
    public function it_fails_if_the_project_message_is_not_in_the_company(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = ProjectMessage::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectMessage);
    }

    private function executeService(Employee $michael, Project $project, ProjectMessage $projectMessage): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_message_id' => $projectMessage->id,
            'content' => 'content',
        ];

        $comment = (new CreateProjectMessageComment)->execute($request);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'commentable_id' => $projectMessage->id,
            'content' => 'content',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            Comment::class,
            $comment
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project) {
            return $job->auditLog['action'] === 'project_message_comment_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                ]);
        });
    }
}
