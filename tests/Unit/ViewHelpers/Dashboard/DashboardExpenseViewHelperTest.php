<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\AvatarHelper;
use App\Models\Company\Expense;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\DashboardExpenseViewHelper;

class DashboardExpenseViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_a_collection_of_expenses_that_are_awaiting_for_accounting_approval(): void
    {
        $michael = $this->createAdministrator();

        $expense = Expense::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'status' => Expense::AWAITING_ACCOUTING_APPROVAL,
            'converted_amount' => 123,
            'converted_to_currency' => 'EUR',
        ]);

        Expense::factory()->create([
            'employee_id' => $michael->id,
            'status' => Expense::CREATED,
        ]);

        $collection = DashboardExpenseViewHelper::waitingForAccountingApproval($michael->company);

        $this->assertEquals(1, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $expense->id,
                    'title' => $expense->title,
                    'amount' => '$1.00',
                    'converted_amount' => '€1.23',
                    'status' => 'accounting_approval',
                    'category' => $expense->category->name,
                    'expensed_at' => 'Jan 01, 1999',
                    'manager' => null,
                    'employee' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => AvatarHelper::getImage($michael),
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/expenses/'.$expense->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_expenses_that_are_awaiting_for_manager_approval(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $expenseWithManager = Expense::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'manager_approver_id' => $dwight->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
            'converted_amount' => 123,
            'converted_to_currency' => 'EUR',
        ]);

        sleep(1);

        $expenseWithoutManager = Expense::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
            'converted_amount' => 123,
            'converted_to_currency' => 'EUR',
        ]);

        Expense::factory()->create([
            'employee_id' => $michael->id,
            'status' => Expense::CREATED,
        ]);

        $collection = DashboardExpenseViewHelper::waitingForManagerApproval($michael->company);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $expenseWithoutManager->id,
                    'title' => $expenseWithoutManager->title,
                    'amount' => '$1.00',
                    'converted_amount' => '€1.23',
                    'status' => 'manager_approval',
                    'category' => $expenseWithoutManager->category->name,
                    'expensed_at' => 'Jan 01, 1999',
                    'managers' => null,
                    'employee' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => AvatarHelper::getImage($michael),
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/expenses/'.$expenseWithoutManager->id,
                ],
                1 => [
                    'id' => $expenseWithManager->id,
                    'title' => $expenseWithManager->title,
                    'amount' => '$1.00',
                    'converted_amount' => '€1.23',
                    'status' => 'manager_approval',
                    'category' => $expenseWithManager->category->name,
                    'expensed_at' => 'Jan 01, 1999',
                    'managers' => null,
                    'employee' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => AvatarHelper::getImage($michael),
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/expenses/'.$expenseWithManager->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_complete_details_of_an_expense(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $expense = Expense::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'manager_approver_id' => $dwight->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
            'converted_amount' => 123,
            'converted_at' => Carbon::now(),
            'converted_to_currency' => 'EUR',
        ]);

        $expense = DashboardExpenseViewHelper::expense($expense);

        $this->assertArrayHasKey('id', $expense);
        $this->assertArrayHasKey('title', $expense);
        $this->assertArrayHasKey('created_at', $expense);
        $this->assertArrayHasKey('amount', $expense);
        $this->assertArrayHasKey('status', $expense);
        $this->assertArrayHasKey('category', $expense);
        $this->assertArrayHasKey('expensed_at', $expense);
        $this->assertArrayHasKey('converted_amount', $expense);
        $this->assertArrayHasKey('converted_at', $expense);
        $this->assertArrayHasKey('exchange_rate', $expense);
        $this->assertArrayHasKey('exchange_rate_explanation', $expense);
        $this->assertArrayHasKey('manager', $expense);
        $this->assertArrayHasKey('manager_approver_approved_at', $expense);
        $this->assertArrayHasKey('manager_rejection_explanation', $expense);
        $this->assertArrayHasKey('employee', $expense);
    }
}
