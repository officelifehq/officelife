<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Task;
use App\Models\Company\Answer;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\ExpenseCategory;
use App\Jobs\StartRateYourManagerProcess;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Dashboard\DashboardMeViewHelper;

class DashboardMeViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_an_empty_array_if_there_is_no_active_question_in_the_company(): void
    {
        $michael = $this->createAdministrator();

        $this->assertNull(
            DashboardMeViewHelper::question($michael)
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_current_active_question(): void
    {
        $michael = $this->createAdministrator();
        $question = factory(Question::class)->create([
            'company_id' => $michael->company_id,
            'title' => 'Do you like Dwight',
            'active' => true,
        ]);

        factory(Answer::class, 2)->create([
            'question_id' => $question->id,
        ]);

        $response = DashboardMeViewHelper::question($michael);

        $this->assertArraySubset(
            [
                'title' => 'Do you like Dwight',
                'number_of_answers' => 2,
                'answer_by_employee' => null,
            ],
            $response
        );

        $this->assertArraySubset(
            [
                'title' => 'Do you like Dwight',
                'number_of_answers' => 2,
                'employee_has_answered' => false,
                'answer_by_employee' => null,
            ],
            $response
        );

        factory(Answer::class)->create([
            'employee_id' => $michael->id,
            'question_id' => $question->id,
        ]);

        $response = DashboardMeViewHelper::question($michael);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('title', $response);
        $this->assertArrayHasKey('employee_has_answered', $response);
        $this->assertArrayHasKey('number_of_answers', $response);
        $this->assertArrayHasKey('answers', $response);
        $this->assertArrayHasKey('answer_by_employee', $response);
        $this->assertArrayHasKey('url', $response);

        $this->assertArraySubset(
            [
                'title' => 'Do you like Dwight',
                'number_of_answers' => 3,
                'employee_has_answered' => true,
                'url' => env('APP_URL').'/'.$michael->company_id.'/company/questions/'.$question->id,
            ],
            DashboardMeViewHelper::question($michael)
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_inprogress_tasks_of_the_employee(): void
    {
        $michael = $this->createAdministrator();
        $taskA = factory(Task::class)->create([
            'employee_id' => $michael->id,
            'completed' => false,
        ]);
        $taskB = factory(Task::class)->create([
            'employee_id' => $michael->id,
            'completed' => false,
        ]);

        $response = DashboardMeViewHelper::tasks($michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $taskA->id,
                    'title' => $taskA->title,
                ],
                1 => [
                    'id' => $taskB->id,
                    'title' => $taskB->title,
                ],
            ],
            $response->toArray()
        );
    }

    /** @test */
    public function it_gets_all_the_expense_categories_in_the_given_company(): void
    {
        $michael = $this->createAdministrator();
        factory(ExpenseCategory::class, 2)->create([
            'company_id' => $michael->company_id,
        ]);

        $response = DashboardMeViewHelper::categories($michael->company);

        $this->assertCount(2, $response);
        $this->assertArrayHasKey('id', $response->toArray()[0]);
        $this->assertArrayHasKey('name', $response->toArray()[0]);
    }

    /** @test */
    public function it_gets_a_collection_of_currencies(): void
    {
        $response = DashboardMeViewHelper::currencies();

        $this->assertEquals(
            179,
            $response->count()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_recent_expenses(): void
    {
        $michael = $this->createAdministrator();

        $expense = factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'status' => Expense::AWAITING_ACCOUTING_APPROVAL,
            'converted_amount' => 123,
            'converted_to_currency' => 'EUR',
        ]);

        factory(Expense::class)->create([
            'employee_id' => $michael->id,
            'status' => Expense::CREATED,
        ]);

        $collection = DashboardMeViewHelper::expenses($michael);

        $this->assertEquals(1, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $expense->id,
                    'title' => 'Restaurant',
                    'amount' => '$1.00',
                    'converted_amount' => '€1.23',
                    'status' => 'accounting_approval',
                    'category' => 'travel',
                    'expensed_at' => 'Jan 01, 1999',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/expenses/'.$expense->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_answers_the_employee_should_reply_to_about_how_they_feel_about_their_manager(): void
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
            'employee_id' => $dwight->id,
            'manager_id' => $jim->id,
        ]);

        // start two surveys
        StartRateYourManagerProcess::dispatch();

        $collection = DashboardMeViewHelper::rateYourManagerAnswers($dwight);

        $this->assertEquals(2, $collection->count());

        $this->assertArrayHasKey('id', $collection->toArray()[0]);
        $this->assertArrayHasKey('manager_name', $collection->toArray()[0]);
        $this->assertArrayHasKey('deadline', $collection->toArray()[0]);
    }

    /** @test */
    public function it_gets_a_collection_of_managers(): void
    {
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
            'employee_id' => $jim->id,
            'manager_id' => $dwight->id,
        ]);
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $jim->id,
            'manager_id' => $michael->id,
        ]);

        $collection = DashboardMeViewHelper::oneOnOnes($jim);

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
                        'url' => env('APP_URL').'/'.$dwight->company_id.'/dashboard/oneonones/'.OneOnOneEntry::first()->id,
                    ],
                ],
                1 => [
                    'id' => $michael->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => $michael->avatar,
                    'position' => $michael->position->title,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    'entry' => [
                        'id' => OneOnOneEntry::orderBy('id', 'desc')->first()->id,
                        'url' => env('APP_URL').'/'.$dwight->company_id.'/dashboard/oneonones/'.OneOnOneEntry::orderBy('id', 'desc')->first()->id,
                    ],
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_an_array_containing_information_about_upcoming_contract_renewals_for_external_employees(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        // employee is internal, it should return a blank array
        $array = DashboardMeViewHelper::contractRenewal($michael);
        $this->assertNull($array);

        $status = EmployeeStatus::factory()->create([
            'company_id' => $michael->id,
            'type' => EmployeeStatus::INTERNAL,
        ]);
        $michael->employee_status_id = $status->id;
        $michael->save();
        $array = DashboardMeViewHelper::contractRenewal($michael);
        $this->assertNull($array);

        // employee is external, but the contract renewal date is not set
        $status = EmployeeStatus::factory()->create([
            'company_id' => $michael->id,
            'type' => EmployeeStatus::EXTERNAL,
        ]);
        $michael->employee_status_id = $status->id;
        $michael->save();
        $michael->refresh();

        $array = DashboardMeViewHelper::contractRenewal($michael);
        $this->assertNull($array);

        // // employee is external and contract date is set, but in the far future
        $michael->contract_renewed_at = Carbon::now()->addMonths(4);
        $michael->save();
        $michael->refresh();
        $array = DashboardMeViewHelper::contractRenewal($michael);
        $this->assertNull($array);

        // employee is external and contract date is set in less than 3 months
        $michael->contract_renewed_at = Carbon::now()->addMonths(1);
        $michael->save();
        $michael->refresh();

        $array = DashboardMeViewHelper::contractRenewal($michael);
        $this->assertEquals(
            [
                'contract_renewed_at' => 'Feb 01, 2018',
                'number_of_days' => 31,
            ],
            $array
        );
    }
}
