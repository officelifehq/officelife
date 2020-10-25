<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\Company\Expense;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Jobs\CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges;

class CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChangesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_moves_all_expenses_for_employees_with_no_managers(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $jim = $this->createAnotherEmployee($michael);

        // we'll create 3 expenses:
        // 1 waiting for manager approval for an expense logged by an employee without a manager
        // 1 waiting for manager approval for an expense logged by an employee with a manager
        // 1 waiting for accounting approval
        factory(Expense::class)->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
        ]);
        factory(Expense::class)->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'status' => Expense::AWAITING_ACCOUTING_APPROVAL,
        ]);
        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $jim->id,
            'manager_id' => $dwight->id,
        ];
        (new AssignManager)->execute($request);
        factory(Expense::class)->create([
            'company_id' => $michael->company_id,
            'employee_id' => $jim->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
        ]);

        CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges::dispatch($michael->company);

        $this->assertEquals(
            1,
            $michael->company->expenses()->where('status', Expense::AWAITING_MANAGER_APPROVAL)->count()
        );

        $this->assertEquals(
            2,
            $michael->company->expenses()->where('status', Expense::AWAITING_ACCOUTING_APPROVAL)->count()
        );
    }
}
