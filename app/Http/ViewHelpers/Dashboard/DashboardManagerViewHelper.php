<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MoneyHelper;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\EmployeeStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneEntry;

class DashboardManagerViewHelper
{
    /**
     * Get all the expenses the manager needs to validate.
     *
     * @param Employee $manager
     * @param Collection $directReports
     * @return SupportCollection|null
     */
    public static function pendingExpenses(Employee $manager, Collection $directReports): ?SupportCollection
    {
        // get the list of employees this manager manages
        $expensesCollection = collect([]);
        $company = $manager->company;

        foreach ($directReports as $directReport) {
            $employeeExpenses = $directReport->directReport->expenses;

            $pendingExpenses = $employeeExpenses->filter(function ($expense) {
                return $expense->status == Expense::AWAITING_MANAGER_APPROVAL;
            });

            foreach ($pendingExpenses as $expense) {
                $employee = $expense->employee;

                $expensesCollection->push([
                    'id' => $expense->id,
                    'title' => $expense->title,
                    'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                    'status' => $expense->status,
                    'category' => ($expense->category) ? $expense->category->name : null,
                    'expensed_at' => DateHelper::formatDate($expense->expensed_at, $manager->timezone),
                    'converted_amount' => $expense->converted_amount ?
                        MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                        null,
                    'url' => route('dashboard.manager.expense.show', [
                        'company' => $company,
                        'expense' => $expense,
                    ]),
                    'employee' => ($employee) ? [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'avatar' => ImageHelper::getAvatar($employee, 18),
                    ] : [
                        'employee_name' => $expense->employee_name,
                    ],
                ]);
            }
        }

        return $expensesCollection;
    }

    /**
     * Get all information about the given expense.
     *
     * @param Expense $expense
     * @param Employee $loggedEmployee
     * @return array
     */
    public static function expense(Expense $expense, Employee $loggedEmployee): array
    {
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

    /**
     * Get the one on ones with the direct report(s) if they exist.
     *
     * @param Employee $manager
     * @param Collection $directReports
     * @return SupportCollection|null
     */
    public static function oneOnOnes(Employee $manager, Collection $directReports): ?SupportCollection
    {
        $oneOnOnesCollection = collect([]);
        $company = $manager->company;
        $now = Carbon::now();

        foreach ($directReports as $directReport) {
            $employee = $directReport->directReport;

            $entry = OneOnOneEntry::where('employee_id', $employee->id)
                ->where('manager_id', $manager->id)
                ->where('happened', false)
                ->latest()
                ->first();

            if (! $entry) {
                // there is no active entry, we need to create one
                $entry = (new CreateOneOnOneEntry)->execute([
                    'company_id' => $company->id,
                    'author_id' => $employee->id,
                    'manager_id' => $manager->id,
                    'employee_id' => $employee->id,
                    'date' => $now->format('Y-m-d'),
                ]);
            }

            $oneOnOnesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
                'position' => (! $employee->position) ? null : $employee->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'entry' => [
                    'id' => $entry->id,
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'url' => route('dashboard.oneonones.show', [
                        'company' => $company,
                        'entry' => $entry,
                    ]),
                ],
            ]);
        }

        return $oneOnOnesCollection;
    }

    /**
     * Get the information about employees who have a contract that ends in
     * the next 3 months or less.
     *
     * @param Employee $manager
     * @param Collection $directReports
     * @return SupportCollection|null
     */
    public static function contractRenewals(Employee $manager, Collection $directReports): ?SupportCollection
    {
        $collection = collect([]);
        $company = $manager->company;
        $now = Carbon::now();

        foreach ($directReports as $directReport) {
            $employee = $directReport->directReport;

            if (! $employee->status) {
                continue;
            }

            if ($employee->status->type == EmployeeStatus::INTERNAL) {
                continue;
            }

            if (! $employee->contract_renewed_at) {
                continue;
            }

            $dateInOneMonth = $now->copy()->addMonths(1);

            if ($employee->contract_renewed_at->isAfter($dateInOneMonth)) {
                continue;
            }

            $collection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
                'position' => (! $employee->position) ? null : $employee->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'contract_information' => [
                    'contract_renewed_at' => DateHelper::formatDate($employee->contract_renewed_at, $manager->timezone),
                    'number_of_days' => $employee->contract_renewed_at->diffInDays($now),
                    'late' => $employee->contract_renewed_at->isBefore($now),
                ],
            ]);
        }

        return $collection;
    }

    /**
     * Get the list of employees who have timesheets to approve by this manager.
     *
     * @param Employee $manager
     * @param Collection $directReports
     * @return array|null
     */
    public static function employeesWithTimesheetsToApprove(Employee $manager, Collection $directReports): ?array
    {
        $employeesCollection = collect([]);
        $company = $manager->company;
        $totalNumberOfTimesheetsToValidate = 0;

        foreach ($directReports as $directReport) {
            $employee = $directReport->directReport;

            $pendingTimesheets = $employee->timesheets()
                ->where('status', Timesheet::READY_TO_SUBMIT)
                ->orderBy('started_at', 'desc')
                ->get();

            $totalNumberOfTimesheetsToValidate += $pendingTimesheets->count();

            if ($pendingTimesheets->count() !== 0) {
                $employeesCollection->push([
                    'id' => $employee->id,
                    'avatar' => ImageHelper::getAvatar($employee, 32),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee,
                    ]),
                ]);
            }
        }

        return [
            'totalNumberOfTimesheetsToValidate' => $totalNumberOfTimesheetsToValidate,
            'employees' => $employeesCollection,
            'url_view_all'=> route('dashboard.manager.timesheet.index', [
                'company' => $manager->company,
            ]),
        ];
    }

    /**
     * Get the information about the opened discipline cases for this manager.
     *
     * @param Company $company
     * @param Collection $directReports
     * @return SupportCollection
     */
    public static function activeDisciplineCases(Company $company, Collection $directReports): SupportCollection
    {
        $disciplineCaseCollection = collect();
        foreach ($directReports as $directReport) {
            $cases = $directReport->directReport->disciplineCases()->where('active', true)->get();

            foreach ($cases as $case) {
                $disciplineCaseCollection->push([
                    'id' => $case->id,
                    'employee' => [
                        'id' => $directReport->directReport->id,
                        'name' => $directReport->directReport->name,
                        'avatar' => ImageHelper::getAvatar($directReport->directReport, 40),
                        'position' => (! $directReport->directReport->position) ? null : $directReport->directReport->position->title,
                        'url' => route('employees.show', [
                            'company' => $company,
                            'employee' => $directReport->directReport,
                        ]),
                    ],
                    'url' => route('dashboard.manager.disciplinecase.show', [
                        'company' => $company,
                        'case' => $case,
                    ]),
                ]);
            }
        }

        return $disciplineCaseCollection;
    }
}
