<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Task;
use App\Models\Company\Answer;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use App\Models\Company\ExpenseCategory;
use App\Jobs\StartRateYourManagerProcess;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
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
        $michael = $this->createAdministrator();
        $response = DashboardMeViewHelper::currencies($michael->company);

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
    public function it_gets_the_latest_surveys_about_the_manager(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        // we need one active survey without any results yet and one old survey with results
        $activeSurvey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => true,
            'valid_until_at' => Carbon::now()->addDays(),
        ]);

        $oldSurvey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => false,
            'valid_until_at' => Carbon::now()->subDays(10),
        ]);
        factory(RateYourManagerAnswer::class)->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::BAD,
        ]);
        factory(RateYourManagerAnswer::class)->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::AVERAGE,
        ]);
        factory(RateYourManagerAnswer::class)->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::GOOD,
        ]);

        $collection = DashboardMeViewHelper::latestRateYourManagerSurveys($michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $activeSurvey->id,
                    'active' => true,
                    'month' => 'Jan 2018',
                    'deadline' => '1 day left',
                    'results' => [
                        'bad' => 0,
                        'average' => 0,
                        'good' => 0,
                    ],
                    'employees' => 0,
                    'response_rate' => 0,
                ],
                1 => [
                    'id' => $oldSurvey->id,
                    'active' => false,
                    'month' => 'Jan 2018',
                    'deadline' => '10 days left',
                    'results' => [
                        'bad' => 1,
                        'average' => 1,
                        'good' => 1,
                    ],
                    'employees' => 3,
                    'response_rate' => 100,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_latest_surveys_about_the_manager_with_the_first_survey_being_in_the_future(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        // we need one old survey without any results yet and one old survey with results
        $inactiveSurvey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => false,
            'valid_until_at' => Carbon::now()->subDays(10),
        ]);

        $oldSurvey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => false,
            'valid_until_at' => Carbon::now()->subDays(10),
        ]);
        factory(RateYourManagerAnswer::class)->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::BAD,
        ]);
        factory(RateYourManagerAnswer::class)->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::AVERAGE,
        ]);
        factory(RateYourManagerAnswer::class)->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::GOOD,
        ]);

        $collection = DashboardMeViewHelper::latestRateYourManagerSurveys($michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => null,
                    'month' => 'Jan 2018',
                    'deadline' => '30 days left',
                ],
                1 => [
                    'id' => $inactiveSurvey->id,
                    'active' => false,
                    'month' => 'Jan 2018',
                    'deadline' => '10 days left',
                    'results' => [
                        'bad' => 0,
                        'average' => 0,
                        'good' => 0,
                    ],
                    'employees' => 0,
                    'response_rate' => 0,
                ],
                2 => [
                    'id' => $oldSurvey->id,
                    'active' => false,
                    'month' => 'Jan 2018',
                    'deadline' => '10 days left',
                    'results' => [
                        'bad' => 1,
                        'average' => 1,
                        'good' => 1,
                    ],
                    'employees' => 3,
                    'response_rate' => 100,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_doesnt_get_any_surveys(): void
    {
        $michael = $this->createAdministrator();

        $this->assertNull(DashboardMeViewHelper::latestRateYourManagerSurveys($michael));
    }
}
