<?php

namespace Tests\Unit\Services\Company\Employee\Expense;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Expense\DestroyExpense;

class DestroyExpenseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_an_expense_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function it_destroys_an_expense_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function it_destroys_an_expense_as_normal_user_if_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function another_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyExpense)->execute($request);
    }

    /** @test */
    public function it_fails_if_expense_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $expense = Expense::factory()->create([]);

        $request = [
            'company_id' => $expense->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'expense_id' => $expense->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new DestroyExpense)->execute($request);
    }

    private function executeService(Employee $boss, Employee $employee): void
    {
        Queue::fake();

        $expense = Expense::factory()->create([
            'company_id' => $boss->company_id,
        ]);

        $request = [
            'company_id' => $expense->company_id,
            'author_id' => $boss->id,
            'employee_id' => $employee->id,
            'expense_id' => $expense->id,
        ];

        (new DestroyExpense)->execute($request);

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($boss, $employee, $expense) {
            return $job->auditLog['action'] === 'expense_destroyed' &&
                $job->auditLog['author_id'] === $boss->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'expense_title' => $expense->title,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($boss, $expense) {
            return $job->auditLog['action'] === 'expense_destroyed' &&
                $job->auditLog['author_id'] === $boss->id &&
                $job->auditLog['objects'] === json_encode([
                    'expense_title' => $expense->title,
                ]);
        });
    }
}
