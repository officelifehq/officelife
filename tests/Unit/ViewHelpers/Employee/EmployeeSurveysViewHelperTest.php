<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeSurveysViewHelper;

class EmployeeSurveysViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_all_surveys_about_the_manager(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        // we need one active survey without any results yet and one old survey with results
        $activeSurvey = RateYourManagerSurvey::factory()->create([
            'manager_id' => $michael->id,
            'active' => true,
            'valid_until_at' => Carbon::now()->addDays(),
        ]);

        $oldSurvey = RateYourManagerSurvey::factory()->create([
            'manager_id' => $michael->id,
            'active' => false,
            'valid_until_at' => Carbon::now()->subDays(10),
        ]);
        RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::BAD,
        ]);
        RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::AVERAGE,
        ]);
        RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::GOOD,
        ]);

        $array = EmployeeSurveysViewHelper::rateYourManagerSurveys($michael);

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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/surveys/'.$activeSurvey->id,
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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/surveys/'.$oldSurvey->id,
                ],
            ],
            $array['surveys']->toArray()
        );

        $this->assertEquals(
            1,
            $array['number_of_completed_surveys']
        );

        $this->assertEquals(
            3,
            $array['number_of_unique_participants']
        );

        $this->assertEquals(
            100,
            $array['average_response_rate']
        );
    }

    /** @test */
    public function it_gets_surveys_about_the_manager_with_the_first_survey_being_in_the_future(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        // we need one old survey without any results yet and one old survey with results
        $inactiveSurvey = RateYourManagerSurvey::factory()->create([
            'manager_id' => $michael->id,
            'active' => false,
            'valid_until_at' => Carbon::now()->subDays(10),
        ]);

        $oldSurvey = RateYourManagerSurvey::factory()->create([
            'manager_id' => $michael->id,
            'active' => false,
            'valid_until_at' => Carbon::now()->subDays(10),
        ]);
        RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::BAD,
        ]);
        RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::AVERAGE,
        ]);
        RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $oldSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::GOOD,
        ]);

        $array = EmployeeSurveysViewHelper::rateYourManagerSurveys($michael);

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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/surveys/'.$inactiveSurvey->id,
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
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/performance/surveys/'.$oldSurvey->id,
                ],
            ],
            $array['surveys']->toArray()
        );
    }

    /** @test */
    public function it_doesnt_get_any_surveys(): void
    {
        $michael = $this->createAdministrator();

        $this->assertNull(EmployeeSurveysViewHelper::rateYourManagerSurveys($michael));
    }

    /** @test */
    public function it_gets_information_about_the_current_survey(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        // we need one active survey without any results yet and one old survey with results
        $activeSurvey = RateYourManagerSurvey::factory()->create([
            'manager_id' => $michael->id,
            'active' => true,
            'valid_until_at' => Carbon::now()->addDays(),
        ]);
        $answer1 = RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $activeSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::BAD,
        ]);
        $answer2 = RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $activeSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::AVERAGE,
            'comment' => 'awesome',
        ]);
        $answer3 = RateYourManagerAnswer::factory()->create([
            'rate_your_manager_survey_id' => $activeSurvey->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::GOOD,
        ]);

        $array = EmployeeSurveysViewHelper::informationAboutSurvey($activeSurvey->id);

        $this->assertEquals(
            [
                0 => [
                    'id' => $answer1->employee->id,
                    'name' => $answer1->employee->name,
                    'avatar' => ImageHelper::getAvatar($answer1->employee, 22),
                    'url' => env('APP_URL').'/'.$answer1->employee->company_id.'/employees/'.$answer1->employee->id,
                ],
                1 => [
                    'id' => $answer2->employee->id,
                    'name' => $answer2->employee->name,
                    'avatar' => ImageHelper::getAvatar($answer2->employee, 22),
                    'url' => env('APP_URL').'/'.$answer2->employee->company_id.'/employees/'.$answer2->employee->id,
                ],
                2 => [
                    'id' => $answer3->employee->id,
                    'name' => $answer3->employee->name,
                    'avatar' => ImageHelper::getAvatar($answer3->employee, 22),
                    'url' => env('APP_URL').'/'.$answer3->employee->company_id.'/employees/'.$answer3->employee->id,
                ],
            ],
            $array['direct_reports']->toArray()
        );

        $this->assertEquals(
            [
                    'bad' => 1,
                    'average' => 1,
                    'good' => 1,
            ],
            $array['results']
        );

        $this->assertEquals(
            [
                'id' => $activeSurvey->id,
                'month' => 'Jan 2018',
            ],
            $array['survey']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $answer1->id,
                    'comment' => $answer1->comment,
                    'reveal_identity_to_manager' => $answer1->reveal_identity_to_manager,
                    'employee' => null,
                ],
                1 => [
                    'id' => $answer2->id,
                    'comment' => $answer2->comment,
                    'reveal_identity_to_manager' => $answer2->reveal_identity_to_manager,
                    'employee' => null,
                ],
                2 => [
                    'id' => $answer3->id,
                    'comment' => $answer3->comment,
                    'reveal_identity_to_manager' => $answer3->reveal_identity_to_manager,
                    'employee' => null,
                ],
            ],
            $array['answers']->toArray()
        );
    }
}
