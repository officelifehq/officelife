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
use App\Services\Company\Adminland\Employee\LockEmployee;
use App\Jobs\CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges;

class LockEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_locks_an_employee_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_locks_an_employee_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_locks_an_employee_and_remove_accountant_role(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $dwight->can_manage_expenses = true;
        $dwight->save();

        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new LockEmployee)->execute($request);
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
        (new LockEmployee)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        (new LockEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'locked' => true,
        ]);

        if ($dwight->can_manage_expenses) {
            Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
                return $job->auditLog['action'] === 'employee_disallowed_to_manage_expenses' &&
                $job->auditLog['author_id'] === $michael->id &&
                    $job->auditLog['objects'] === json_encode([
                        'employee_id' => $dwight->id,
                        'employee_name' => $dwight->name,
                    ]);
            });
        }

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_locked' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_locked' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([]);
        });

        Queue::assertPushed(CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges::class);
    }
}
