<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\DirectReport;
use App\Services\Company\Adminland\Expense\DisallowEmployeeToManageExpenses;
use App\Jobs\CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges;

class LockEmployee extends BaseService
{
    private array $data;
    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|exists:employees,id|integer',
            'company_id' => 'required|exists:companies,id|integer',
        ];
    }

    /**
     * Lock an employee's account.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->lockEmployee();
        $this->removeAccountantRole();
        $this->changePotentialExpensesStatuses();
        $this->removeEmployeeHierarchy();
        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);
    }

    private function lockEmployee(): void
    {
        Employee::where('id', $this->employee->id)->update([
            'locked' => true,
        ]);
    }

    /**
     * Remove the accountant role, if it was set.
     */
    private function removeAccountantRole(): void
    {
        $request = [
            'company_id' => $this->data['company_id'],
            'author_id' => $this->author->id,
            'employee_id' => $this->employee->id,
        ];

        if ($this->employee->can_manage_expenses) {
            (new DisallowEmployeeToManageExpenses)->execute($request);
        }
    }

    private function changePotentialExpensesStatuses(): void
    {
        CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges::dispatch($this->employee->company)
            ->onQueue('low');
    }

    /**
     * Remove any link of hierarchy between this employee and other potential employee.
     */
    private function removeEmployeeHierarchy(): void
    {
        DirectReport::where('company_id', $this->data['company_id'])
            ->where('employee_id', $this->employee->id)
            ->delete();

        DirectReport::where('company_id', $this->data['company_id'])
            ->where('manager_id', $this->employee->id)
            ->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_locked',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'employee_locked',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([]),
        ])->onQueue('low');
    }
}
