<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MoneyHelper;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class DashboardExpenseViewHelper
{
    /**
     * Get all the expenses that are waiting for accounting approval in the
     * company.
     *
     * @param Company $company
     * @param Employee $loggedEmployee
     * @return Collection|null
     */
    public static function waitingForAccountingApproval(Company $company, Employee $loggedEmployee): ?Collection
    {
        $expenses = $company->expenses()
            ->with('category')
            ->with('employee')
            ->where('status', Expense::AWAITING_ACCOUTING_APPROVAL)
            ->latest()
            ->get();

        $expensesCollection = collect([]);
        foreach ($expenses as $expense) {
            $manager = $expense->managerApprover;

            $expensesCollection->push([
                'id' => $expense->id,
                'title' => $expense->title,
                'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                'status' => $expense->status,
                'category' => ($expense->category) ? $expense->category->name : null,
                'expensed_at' => DateHelper::formatDate($expense->expensed_at, $loggedEmployee->timezone),
                'converted_amount' => $expense->converted_amount ?
                    MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                    null,
                'manager' => $manager ? [
                    'id' => $manager->id,
                    'name' => $manager->name,
                    'avatar' => ImageHelper::getAvatar($manager, 18),
                ] : ($expense->manager_approver_name == '' ? null : $expense->manager_approver_name),
                'employee' => $expense->employee ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => ImageHelper::getAvatar($expense->employee, 18),
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
     * Get all the expenses that are waiting for manager
     * approval in the company.
     *
     * @param Company $company
     * @param Employee $loggedEmployee
     * @return Collection|null
     */
    public static function waitingForManagerApproval(Company $company, Employee $loggedEmployee): ?Collection
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
                        'avatar' => ImageHelper::getAvatar($manager->manager, 18),
                    ]);
                }
            }

            $expensesCollection->push([
                'id' => $expense->id,
                'title' => $expense->title,
                'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                'status' => $expense->status,
                'category' => ($expense->category) ? $expense->category->name : null,
                'expensed_at' => DateHelper::formatDate($expense->expensed_at, $loggedEmployee->timezone),
                'converted_amount' => $expense->converted_amount ?
                    MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                    null,
                'managers' => $managerCollection->count() == 0 ? null : $managerCollection,
                'employee' => ($expense->employee) ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => ImageHelper::getAvatar($expense->employee, 18),
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
     * Get all the expenses that have been either accepted or
     * rejected.
     *
     * @param Company $company
     * @param Employee $loggedEmployee
     * @return Collection|null
     */
    public static function acceptedAndRejected(Company $company, Employee $loggedEmployee): ?Collection
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
                'expensed_at' => DateHelper::formatDate($expense->expensed_at, $loggedEmployee->timezone),
                'converted_amount' => $expense->converted_amount ?
                    MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                    null,
                'employee' => ($expense->employee) ? [
                    'id' => $expense->employee->id,
                    'name' => $expense->employee->name,
                    'avatar' => ImageHelper::getAvatar($expense->employee),
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
     * Get all the information about the given expense.
     *
     * @param Expense $expense
     * @param Employee $loggedEmployee
     * @return array
     */
    public static function expense(Expense $expense, Employee $loggedEmployee): array
    {
        $manager = $expense->managerApprover;
        $accountant = $expense->accountingApprover;
        $expenseEmployee = $expense->employee;

        $expense = [
            'id' => $expense->id,
            'title' => $expense->title,
            'created_at' => DateHelper::formatDate($expense->created_at, $loggedEmployee->timezone),
            'amount' => MoneyHelper::format($expense->amount, $expense->currency),
            'status' => $expense->status,
            'category' => ($expense->category) ? $expense->category->name : null,
            'expensed_at' => DateHelper::formatDate($expense->expensed_at, $loggedEmployee->timezone),
            'converted_amount' => $expense->converted_amount ?
                MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                null,
            'converted_at' => $expense->converted_at ?
                DateHelper::formatShortDateWithTime($expense->converted_at, $loggedEmployee->timezone) :
                null,
            'exchange_rate' => $expense->exchange_rate,
            'exchange_rate_explanation' => '1 '.$expense->converted_to_currency.' = '.$expense->exchange_rate.' '.$expense->currency,
            'manager' => $manager ? [
                'id' => $manager->id,
                'name' => $manager->name,
                'avatar' => ImageHelper::getAvatar($manager),
                'position' => $manager->position ? $manager->position->title : null,
                'status' => $manager->status ? $manager->status->name : null,
            ] : [
                'name' => $expense->manager_approver_name,
            ],
            'manager_approver_approved_at' => $expense->manager_approver_approved_at ?
                DateHelper::formatDate($expense->manager_approver_approved_at, $loggedEmployee->timezone) :
                null,
            'manager_rejection_explanation' => $expense->manager_rejection_explanation,
            'accountant' => $accountant ? [
                'id' => $accountant->id,
                'name' => $accountant->name,
                'avatar' => ImageHelper::getAvatar($accountant),
                'position' => $accountant->position ? $accountant->position->title : null,
                'status' => $accountant->status ? $accountant->status->name : null,
            ] : [
                'name' => $expense->accounting_approver_name,
            ],
            'accounting_approver_approved_at' => ($expense->accounting_approver_approved_at) ?
                DateHelper::formatDate($expense->accounting_approver_approved_at, $loggedEmployee->timezone) :
                null,
            'accounting_rejection_explanation' => $expense->accounting_rejection_explanation,
            'employee' => $expenseEmployee ? [
                'id' => $expenseEmployee->id,
                'name' => $expenseEmployee->name,
                'avatar' => ImageHelper::getAvatar($expenseEmployee),
                'position' => $expenseEmployee->position ? $expenseEmployee->position->title : null,
                'status' => $expenseEmployee->status ? $expenseEmployee->status->name : null,
            ] : [
                'employee_name' => $expense->employee_name,
            ],
        ];

        return $expense;
    }
}
