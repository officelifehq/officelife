<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Comment;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectIssue;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\DestroyProjectIssueComment;

class DestroyProjectIssueCommentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_comment_to_a_issue_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ]);
        $projectIssue->comments()->save($comment);
        $this->executeService($michael, $project, $projectIssue, $comment);
    }

    /** @test */
    public function it_deletes_a_comment_to_a_issue_as_hr(): void
    {
        $michael = $this->createHR();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ]);
        $projectIssue->comments()->save($comment);
        $this->executeService($michael, $project, $projectIssue, $comment);
    }

    /** @test */
    public function it_deletes_a_comment_to_a_issue_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ]);
        $projectIssue->comments()->save($comment);
        $this->executeService($michael, $project, $projectIssue, $comment);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyProjectIssueComment)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create();
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ]);
        $projectIssue->comments()->save($comment);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectIssue, $comment);
    }

    /** @test */
    public function it_fails_if_the_project_issue_is_not_in_the_company(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create();
        $comment = Comment::factory()->create([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ]);
        $projectIssue->comments()->save($comment);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectIssue, $comment);
    }

    /** @test */
    public function it_fails_if_the_comment_is_not_in_the_project_message(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectIssue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $project, $projectIssue, $comment);
    }

    private function executeService(Employee $michael, Project $project, ProjectIssue $projectIssue, Comment $comment): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'project_id' => $project->id,
            'project_issue_id' => $projectIssue->id,
            'comment_id' => $comment->id,
        ];

        (new DestroyProjectIssueComment)->execute($request);

        $this->assertDatabaseMissing('comments', [
            'id' => $projectIssue->id,
            'author_id' => $michael->id,
            'commentable_id' => $projectIssue->id,
            'content' => 'content',
        ]);

        $this->assertDatabaseHas('project_member_activities', [
            'project_id' => $project->id,
            'employee_id' => $michael->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $project, $projectIssue) {
            return $job->auditLog['action'] === 'project_issue_comment_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_issue_id' => $projectIssue->id,
                    'project_issue_title' => $projectIssue->title,
                ]);
        });
    }
}
