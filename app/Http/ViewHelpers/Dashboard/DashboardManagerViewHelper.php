<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Company\RateYourManagerAnswer;
use Illuminate\Support\Collection as SupportCollection;

class DashboardManagerViewHelper
{
    /**
     * Get all the expenses the manager needs to validate.
     *
     * @var Collection $directReports
     * @return SupportCollection|null
     */
    public static function pendingExpenses(Collection $directReports): ?SupportCollection
    {
        // get the list of employees this manager manages
        $expensesCollection = collect([]);
        foreach ($directReports as $directReport) {
            $employee = $directReport->directReport;
            $employeeExpenses = $employee->expenses;

            $pendingExpenses = $employeeExpenses->filter(function ($expense) {
                return $expense->status == Expense::AWAITING_MANAGER_APPROVAL;
            });

            foreach ($pendingExpenses as $expense) {
                $expensesCollection->push([
                    'id' => $expense->id,
                    'title' => $expense->title,
                    'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                    'status' => $expense->status,
                    'category' => ($expense->category) ? $expense->category->name : null,
                    'expensed_at' => DateHelper::formatDate($expense->expensed_at),
                    'converted_amount' => $expense->converted_amount ?
                        MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                        null,
                    'url' => route('dashboard.manager.expense.show', [
                        'company' => $expense->company,
                        'expense' => $expense,
                    ]),
                    'employee' => ($expense->employee) ? [
                        'id' => $expense->employee->id,
                        'name' => $expense->employee->name,
                        'avatar' => $expense->employee->avatar,
                    ] : [
                        'employee_name' => $expense->employee_name,
                    ],
                ]);
            }
        }

        return $expensesCollection;
    }

    /**
     * Array containing information about the given expense.
     *
     * @param Expense $expense
     * @return Collection|null
     */
    public static function expense(Expense $expense): array
    {
        $expense = [
            'id' => $expense->id,
            'title' => $expense->title,
            'created_at' => DateHelper::formatDate($expense->created_at),
            'amount' => MoneyHelper::format($expense->amount, $expense->currency),
            'status' => $expense->status,
            'category' => ($expense->category) ? $expense->category->name : null,
            'expensed_at' => DateHelper::formatDate($expense->expensed_at),
            'converted_amount' => $expense->converted_amount ?
                MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                null,
            'converted_at' => $expense->converted_at ?
                DateHelper::formatShortDateWithTime($expense->converted_at) :
                null,
            'exchange_rate' => $expense->exchange_rate,
            'employee' => ($expense->employee) ? [
                'id' => $expense->employee->id,
                'name' => $expense->employee->name,
                'avatar' => $expense->employee->avatar,
                'position' => $expense->employee->position ? $expense->employee->position->title : null,
                'status' => $expense->employee->status ? $expense->employee->status->name : null,
            ] : [
                'employee_name' => $expense->employee_name,
            ],
        ];

        return $expense;
    }

    /**
     * Get the latest Rate your manager surveys about this manager.
     *
     * @var Employee $employee
     * @return SupportCollection|null
     */
    public static function latestRateYourManagerSurveys(Employee $employee): ?SupportCollection
    {
        $surveys = $employee->rateYourManagerSurveys()
            ->with('answers')
            ->latest()
            ->get()
            ->take(2);

        $surveysCollection = collect([]);

        // if the first survey is not active, that means we need to add a survey
        // in the future to indicate that the next survey will be in X days
        $survey = $surveys->first();
        if (! $survey->active) {
            $surveysCollection->push([
                'id' => null,
                'month' => Carbon::now()->format('M Y'),
                'deadline' => DateHelper::hoursOrDaysLeft(Carbon::now()->endOfMonth()),
            ]);
        }

        foreach ($surveys as $survey) {
            $totalNumberOfPotentialResponders = $survey->answers->count();
            $numberOfAnswers = 0;

            // counting results about answers, if available
            $results = [];
            if ($survey->answers) {
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
            }

            $surveysCollection->push([
                'id' => $survey->id,
                'active' => $survey->active,
                'month' => $survey->created_at->format('M Y'),
                'deadline' => DateHelper::hoursOrDaysLeft($survey->valid_until_at),
                'results' => $results,
                'employees' => $totalNumberOfPotentialResponders,
                'response_rate' => $numberOfAnswers != 0 ? round($numberOfAnswers * 100 / $totalNumberOfPotentialResponders) : 0,
            ]);
        }

        return $surveysCollection;
    }
}
