<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The company instance.
     *
     * @var Company
     */
    public Company $company;

    /**
     * Create a new job instance.
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * This job is executed when
     * * a manager is unassigned,
     * * a manager is deleted (or locked).
     * In both cases, we can’t leave pending expenses as they would never be
     * validated in this case. We will move the expenses in the accounting
     * department for validation.
     */
    public function handle(): void
    {
        // grab all the expenses with the awaiting manager approval status
        // analyze the expense's associated employee
        // if the employee has no more managers, change expense status
        $expenses = $this->company->expenses()
            ->with('employee')
            ->with('employee.managers')
            ->where('company_id', $this->company->id)
            ->where('status', Expense::AWAITING_MANAGER_APPROVAL)
            ->where('employee_id', '!=', null)
            ->get();

        foreach ($expenses as $expense) {
            $employee = $expense->employee;

            if ($employee->managers->count() == 0) {
                Expense::where('id', $expense->id)->update([
                    'status' => Expense::AWAITING_ACCOUTING_APPROVAL,
                ]);
            }
        }
    }
}
