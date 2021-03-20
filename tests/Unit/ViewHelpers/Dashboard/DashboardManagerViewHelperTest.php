<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Expense;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\TimeTrackingEntry;
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
        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $expense = Expense::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'status' => Expense::AWAITING_MANAGER_APPROVAL,
            'converted_amount' => 123,
            'converted_to_currency' => 'EUR',
        ]);

        Expense::factory()->create([
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
                    'title' => $expense->title,
                    'amount' => '$1.00',
                    'converted_amount' => '€1.23',
                    'status' => 'manager_approval',
                    'category' => $expense->category->name,
                    'expensed_at' => 'Jan 01, 1999',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/dashboard/manager/expenses/'.$expense->id,
                    'employee' => [
                        'id' => $dwight->id,
                        'name' => $dwight->name,
                        'avatar' => ImageHelper::getAvatar($dwight),
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

        $expense = Expense::factory()->create([
            'company_id' => $michael->company_id,
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

        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = Employee::factory()->create([
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
                    'avatar' => ImageHelper::getAvatar($dwight),
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
                    'avatar' => ImageHelper::getAvatar($jim),
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

    /** @test */
    public function it_gets_a_collection_of_employees_who_have_a_contract_renewed_soon(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $status = EmployeeStatus::factory()->create([
            'company_id' => $michael->company_id,
            'type' => EmployeeStatus::EXTERNAL,
        ]);

        $dwight->employee_status_id = $status->id;
        $dwight->contract_renewed_at = Carbon::now()->addMonths(1);
        $dwight->save();

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

        $collection = DashboardManagerViewHelper::contractRenewals($michael, $michael->directReports);

        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => ImageHelper::getAvatar($dwight),
                    'position' => $dwight->position->title,
                    'url' => env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
                    'contract_information' => [
                        'contract_renewed_at' => 'Feb 01, 2018',
                        'number_of_days' => 31,
                        'late' => false,
                    ],
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_an_array_of_data_about_employees_who_have_timesheets_to_approve(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        // creating two employees and adding timesheets after this
        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = Employee::factory()->create([
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

        // creating one timesheet ready to submit
        $project = Project::factory()->create();
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheetA = Timesheet::factory()->create([
            'employee_id' => $dwight->id,
            'status' => Timesheet::READY_TO_SUBMIT,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheetA->id,
            'project_task_id' => $task->id,
        ]);

        // creating one timesheet but not ready - it shouldn't appear in the
        // collection
        $timesheetB = Timesheet::factory()->create([
            'employee_id' => $dwight->id,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheetB->id,
            'project_task_id' => $task->id,
        ]);

        $array = DashboardManagerViewHelper::employeesWithTimesheetsToApprove($michael, $michael->directReports);

        // make sure there is only one employee
        $this->assertEquals(
            1,
            $array['totalNumberOfTimesheetsToValidate']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$dwight->company_id.'/dashboard/manager/timesheets',
            $array['url_view_all']
        );

        // now analyzing what's returned from the method
        $this->assertEquals(
            $dwight->id,
            $array['employees']->toArray()[0]['id']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
            $array['employees']->toArray()[0]['url']
        );
    }
}
