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
                ] : ($expense->manager_approver_name == '' ? null : $expense->manager_approver_name),
                'employee' => ($expense->employee) ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => $expense->employee->avatar,
                ] : [
                    'employee_name' => $expense->employee_name,
                ],
                'url' => route('dashboard.expenses.show', [
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
            ->with('employee.managers')
            ->where('status', Expense::AWAITING_MANAGER_APPROVAL)
            ->latest()
            ->get();

        $expensesCollection = collect([]);
        foreach ($expenses as $expense) {
            $managerCollection = collect([]);

            if ($expense->employee) {
                foreach ($expense->employee->managers as $manager) {
                    $managerCollection->push([
                        'id' => $manager->manager->id,
                        'name' => $manager->manager->name,
                        'avatar' => $manager->manager->avatar,
                    ]);
                }
            }

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
                'managers' => $managerCollection->count() == 0 ? null : $managerCollection,
                'employee' => ($expense->employee) ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => $expense->employee->avatar,
                ] : [
                    'employee_name' => $expense->employee_name,
                ],
                'url' => route('dashboard.expenses.show', [
                    'company' => $company,
                    'expense' => $expense->id,
                ]),
            ]);
        }

        return $expensesCollection;
    }

    /**
     * Array containing all the expenses that have been either accepted or
     * rejected.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function acceptedAndRejected(Company $company): ?Collection
    {
        $expenses = $company->expenses()
            ->with('category')
            ->with('employee')
            ->with('employee.managers')
            ->where('status', Expense::ACCEPTED)
            ->orWhere('status', Expense::REJECTED_BY_ACCOUNTING)
            ->orWhere('status', Expense::REJECTED_BY_MANAGER)
            ->orderBy('expenses.updated_at', 'asc')
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
                'employee' => ($expense->employee) ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => $expense->employee->avatar,
                ] : [
                    'employee_name' => $expense->employee_name,
                ],
                'url' => route('dashboard.expenses.summary', [
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
     * @return array
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
            'exchange_rate_explanation' => '1 '.$expense->converted_to_currency.' = '.$expense->exchange_rate.' '.$expense->currency,
            'manager' => ($expense->managerApprover) ? [
                'id' => $expense->managerApprover->id,
                'name' => $expense->managerApprover->name,
                'avatar' => $expense->managerApprover->avatar,
                'position' => $expense->managerApprover->position ? $expense->managerApprover->position->title : null,
                'status' => $expense->managerApprover->status ? $expense->managerApprover->status->name : null,
            ] : [
                'name' => $expense->manager_approver_name,
            ],
            'manager_approver_approved_at' => $expense->manager_approver_approved_at ?
                DateHelper::formatDate($expense->manager_approver_approved_at) :
                null,
            'manager_rejection_explanation' => $expense->manager_rejection_explanation,
            'accountant' => $expense->accountingApprover ? [
                'id' => $expense->accountingApprover->id,
                'name' => $expense->accountingApprover->name,
                'avatar' => $expense->accountingApprover->avatar,
                'position' => $expense->accountingApprover->position ? $expense->accountingApprover->position->title : null,
                'status' => $expense->accountingApprover->status ? $expense->accountingApprover->status->name : null,
            ] : [
                'name' => $expense->accounting_approver_name,
            ],
            'accounting_approver_approved_at' => ($expense->accounting_approver_approved_at) ?
                DateHelper::formatDate($expense->accounting_approver_approved_at) :
                null,
            'accounting_rejection_explanation' => $expense->accounting_rejection_explanation,
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
}
