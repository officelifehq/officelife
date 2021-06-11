<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Adminland\Flow\CreateFlow;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_flow_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_flow_as_hr(): void
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
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new CreateFlow)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Selling team',
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'anniversary' => false,
        ];

        $flow = (new CreateFlow)->execute($request);

        $this->assertDatabaseHas('flows', [
            'id' => $flow->id,
            'company_id' => $michael->company_id,
            'name' => 'Selling team',
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'paused' => true,
        ]);

        $this->assertInstanceOf(
            Flow::class,
            $flow
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $flow) {
            return $job->auditLog['action'] === 'flow_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'flow_id' => $flow->id,
                    'flow_name' => $flow->name,
                ]);
        });
    }
}
