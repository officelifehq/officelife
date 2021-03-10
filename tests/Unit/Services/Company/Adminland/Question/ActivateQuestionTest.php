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
use App\Services\Company\Adminland\Question\ActivateQuestion;

class ActivateQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_activates_a_question_as_administrator(): void
    {
        $this->executeService(config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_activates_a_question_as_hr(): void
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
        (new ActivateQuestion)->execute($request);
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
        ];

        $this->expectException(ModelNotFoundException::class);
        (new ActivateQuestion)->execute($request);
    }

    private function executeService(int $permissionLevel): void
    {
        Queue::fake();

        $question = Question::factory()->create([
            'active' => false,
        ]);
        Question::factory()->count(3)->create([
            'active' => true,
            'deactivated_at' => '2020-04-15 00:00:00',
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $question->company_id,
            'permission_level' => $permissionLevel,
        ]);

        $request = [
            'company_id' => $question->company_id,
            'author_id' => $michael->id,
            'question_id' => $question->id,
        ];

        (new ActivateQuestion)->execute($request);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'company_id' => $question->company_id,
            'active' => true,
            'deactivated_at' => null,
        ]);

        $this->assertEquals(
            1,
            $michael->company->questions()->where('active', true)->count()
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $question) {
            return $job->auditLog['action'] === 'question_activated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'question_id' => $question->id,
                    'question_title' => $question->title,
                ]);
        });
    }
}
