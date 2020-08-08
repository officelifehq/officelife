<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use Illuminate\Support\Collection;

class DashboardExpenseViewHelper
{
    /**
     * Array containing all the expenses that are waiting for accounting
     * approval in the company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function waitingForAccountingApproval(Company $company): ?Collection
    {
        $expenses = $company->expenses()
            ->with('category')
            ->with('employee')
            ->where('status', Expense::AWAITING_ACCOUTING_APPROVAL)
            ->latest()
            ->get();

        $expensesCollection = collect([]);
        foreach ($expenses as $expense) {
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
                'manager' => ($expense->managerApprover) ? [
                    'id' => $expense->managerApprover->id,
                    'name' => $expense->managerApprover->name,
                    'avatar' => $expense->managerApprover->avatar,
                ] : $expense->manager_approver_name,
                'employee' => ($expense->employee) ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => $expense->employee->avatar,
                ] : null,
                'url' => route('dashboard.expense.show', [
                    'company' => $company,
                    'expense' => $expense->id,
                ]),
            ]);
        }

        return $expensesCollection;
    }

    /**
     * Array containing all the expenses that are waiting for manager
     * approval in the company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function waitingForManagerApproval(Company $company): ?Collection
    {
        $expenses = $company->expenses()
            ->with('category')
            ->with('employee')
            ->with('managerApprover')
            ->where('status', Expense::AWAITING_MANAGER_APPROVAL)
            ->latest()
            ->get();

        $expensesCollection = collect([]);
        foreach ($expenses as $expense) {
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
                'manager' => ($expense->managerApprover) ? [
                    'id' => $expense->managerApprover->id,
                    'name' => $expense->managerApprover->name,
                    'avatar' => $expense->managerApprover->avatar,
                ] : $expense->manager_approver_name,
                'employee' => ($expense->employee) ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => $expense->employee->avatar,
                ] : null,
                'url' => route('dashboard.expense.show', [
                    'company' => $company,
                    'expense' => $expense->id,
                ]),
            ]);
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
            'manager' => ($expense->managerApprover) ? [
                'id' => $expense->managerApprover->id,
                'name' => $expense->managerApprover->name,
                'avatar' => $expense->managerApprover->avatar,
            ] : $expense->manager_approver_name,
            'manager_approver_approved_at' => ($expense->manager_approver_approved_at) ?
                DateHelper::formatShortDateWithTime($expense->manager_approver_approved_at) :
                null,
            'manager_rejection_explanation' => $expense->manager_rejection_explanation,
            'employee' => ($expense->employee) ? [
                'id' => $expense->employee->id,
                'name' => $expense->employee->name,
                'avatar' => $expense->employee->avatar,
            ] : null,
        ];

        return $expense;
    }
}
