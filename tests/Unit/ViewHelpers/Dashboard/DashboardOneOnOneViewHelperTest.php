<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Tests\TestCase;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Dashboard\DashboardManagerViewHelper;

class DashboardOneOnOneViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_an_array_containing_all_the_information_about_a_one_on_one_entry(): void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $dwight->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $expense = factory(Expense::class)->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
            'converted_amount' => 123,
            'converted_to_currency' => 'EUR',
        ]);

        factory(Expense::class)->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'status' => Expense::CREATED,
        ]);

        $collection = DashboardManagerViewHelper::pendingExpenses($michael->directReports);

        $this->assertEquals(1, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $expense->id,
                    'title' => 'Restaurant',
                    'amount' => '$1.00',
                    'converted_amount' => '€1.23',
                    'status' => 'manager_approval',
                    'category' => 'travel',
                    'expensed_at' => 'Jan 01, 1999',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/manager/expenses/'.$expense->id,
                    'employee' => [
                        'id' => $dwight->id,
                        'name' => $dwight->name,
                        'avatar' => $dwight->avatar,
                    ],
                ],
            ],
            $collection->toArray()
        );
    }
}
