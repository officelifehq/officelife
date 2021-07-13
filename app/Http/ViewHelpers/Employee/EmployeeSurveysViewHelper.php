<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;

class EmployeeSurveysViewHelper
{
    /**
     * Get all the Rate your manager surveys about this manager.
     *
     * @param Employee $employee
     * @return array|null
     */
    public static function rateYourManagerSurveys(Employee $employee): ?array
    {
        $allSurveys = $employee->rateYourManagerSurveys()
            ->with('answers')
            ->latest()
            ->get();

        if ($allSurveys->count() == 0) {
            return null;
        }

        $surveysCollection = collect([]);

        // if the first survey is not active, that means we need to indicate to
        // the manager the date of the next survey
        $survey = $allSurveys->first();
        if (! $survey->active) {
            $now = Carbon::now();
            $surveysCollection->push([
                'id' => null,
                'month' => $now->format('M Y'),
                'deadline' => DateHelper::hoursOrDaysLeft($now->copy()->endOfMonth()),
            ]);
        }

        foreach ($allSurveys as $survey) {
            $totalNumberOfPotentialResponders = $survey->answers->count();

            // counting results about answers
            $bad = $survey->answers->filter(function ($answer) {
                return $answer->rating == RateYourManagerAnswer::BAD;
            });
            $average = $survey->answers->filter(function ($answer) {
                return $answer->rating == RateYourManagerAnswer::AVERAGE;
            });
            $good = $survey->answers->filter(function ($answer) {
                return $answer->rating == RateYourManagerAnswer::GOOD;
            });
            $results = [
                'bad' => $bad->count(),
                'average' => $average->count(),
                'good' => $good->count(),
            ];

            $numberOfAnswers = $bad->count() + $average->count() + $good->count();

            $surveysCollection->push([
                'id' => $survey->id,
                'active' => $survey->active,
                'month' => $survey->created_at->format('M Y'),
                'deadline' => DateHelper::hoursOrDaysLeft($survey->valid_until_at),
                'results' => $results,
                'employees' => $totalNumberOfPotentialResponders,
                'response_rate' => $numberOfAnswers != 0 ? round($numberOfAnswers * 100 / $totalNumberOfPotentialResponders) : 0,
                'url' => route('employees.show.performance.survey.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                    'survey' => $survey,
                ]),
            ]);
        }

        // calculating the number of unique participants
        $uniqueParticipants = DB::table('rate_your_manager_answers', 'ra')
            ->join('rate_your_manager_surveys', 'ra.rate_your_manager_survey_id', '=', 'rate_your_manager_surveys.id')
            ->where('rate_your_manager_surveys.manager_id', $employee->id)
            ->distinct()
            ->count();

        // the average response rate
        // to calculate this, we need to remove any active or future survey
        $surveyAnswered = $surveysCollection->where('active', false);
        $averageResponseRate = round($surveyAnswered->sum('response_rate') / $surveyAnswered->count());

        return [
            'number_of_completed_surveys' => $surveyAnswered->count(),
            'number_of_unique_participants' => $uniqueParticipants,
            'average_response_rate' => $averageResponseRate,
            'surveys' => $surveysCollection,
        ];
    }

    /**
     * Get information about the given survey.
     *
     * @param int $surveyId
     * @return array|null
     */
    public static function informationAboutSurvey(int $surveyId): ?array
    {
        $survey = RateYourManagerSurvey::with('answers')
            ->with('answers.employee')
            ->findOrFail($surveyId);

        // counting results about answers
        $bad = $survey->answers->filter(function ($answer) {
            return $answer->rating == RateYourManagerAnswer::BAD;
        });
        $average = $survey->answers->filter(function ($answer) {
            return $answer->rating == RateYourManagerAnswer::AVERAGE;
        });
        $good = $survey->answers->filter(function ($answer) {
            return $answer->rating == RateYourManagerAnswer::GOOD;
        });
        $results = [
            'bad' => $bad->count(),
            'average' => $average->count(),
            'good' => $good->count(),
        ];

        // employees
        $directReportsCollection = collect([]);
        foreach ($survey->answers as $answer) {
            $directReportsCollection->push([
                'id' => $answer->employee->id,
                'name' => $answer->employee->name,
                'avatar' => ImageHelper::getAvatar($answer->employee, 22),
                'url' => route('employees.show', [
                    'company' => $answer->employee->company_id,
                    'employee' => $answer->employee->id,
                ]),
            ]);
        }

        // answers
        $answersCollection = collect([]);
        $answers = $survey->answers->filter(function ($answer) {
            return $answer->comment;
        });
        foreach ($answers as $answer) {
            $answersCollection->push([
                'id' => $answer->id,
                'comment' => $answer->comment,
                'reveal_identity_to_manager' => $answer->reveal_identity_to_manager,
                'employee' => $answer->reveal_identity_to_manager ? [
                    'id' => $answer->employee->id,
                    'name' => $answer->employee->name,
                    'avatar' => ImageHelper::getAvatar($answer->employee, 22),
                    'url' => route('employees.show', [
                        'company' => $answer->employee->company_id,
                        'employee' => $answer->employee->id,
                    ]),
                ] : null,
            ]);
        }

        return [
            'results' => $results,
            'direct_reports' => $directReportsCollection,
            'answers' => $answersCollection,
            'survey' => [
                'id' => $survey->id,
                'month' => $survey->created_at->format('M Y'),
            ],
        ];
    }
}
