<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Expense;
use App\Models\Company\ExpenseCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExpenseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $expense = Expense::factory()->create([]);
        $this->assertTrue($expense->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $expense = Expense::factory()->create([]);
        $this->assertTrue($expense->employee()->exists());
    }

    /** @test */
    public function it_belongs_to_an_expense_category(): void
    {
        $category = ExpenseCategory::factory()->create([]);
        $expense = Expense::factory()->create([
            'expense_category_id' => $category->id,
        ]);
        $this->assertTrue($expense->category()->exists());
    }

    /** @test */
    public function it_has_a_manager_associated_with_the_expense(): void
    {
        $michael = $this->createAdministrator();
        $expense = Expense::factory()->create([
            'manager_approver_id' => $michael->id,
        ]);
        $this->assertTrue($expense->managerApprover()->exists());
    }

    /** @test */
    public function it_has_an_accounting_approver_associated_with_the_expense(): void
    {
        $michael = $this->createAdministrator();
        $expense = Expense::factory()->create([
            'accounting_approver_id' => $michael->id,
        ]);
        $this->assertTrue($expense->accountingApprover()->exists());
    }
}
