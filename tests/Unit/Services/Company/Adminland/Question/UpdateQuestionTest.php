<?php

namespace Tests\Unit\Services\Company\Adminland\Question;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Question\UpdateQuestion;

class UpdateQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_question_as_administrator(): void
    {
        $this->executeService(config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_updates_a_question_as_hr(): void
    {
        $this->executeService(config('officelife.permission_level.hr'));
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $this->executeService(config('officelife.permission_level.user'));
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant Regional Manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateQuestion)->execute($request);
    }

    /** @test */
    public function it_fails_if_question_is_not_linked_to_company(): void
    {
        $question = Question::factory()->create([]);
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $question->company_id,
            'author_id' => $michael->id,
            'question_id' => $question->id,
            'title' => 'Assistant Regional Manager',
            'active' => true,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateQuestion)->execute($request);
    }

    private function executeService(int $permissionLevel): void
    {
        Queue::fake();

        $question = Question::factory()->create([]);
        $michael = Employee::factory()->create([
            'company_id' => $question->company_id,
            'permission_level' => $permissionLevel,
        ]);

        $request = [
            'company_id' => $question->company_id,
            'author_id' => $michael->id,
            'question_id' => $question->id,
            'title' => 'Assistant Regional Manager',
            'active' => true,
        ];

        $newQuestion = (new UpdateQuestion)->execute($request);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'company_id' => $question->company_id,
            'title' => 'Assistant Regional Manager',
            'active' => true,
        ]);

        $this->assertInstanceOf(
            Question::class,
            $question
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $question, $newQuestion) {
            return $job->auditLog['action'] === 'question_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'question_id' => $newQuestion->id,
                    'question_title' => $newQuestion->title,
                    'question_old_title' => $question->title,
                ]);
        });
    }
}
