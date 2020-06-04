<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Employee\UnlockEmployee;

class UnlockEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_unlocks_an_employee_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }
    /** @test */
    public function it_unlocks_an_employee_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UnlockEmployee)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UnlockEmployee)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        (new UnlockEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'locked' => false,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_unlocked' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_unlocked' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([]);
        });
    }
}
