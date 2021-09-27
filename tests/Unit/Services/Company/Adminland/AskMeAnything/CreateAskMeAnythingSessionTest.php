<?php

namespace Tests\Unit\Services\Company\Adminland\AskMeAnything;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\AskMeAnythingSession;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingSession;

class CreateAskMeAnythingSessionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_session_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_session_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function it_cant_create_a_session_as_normal_user(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateAskMeAnythingSession)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'theme' => 'theme',
            'date' => '1999-02-02',
        ];

        $ama = (new CreateAskMeAnythingSession)->execute($request);

        $this->assertInstanceOf(
            AskMeAnythingSession::class,
            $ama
        );

        $this->assertDatabaseHas('ask_me_anything_sessions', [
            'id' => $ama->id,
            'active' => false,
            'theme' => 'theme',
            'happened_at' => '1999-02-02 00:00:00',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $ama) {
            return $job->auditLog['action'] === 'ask_me_anything_session_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'ask_me_anything_session_id' => $ama->id,
                ]);
        });
    }
}
