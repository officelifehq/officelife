<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeExpenseViewHelper;

class EmployeeExpenseViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_an_array_of_statistics_about_the_expenses_of_this_employee(): void
    {
        $michael = factory(Employee::class)->create([]);

        // 2 accepted expenses
        factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'amount' => 100,
            'status' => Expense::ACCEPTED,
        ]);
        factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'amount' => 50,
            'status' => Expense::ACCEPTED,
        ]);

        // 2 awaiting expenses
        factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
        ]);
        factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'status' => Expense::AWAITING_ACCOUTING_APPROVAL,
        ]);

        // 1 rejected expense
        factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'status' => Expense::REJECTED_BY_ACCOUNTING,
        ]);

        $this->assertEquals(
            [
                'numberOfAcceptedExpenses' => 2,
                'numberOfRejectedExpenses' => 1,
                'numberOfInProgressExpenses' => 2,
                'reimbursedAmount' => '$1.50',
            ],
            EmployeeExpenseViewHelper::stats($michael, $michael->expenses)
        );
    }

    /** @test */
    public function it_gets_a_collection_of_expenses(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();

        $expense = factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'created_at' => '2019-01-01 01:00:00',
        ]);

        $collection = EmployeeExpenseViewHelper::list($michael, $michael->expenses);

        $this->assertEquals(
            [
                0 => [
                    'id' => $expense->id,
                    'title' => 'Restaurant',
                    'amount' => '$1.00',
                    'status' => 'created',
                    'expensed_at' => 'Jan 01, 1999',
                    'converted_amount' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/administration/expenses/'.$expense->id,
                ],
            ],
            $collection->toArray()
        );
    }
}
