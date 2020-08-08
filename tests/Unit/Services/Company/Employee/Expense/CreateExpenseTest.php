<?php

namespace Tests\Unit\Services\Company\Employee\Expense;

use Tests\TestCase;
use App\Helpers\MoneyHelper;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ExpenseCategory;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Expense\CreateExpense;
use App\Services\Company\Employee\Manager\AssignManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Jobs\ConvertAmountFromOneCurrencyToCompanyCurrency;

class CreateExpenseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_expense_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_an_expense_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_an_expense_as_normal_user_for_himself(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($dwight, $dwight);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_for_someone_else(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($dwight, $michael);
    }

    /** @test */
    public function it_creates_an_expense_and_assigns_it_to_the_employee_managers(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $managerA = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'first_name' => 'toto',
        ]);
        $managerB = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'first_name' => 'titi',
        ]);
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $managerA->id,
        ]);
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $managerB->id,
        ]);
        $this->executeService($michael, $dwight);

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($dwight) {
            return $job->notification['action'] === 'expense_assigned_for_validation' &&
                $job->notification['objects'] === json_encode([
                    'name' => $dwight->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_employee_doesnt_belong_to_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($dwight, $michael);
    }

    /** @test */
    public function it_fails_if_expense_category_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee($michael);
        $category = factory(ExpenseCategory::class)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $category);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateExpense)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight, ExpenseCategory $category = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'expense_category_id' => is_null($category) ? null : $category->id,
            'title' => 'Restaurant',
            'amount' => '100',
            'currency' => 'USD',
            'expensed_at' => '1999-01-01',
        ];

        $expense = (new CreateExpense)->execute($request);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'company_id' => $dwight->company->id,
            'employee_id' => $dwight->id,
            'employee_name' => $dwight->name,
            'title' => 'Restaurant',
            'amount' => '100',
            'currency' => 'USD',
            'expensed_at' => '1999-01-01 00:00:00',
        ]);

        $this->assertInstanceOf(
            Expense::class,
            $expense
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight, $expense) {
            return $job->auditLog['action'] === 'expense_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'expense_id' => $expense->id,
                    'expense_title' => $expense->title,
                    'expense_amount' => MoneyHelper::format($expense->amount, $expense->currency),
                    'expensed_at' => $expense->expensed_at,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $expense) {
            return $job->auditLog['action'] === 'expense_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'expense_id' => $expense->id,
                    'expense_title' => $expense->title,
                    'expense_amount' => MoneyHelper::format($expense->amount, $expense->currency),
                    'expensed_at' => $expense->expensed_at,
                ]);
        });

        Queue::assertPushed(ConvertAmountFromOneCurrencyToCompanyCurrency::class);
    }
}
