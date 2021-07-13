<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Employee;
use App\Models\Company\RateYourManagerAnswer;

class EmployeePerformanceViewHelper
{
    /**
     * Get the latest Rate your manager surveys about this manager.
     *
     * @param Employee $employee
     * @return array|null
     */
    public static function latestRateYourManagerSurveys(Employee $employee): ?array
    {
        $allSurveys = $employee->rateYourManagerSurveys()
            ->with('answers')
            ->latest()
            ->get();

        $surveysToDisplay = $allSurveys->take(3);

        if ($allSurveys->count() == 0) {
            return null;
        }

        $surveysCollection = collect([]);

        // if the first survey is not active, that means we need to indicate to
        // the manager the date of the next survey
        $survey = $surveysToDisplay->first();
        if (! $survey->active) {
            $now = Carbon::now();
            $surveysCollection->push([
                'id' => null,
                'month' => $now->format('M Y'),
                'deadline' => DateHelper::hoursOrDaysLeft($now->copy()->endOfMonth()),
            ]);
        }

        foreach ($surveysToDisplay as $survey) {
            $totalNumberOfPotentialResponders = $survey->answers->count();

            // counting results about answers, if available
            $results = [];

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

        return [
            'number_of_surveys' => $allSurveys->count(),
            'url_view_all' => route('employees.show.performance.survey.index', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
            'surveys' => $surveysCollection,
        ];
    }
}
