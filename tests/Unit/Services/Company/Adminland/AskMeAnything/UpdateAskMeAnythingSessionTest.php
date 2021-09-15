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
use App\Services\Company\Adminland\AskMeAnything\UpdateAskMeAnythingSession;

class UpdateAskMeAnythingSessionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_session_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $ama);
    }

    /** @test */
    public function it_updates_a_session_as_hr(): void
    {
        $michael = $this->createHR();
        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $ama);
    }

    /** @test */
    public function it_cant_update_a_session_as_normal_user(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

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
        (new UpdateAskMeAnythingSession)->execute($request);
    }

    private function executeService(Employee $michael, AskMeAnythingSession $ama): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'ask_me_anything_session_id' => $ama->id,
            'theme' => 'theme',
            'date' => '1921-12-12',
        ];

        $ama = (new UpdateAskMeAnythingSession)->execute($request);

        $this->assertInstanceOf(
            AskMeAnythingSession::class,
            $ama
        );

        $this->assertDatabaseHas('ask_me_anything_sessions', [
            'id' => $ama->id,
            'theme' => 'theme',
            'happened_at' => '1921-12-12 00:00:00',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $ama) {
            return $job->auditLog['action'] === 'ask_me_anything_session_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'ask_me_anything_session_id' => $ama->id,
                ]);
        });
    }
}
