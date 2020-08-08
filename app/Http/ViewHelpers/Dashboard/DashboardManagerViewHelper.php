<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use App\Models\Company\Expense;
use Illuminate\Database\Eloquent\Collection;
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
                    ] : null,
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
            ] : null,
        ];

        return $expense;
    }
}
