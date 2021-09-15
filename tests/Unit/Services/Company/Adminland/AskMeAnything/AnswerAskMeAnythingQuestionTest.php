<?php

namespace Tests\Unit\Services\Company\Adminland\AskMeAnything;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\AskMeAnything\AnswerAskMeAnythingQuestion;

class AnswerAskMeAnythingQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_question_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $question = AskMeAnythingQuestion::factory()->create([
            'ask_me_anything_session_id' => $ama->id,
        ]);
        $this->executeService($michael, $ama, $question);
    }

    /** @test */
    public function it_updates_a_question_as_hr(): void
    {
        $michael = $this->createHR();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $question = AskMeAnythingQuestion::factory()->create([
            'ask_me_anything_session_id' => $ama->id,
        ]);
        $this->executeService($michael, $ama, $question);
    }

    /** @test */
    public function it_cant_update_a_session_as_normal_user(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $question = AskMeAnythingQuestion::factory()->create([
            'ask_me_anything_session_id' => $ama->id,
        ]);
        $this->executeService($michael, $ama, $question);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new AnswerAskMeAnythingQuestion)->execute($request);
    }

    /** @test */
    public function it_fails_if_question_doesnt_belong_to_a_session(): void
    {
        $michael = $this->createAdministrator();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $question = AskMeAnythingQuestion::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $ama, $question);
    }

    private function executeService(Employee $michael, AskMeAnythingSession $ama, AskMeAnythingQuestion $question): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'ask_me_anything_session_id' => $ama->id,
            'ask_me_anything_question_id' => $question->id,
        ];

        $question = (new AnswerAskMeAnythingQuestion)->execute($request);

        $this->assertInstanceOf(
            AskMeAnythingQuestion::class,
            $question
        );

        $this->assertDatabaseHas('ask_me_anything_questions', [
            'id' => $question->id,
            'answered' => true,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $ama) {
            return $job->auditLog['action'] === 'ask_me_anything_question_answered' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'ask_me_anything_session_id' => $ama->id,
                ]);
        });
    }
}
