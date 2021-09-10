<?php

namespace Tests\Unit\Services\Company\Adminland\AskMeAnything;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingQuestion;

class CreateAskMeAnythingQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_question_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $ama);
    }

    /** @test */
    public function it_creates_a_question_as_hr(): void
    {
        $michael = $this->createHR();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $ama);
    }

    /** @test */
    public function it_creates_a_question_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $ama);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateAskMeAnythingQuestion)->execute($request);
    }

    private function executeService(Employee $michael, AskMeAnythingSession $ama): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'ask_me_anything_session_id' => $ama->id,
            'question' => 'this is a question',
            'anonymous' => false,
        ];

        $question = (new CreateAskMeAnythingQuestion)->execute($request);

        $this->assertInstanceOf(
            AskMeAnythingQuestion::class,
            $question
        );

        $this->assertDatabaseHas('ask_me_anything_questions', [
            'id' => $question->id,
            'question' => 'this is a question',
            'anonymous' => false,
        ]);
    }
}
