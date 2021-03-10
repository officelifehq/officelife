<?php

namespace Tests\Unit\Services\Company\Employee\Expense;

use ErrorException;
use Tests\TestCase;
use App\Helpers\MoneyHelper;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Services\Company\Employee\Expense\AcceptExpenseAsManager;

class AcceptExpenseAsManagerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_accepts_an_expense_as_the_manager(): void
    {
        $manager = $this->createAdministrator();

        $employee = Employee::factory()->create([
            'company_id' => $manager->company_id,
            'first_name' => 'toto',
        ]);

        (new AssignManager)->execute([
            'company_id' => $manager->company_id,
            'author_id' => $manager->id,
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);

        $expense = Expense::factory()->create([
            'company_id' => $manager->company_id,
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
        ]);

        $this->executeService($manager, $employee, $expense);
    }

    /** @test */
    public function it_accepts_an_expense_as_the_manager_even_if_the_employee_of_the_expense_doesnt_exist_anymore(): void
    {
        $manager = $this->createAdministrator();

        $employee = Employee::factory()->create([
            'company_id' => $manager->company_id,
            'first_name' => 'toto',
        ]);

        (new AssignManager)->execute([
            'company_id' => $manager->company_id,
            'author_id' => $manager->id,
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);

        $expense = Expense::factory()->create([
            'company_id' => $manager->company_id,
            'employee_id' => null,
            'employee_name' => $employee->name,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
        ]);

        $this->executeService($manager, $employee, $expense);
    }

    /** @test */
    public function it_fails_if_manager_doesnt_manage_the_employee(): void
    {
        $manager = $this->createAdministrator();

        $employee = Employee::factory()->create([
            'company_id' => $manager->company_id,
            'first_name' => 'toto',
        ]);

        $expense = Expense::factory()->create([
            'company_id' => $manager->company_id,
            'employee_id' => $employee->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($manager, $employee, $expense);
    }

    /** @test */
    public function it_fails_if_expense_is_not_in_the_correct_status(): void
    {
        $manager = $this->createAdministrator();

        $employee = Employee::factory()->create([
            'company_id' => $manager->company_id,
            'first_name' => 'toto',
        ]);

        $expense = Expense::factory()->create([
            'company_id' => $manager->company_id,
            'employee_id' => $employee->id,
            'status' => Expense::CREATED,
        ]);

        $this->expectException(ErrorException::class);
        $this->executeService($manager, $employee, $expense);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new AcceptExpenseAsManager)->execute($request);
    }

    private function executeService(Employee $manager, Employee $employee = null, Expense $expense): void
    {
        Queue::fake();

        $request = [
            'company_id' => $manager->company_id,
            'author_id' => $manager->id,
            'expense_id' => $expense->id,
        ];

        $expense = (new AcceptExpenseAsManager)->execute($request);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => Expense::AWAITING_ACCOUTING_APPROVAL,
            'company_id' => $manager->company_id,
        ]);

        $this->assertInstanceOf(
            Expense::class,
            $expense
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($manager, $expense) {
            return $job->auditLog['action'] === 'expense_accepted_by_manager' &&
                $job->auditLog['author_id'] === $manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $expense->employee_id,
                    'employee_name' => $expense->employee_name,
                    'expense_id' => $expense->id,
                    'expense_title' => $expense->title,
                    'expense_amount' => MoneyHelper::format($expense->amount, $expense->currency),
                    'expensed_at' => $expense->expensed_at,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($manager, $expense) {
            return $job->auditLog['action'] === 'expense_accepted_for_employee' &&
                $job->auditLog['author_id'] === $manager->id &&
                $job->auditLog['employee_id'] === $manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $expense->employee_id,
                    'employee_name' => $expense->employee_name,
                    'expense_id' => $expense->id,
                    'expense_title' => $expense->title,
                    'expense_amount' => MoneyHelper::format($expense->amount, $expense->currency),
                    'expensed_at' => $expense->expensed_at,
                ]);
        });

        if ($expense->employee) {
            Queue::assertPushed(NotifyEmployee::class);
        }

        if ($expense->employee) {
            Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($manager, $employee, $expense) {
                return $job->auditLog['action'] === 'expense_accepted_by_manager' &&
                    $job->auditLog['author_id'] === $manager->id &&
                    $job->auditLog['employee_id'] === $employee->id &&
                    $job->auditLog['objects'] === json_encode([
                        'expense_id' => $expense->id,
                        'expense_title' => $expense->title,
                        'expense_amount' => MoneyHelper::format($expense->amount, $expense->currency),
                        'expensed_at' => $expense->expensed_at,
                    ]);
            });
        }
    }
}
