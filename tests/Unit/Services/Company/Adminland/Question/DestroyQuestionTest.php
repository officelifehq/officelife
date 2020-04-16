<?php

namespace Tests\Unit\Services\Company\Adminland\Position;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Question\DestroyQuestion;

class DestroyQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_position_as_administrator(): void
    {
        $this->executeService(config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_destroys_a_position_as_hr(): void
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
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyQuestion)->execute($request);
    }

    /** @test */
    public function it_fails_if_position_is_not_linked_to_company(): void
    {
        $question = factory(Question::class)->create([]);
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $question->company_id,
            'author_id' => $michael->id,
            'question_id' => $question->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new DestroyQuestion)->execute($request);
    }

    private function executeService(int $permissionLevel): void
    {
        Queue::fake();

        $question = factory(Question::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $question->company_id,
            'permission_level' => $permissionLevel,
        ]);

        $request = [
            'company_id' => $question->company_id,
            'author_id' => $michael->id,
            'question_id' => $question->id,
        ];

        (new DestroyQuestion)->execute($request);

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $question) {
            return $job->auditLog['action'] === 'question_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'question_title' => $question->title,
                ]);
        });
    }
}
