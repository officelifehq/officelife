<?php

namespace Tests\Unit\Services\Company\Employee\Expense;

use Tests\TestCase;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Expense\AssignExpenseToManager;

class AssignExpenseToManagerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_an_expense_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $manager = $this->createAnotherEmployee($michael);
        $expense = factory(Expense::class)->create([
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $dwight, $manager, $expense);
    }

    /** @test */
    public function it_assigns_an_expense_as_hr(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $manager = $this->createAnotherEmployee($michael);
        $expense = factory(Expense::class)->create([
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $dwight, $manager, $expense);
    }

    /** @test */
    public function it_assigns_an_expense_as_normal_user_for_himself(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $manager = $this->createAnotherEmployee($michael);
        $expense = factory(Expense::class)->create([
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($dwight, $dwight, $manager, $expense);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_for_someone_else(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createEmployee($michael);
        $manager = $this->createAnotherEmployee($michael);
        $expense = factory(Expense::class)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);

        $this->executeService($michael, $dwight, $manager, $expense);
    }

    /** @test */
    public function it_fails_if_expense_doesnt_belong_to_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $manager = $this->createAnotherEmployee($michael);
        $expense = factory(Expense::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $manager, $expense);
    }

    /** @test */
    public function it_fails_if_manager_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $manager = $this->createEmployee();
        $expense = factory(Expense::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $manager, $expense);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new AssignExpenseToManager)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight, Employee $manager, Expense $expense = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'expense_id' => $expense->id,
            'manager_id' => $manager->id,
        ];

        $expense = (new AssignExpenseToManager)->execute($request);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'manager_approver_id' => $manager->id,
            'manager_approver_name' => $manager->name,
        ]);

        $this->assertDatabaseHas('tasks', [
            'employee_id' => $manager->id,
            'title' => 'Approve the expense for '.$dwight->name,
        ]);

        $this->assertInstanceOf(
            Expense::class,
            $expense
        );

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($dwight, $manager) {
            return $job->notification['action'] === 'expense_assigned_for_validation' &&
                $job->notification['employee_id'] === $manager->id &&
                $job->notification['objects'] === json_encode([
                    'name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight, $expense, $manager) {
            return $job->auditLog['action'] === 'expense_assigned_to_manager' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'expense_id' => $expense->id,
                    'expense_title' => $expense->title,
                    'expense_amount' => $expense->amount,
                    'expense_currency' => $expense->currency,
                    'expensed_at' => $expense->expensed_at,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight, $expense) {
            return $job->auditLog['action'] === 'expense_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'expense_id' => $expense->id,
                    'expense_title' => $expense->title,
                    'expense_amount' => $expense->amount,
                    'expense_currency' => $expense->currency,
                    'expensed_at' => $expense->expensed_at,
                ]);
        });
    }
}
