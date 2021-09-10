<?php

namespace Tests\Unit\Services\Company\Adminland\AskMeAnything;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\AskMeAnything\DestroyAskMeAnythingQuestion;

class DestroyAskMeAnythingQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_question_as_administrator(): void
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
    public function it_deletes_a_question_as_hr(): void
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
    public function it_cant_create_a_session_as_normal_user(): void
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
    public function it_fails_if_question_is_not_linked_to_the_session(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $question = AskMeAnythingQuestion::factory()->create([]);
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
        (new DestroyAskMeAnythingQuestion)->execute($request);
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

        (new DestroyAskMeAnythingQuestion)->execute($request);

        $this->assertDatabaseMissing('ask_me_anything_questions', [
            'id' => $ama->id,
        ]);
    }
}
