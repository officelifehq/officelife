<?php

namespace Tests\Unit\Services\Company\Employee\Answer;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Employee\Answer\CreateAnswer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateAnswerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_answer_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_an_answer_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function own_employee_can_create_the_answer(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function a_normal_user_cant_execute_the_service_for_another_employee(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'This is my answer',
        ];

        $this->expectException(ValidationException::class);
        (new CreateAnswer)->execute($request);
    }

    /** @test */
    public function it_fails_if_question_doesnt_belong_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $this->executeService($michael, $dwight);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $question = Question::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'question_id' => $question->id,
            'employee_id' => $dwight->id,
            'body' => 'This is my answer',
        ];

        $answer = (new CreateAnswer)->execute($request);

        $this->assertDatabaseHas('answers', [
            'id' => $answer->id,
            'question_id' => $question->id,
            'body' => 'This is my answer',
        ]);

        $this->assertInstanceOf(
            Question::class,
            $question
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $question, $answer) {
            return $job->auditLog['action'] === 'answer_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'answer_id' => $answer->id,
                    'question_id' => $question->id,
                    'question_title' => $question->title,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $question, $answer) {
            return $job->auditLog['action'] === 'answer_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'answer_id' => $answer->id,
                    'question_id' => $question->id,
                    'question_title' => $question->title,
                ]);
        });
    }
}
