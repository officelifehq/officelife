<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckIfPendingExpenseShouldBeMovedToAccountingWhenEmployeeChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The manager instance.
     *
     * @var Employee
     */
    public Employee $employee;

    /**
     * Create a new job instance.
     *
     * @param Expense $expense
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Execute the job.
     *
     * This job is executed when
     * * an employee has his manager unassigned,
     * * an employee (who was a manager) is deleted (or locked).
     * In both cases, we can’t leave pending expenses as they would never be
     * validated in this case. We will move the expenses in the accounting
     * department for validation.
     */
    public function handle()
    {
        // if the employee doesn't exist anymore (and was a manager), we should move all the expenses
        try {
            Employee::findOrFail($this->employee->id);
        } catch (ModelNotFoundException $e) {
            $this->movePendingExpenses();
            return;
        }

        // grab all the managers of this employee
        $numberOfManagers = $this->employee->managers->count();

        // if the employee has no manager, we should move all the pending expenses
        // to accounting so they can be approved
        if ($numberOfManagers == 0) {
            $this->movePendingExpenses();
        }

        // if the employee is locked and is a manager, we should move all the expenses
        if ($isManager && $this->employee->locked) {
            $this->movePendingExpenses();
        }
    }

    private function movePendingExpenses(): void
    {
        $expenses = $this->employee->expenses()
            ->where('expenses.status', Expense::AWAITING_MANAGER_APPROVAL)
            ->get();

        foreach ($expenses as $expense) {
            $expense->status = Expense::AWAITING_ACCOUTING_APPROVAL;
            $expense->status;
        }
    }
}
