<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Dashboard\DashboardManagerViewHelper;

class DashboardManagerViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_a_collection_of_pending_expenses(): void
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

        $collection = DashboardManagerViewHelper::pendingExpenses($michael, $michael->directReports);

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

    /** @test */
    public function it_gets_the_complete_details_of_an_expense(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $expense = factory(Expense::class)->create([
            'company_id' => $michael->company->id,
            'employee_id' => $michael->id,
            'manager_approver_id' => $dwight->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
            'converted_amount' => 123,
            'converted_at' => Carbon::now(),
            'converted_to_currency' => 'EUR',
        ]);

        $expense = DashboardManagerViewHelper::expense($expense);

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
        $this->assertArrayHasKey('employee', $expense);
    }

    /** @test */
    public function it_gets_a_collection_of_one_on_ones_for_direct_reports(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ]);
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $jim->id,
            'manager_id' => $michael->id,
        ]);

        $collection = DashboardManagerViewHelper::oneOnOnes($michael, $michael->directReports);

        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => $dwight->avatar,
                    'position' => $dwight->position->title,
                    'url' => env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
                    'entry' => [
                        'id' => OneOnOneEntry::first()->id,
                        'happened_at' => '2018-01-01',
                        'url' => env('APP_URL').'/'.$dwight->company_id.'/dashboard/oneonones/'.OneOnOneEntry::first()->id,
                    ],
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => $jim->avatar,
                    'position' => $jim->position->title,
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                    'entry' => [
                        'id' => OneOnOneEntry::orderBy('id', 'desc')->first()->id,
                        'happened_at' => '2018-01-01',
                        'url' => env('APP_URL').'/'.$dwight->company_id.'/dashboard/oneonones/'.OneOnOneEntry::orderBy('id', 'desc')->first()->id,
                    ],
                ],
            ],
            $collection->toArray()
        );
    }
}
