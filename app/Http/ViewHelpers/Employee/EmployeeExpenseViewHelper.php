<?php

namespace App\Http\ViewHelpers\Employee;

use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class EmployeeExpenseViewHelper
{
    /**
     * Array containing the main statistics about the expenses of this employee.
     *
     * @param Employee $employee
     * @param EloquentCollection $expenses
     * @return array
     */
    public static function stats(Employee $employee, EloquentCollection $expenses): array
    {
        $acceptedExpenses = $expenses->filter(function ($expense) {
            return $expense->status === Expense::ACCEPTED;
        });

        $numberOfAcceptedExpenses = $acceptedExpenses->count();

        $numberOfRejectedExpenses = $expenses->filter(function ($expense) {
            return $expense->status === Expense::REJECTED_BY_MANAGER ||
                $expense->status === Expense::REJECTED_BY_ACCOUNTING;
        })->count();

        $numberOfInProgressExpenses = $expenses->count() - $numberOfRejectedExpenses - $numberOfAcceptedExpenses;

        $reimbursedAmount = $acceptedExpenses->sum(function ($expense) {
            $amount = $expense->converted_amount ? $expense->converted_amount : $expense->amount;

            return $amount;
        });

        return [
            'numberOfAcceptedExpenses' => $numberOfAcceptedExpenses,
            'numberOfRejectedExpenses' => $numberOfRejectedExpenses,
            'numberOfInProgressExpenses' => $numberOfInProgressExpenses,
            'reimbursedAmount' => MoneyHelper::format($reimbursedAmount, $employee->company->currency),
        ];
    }

    public static function list(Employee $employee, $expenses, Employee $loggedEmployee): Collection
    {
        $company = $employee->company;

        $expensesCollection = collect([]);
        foreach ($expenses as $expense) {
            $expensesCollection->push([
                'id' => $expense->id,
                'title' => $expense->title,
                'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                'status' => $expense->status,
                'expensed_at' => DateHelper::formatDate($expense->expensed_at, $loggedEmployee->timezone),
                'converted_amount' => $expense->converted_amount ?
                    MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                    null,
                'url' => route('employee.administration.expenses.show', [
                    'company' => $company,
                    'employee' => $employee,
                    'expense' => $expense,
                ]),
            ]);
        }

        return $expensesCollection;
    }
}
