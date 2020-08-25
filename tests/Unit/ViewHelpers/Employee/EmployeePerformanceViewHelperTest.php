<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeePerformanceViewHelper;

class EmployeePerformanceViewHelperTest extends TestCase
{
    use DatabaseTransactions;

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

        $array = EmployeePerformanceViewHelper::latestRateYourManagerSurveys($michael);

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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/'.$activeSurvey->id,
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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/'.$oldSurvey->id,
                ],
            ],
            $array['surveys']->toArray()
        );

        $this->assertEquals(
            2,
            $array['number_of_surveys']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/surveys',
            $array['url_view_all']
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

        $array = EmployeePerformanceViewHelper::latestRateYourManagerSurveys($michael);

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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/'.$inactiveSurvey->id,
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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/'.$oldSurvey->id,
                ],
            ],
            $array['surveys']->toArray()
        );
    }

    /** @test */
    public function it_doesnt_get_any_surveys(): void
    {
        $michael = $this->createAdministrator();

        $this->assertNull(EmployeePerformanceViewHelper::latestRateYourManagerSurveys($michael));
    }
}
