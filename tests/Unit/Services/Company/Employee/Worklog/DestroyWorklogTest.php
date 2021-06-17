<?php

namespace Tests\Unit\Services\Company\Employee\Worklog;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Worklog\DestroyWorklog;

class DestroyWorklogTest extends TestCase
{
    use DatabaseTransactions;

    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_worklog_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_destroys_a_worklog_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function employee_can_destroy_her_own_worklog(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_against_another_user(): void
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
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyWorklog)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $worklog = Worklog::factory()->create([
            'employee_id' => $dwight->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'worklog_id' => $worklog->id,
        ];

        (new DestroyWorklog)->execute($request);

        $this->assertDatabaseMissing('worklogs', [
            'id' => $worklog->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $worklog) {
            return $job->auditLog['action'] === 'worklog_destroyed' &&
            $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'date' => $worklog->created_at->format('Y-m-d'),
                ]);
        });
    }
}
