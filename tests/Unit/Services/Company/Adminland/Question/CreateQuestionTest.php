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
use App\Services\Company\Adminland\Question\CreateQuestion;

class CreateQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_question_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_question_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'What is your favorite food?',
        ];

        $this->expectException(ValidationException::class);
        (new CreateQuestion)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'title' => 'What is your favorite food?',
            'active' => true,
        ];

        $question = (new CreateQuestion)->execute($request);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'company_id' => $michael->company_id,
            'title' => 'What is your favorite food?',
            'active' => true,
        ]);

        $this->assertInstanceOf(
            Question::class,
            $question
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $question) {
            return $job->auditLog['action'] === 'question_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'question_id' => $question->id,
                    'question_title' => $question->title,
                    'question_status' => $question->active,
                ]);
        });
    }
}
